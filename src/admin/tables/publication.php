<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport('joomla.utilities.date');
jresearchimport('tables.activity', 'jresearch.admin');

/**
 * This class defines the base for all types of publications managed by JResearch.
 * Extensions for JResearch supported types imply write JResearchPublication derived
 * classes.
 *
 */
class JResearchPublication extends JResearchActivity{

	
	/**
	 * Extra comments written by the author.
	 *
	 * @var string
	 */
	public $comments;
	public $internal;
	
	
	/**
	 * The acceptance rate of the journal where the publication was accepted.
	 *
	 * @var float
	 */
	public $journal_acceptance_rate;	
	
	/**
	 * The impact factor of the publication
	 *
	 * @var float
	 */
	public $impact_factor;
	
	/**
	 * @var string
	 */
	public $awards;		
	public $year;

	/**
	 * Label used for programs like Bibtex when trying to cite a publication.
	 * Used by JResearch automatic citation plugin.
	 *
	 * @var string
	 */
	public $citekey;
		
	public $abstract;	
	public $pubtype;
	public $keywords;	
	public $note;
	
	/**
	 * Optional cover URL for the publication
	 *
	 * @var string
	 */
	public $cover;
	
	/**
	 * Digital Object identifier
	 * @var string
	 */
	public $doi;	
	public $issn;
    public $isbn;
    public $volume;
	public $number;
	public $pages;
	public $month;	    
	public $crossref;
	public $journal;
	public $publisher;
	public $editor;
	public $series;
	public $address;		
	public $edition;
	public $howpublished;
	public $booktitle;
	public $organization;
	public $chapter;
	public $type;
	public $key;	
	public $patent_number;
	public $filing_date;
	public $issue_date;	
	public $claims;
	public $drawings_dir;
	public $country;
	public $office;
	public $school;
	public $institution;
	public $day;
	public $access_date;
	public $extra;
	public $online_source_type;
	public $digital_source_type;

	/**
	* Associative array with supported records types and their printable names.
	*/
	static $_types;
	
	

	/**
	 * Class constructor. It maps the entity to a Joomla table.
	 *
	 * @param JDatabase $db
	 */
	public function __construct(){
		$db = JFactory::getDBO();
		parent::__construct( '#__jresearch_publication', 'id', $db );		
		$this->year = 0;
		$this->_type = 'publication';
		$this->id_research_area = array();		
	}
	
	/**
	* Parse the publication into an associative array considering just the public 
	* attributes.
	* @return array
	*/
	public function __toArray(){
		$properties = get_class_vars(get_class($this));
		$resultProperties = array();
		foreach($properties as $k=>$v){
			if($k{0} != '_')
				$resultProperties[$k] = $v;
		}
		
		return $resultProperties;
	}
	
	/**
	 * Returns an array with the names of the attributes that compound
	 * the class JResearchPublication.
	 * @return array
	 */
	protected function _getClassAttributes(){
		$properties = get_class_vars("JResearchPublication");
		$result = array();
		foreach($properties as $k=>$v){
			if($k{0} != '_')
				$result[] = $k;
		}
		return $result;
	}
		
	
	
	/**
	 * Loads a row from the database and binds the fields to the object properties.
	 *
	 * @param int $oid
	 * @return True if successful
	 */
	public function load($oid = null){		
		$k = $this->_tbl_key;
		if(!parent::load($oid))
			return false;
 
		$this->_loadAuthors();
		return true;			
	}
	
	
	
	/**
	* Verify if the publication is ready to be saved in the database. To get the
	* cause of a failed check, method getError must be invoked. 
	* @return boolean
	*/
	public function check(){
		$withoutErrors = true;		
		
		// Verify authors integrity
		if(!parent::checkAuthors())
			return false;
				
		// Verify if title is not empty
		if(empty($this->title)){
			$this->title = trim($this->title);			
			$this->setError(JText::_('JRESEARCH_REQUIRE_PUBLICATION_TITLE'));
			$withoutErrors = false;
		}
		
		// Verify year
		if(!empty($this->year)){
			$this->year = trim($this->year);			
			if(!preg_match('/^[1-9]\d{3}$/',$this->year)){
				$this->setError(JText::_('JRESEARCH_PROVIDE_VALID_YEAR')); 
				$withoutErrors = false;
			}
					
		}
		
		if(!empty($this->keywords)){
			$this->doi = trim($this->doi);
			jresearchimport('helpers.charsets', 'jresearch.admin');
			$extra = implode('', JResearchCharsetsHelper::getLatinWordSpecialChars());	
					
			if(!preg_match("/^[-_'\w$extra\s\d]+(,[-_'\w$extra\s\d]+)*[,]?$/", $this->keywords)){
				$this->setError(JText::_('JRESEARCH_PROVIDE_VALID_KEYWORDS'));
				$withoutErrors = false;
			}
		}
		
		if(!empty($this->journal_acceptance_rate)){
			$this->journal_acceptance_rate = trim($this->journal_acceptance_rate);			
			if(!is_numeric($this->journal_acceptance_rate)){
				$this->setError(JText::_('JRESEARCH_PROVIDE_VALID_JOURNAL_ACCEPTANCE_RATE'));
				$withoutErrors = false;
			}
		}
		
		if(!empty($this->impact_factor)){
			$this->journal_acceptance_rate = trim($this->impact_factor);			
			if(!is_numeric($this->impact_factor)){
				$this->setError(JText::_('JRESEARCH_PROVIDE_VALID_IMPACT_FACTOR'));
				$withoutErrors = false;
			}
		}
		

		return $withoutErrors;
	}
	
	
	/**
	 * Inserts a new row if id is zero or updates an existing row in the 
	 * database table.
	 *
	 * @param boolean $updateNulls If false, null object variables are not updated
	 * @return true if successful
	 */
	public function store(){				
		// Time to insert the information of the publication per se			
 		$db = JFactory::getDBO();
 		$user = JFactory::getUser();
		$now = new JDate(); 		

		if(!isset($this->id)){
			JError::raiseWarning(1, 'Changing the created_by');
			$this->created = $now->toMySQL();
            $author = JRequest::getVar('created_by', $user->get('id'));
            $this->created_by = $author;
		}
		
        $this->modified = $now->toMySQL();
        $this->modified_by = $author;
        if(empty($this->alias))
             $this->alias = JFilterOutput::stringURLSafe($this->name);
		
		
		$result = parent::store();
		
		if(!$result)
			return false;
   
		// Delete the information about internal and external references
		$deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_publication_internal_author').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($this->id);
		$deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_publication_external_author').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($this->id);
		
		$db->setQuery($deleteInternalQuery);
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}	
		
		$db->setQuery($deleteExternalQuery);
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}		
		
		$orderField = $db->nameQuote('order');
		$idPubField = $db->nameQuote('id_publication');
       
		$authorsArray = explode(';', $this->authors);
		$order = 0;
		foreach($authorsArray as $author){
			if(empty($author)) continue;
			$idValue = $db->Quote($this->id);
			$orderValue = $db->Quote($order);
			
			if(is_numeric($author)){
				$id_staff_member = $db->Quote($author);
				$idStaffField = $db->nameQuote('id_staff_member');
				$tableName = $db->nameQuote('#__jresearch_publication_internal_author');
				$query = "INSERT INTO $tableName($idPubField,$idStaffField,$orderField) VALUES ($idValue, $id_staff_member, $orderValue)";				
			}else{
				$authorField = $db->nameQuote('author_name');
				$tableName = $db->nameQuote('#__jresearch_publication_external_author');
				$authorName = $db->Quote($author);
				$query = "INSERT INTO $tableName($idPubField, $authorField, $orderField) VALUES($idValue, $authorName, $orderValue)";				
			}			
			
			$db->setQuery($query);			
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}
			
			$order++;
		}

		//Time to remove research areas too
		$researchareaRemoveQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_publication_researcharea').' WHERE id_publication = '.$db->Quote($this->id);
		$db->setQuery($researchareaRemoveQuery);
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}		
		//And to insert them again
		$idsAreas = explode(',', $this->id_research_area);
		foreach($idsAreas as $area){
			$insertAreaQuery = 'INSERT INTO '.$db->nameQuote('#__jresearch_publication_researcharea').'(id_publication, id_research_area) VALUES('.$db->Quote($this->id).', '.$db->Quote($area).')';	
			$db->setQuery($insertAreaQuery);
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}					
		}
		
		//Time to remove keyword relationships
		$keywordsRemoveQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_publication_keyword').' WHERE id_publication = '.$db->Quote($this->id);
		$db->setQuery($keywordsRemoveQuery);	
		if(!$db->query()){			
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;			
		}
		
		
		//Time to insert keywords
		$keywords = explode(',', trim($this->keywords));
		$keywords = array_unique($keywords);
		foreach($keywords as $keyword){
			if(!empty($keyword)){
				$selectKeywordQuery = 'SELECT * FROM '.$db->nameQuote('#__jresearch_keyword').' WHERE keyword = '.$db->Quote($keyword);
				$db->setQuery($selectKeywordQuery);
				$resultKeyword = $db->loadResult();
				if(empty($resultKeyword)){				
					$insertKeywordQuery = 'INSERT INTO '.$db->nameQuote('#__jresearch_keyword').' VALUES('.$db->Quote($keyword).')';
					$db->setQuery($insertKeywordQuery);
					if(!$db->query()){
						$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
						return false;
					}									
				}
				
				$insertPublicationKeywordQuery = 'INSERT INTO '.$db->nameQuote('#__jresearch_publication_keyword').' VALUES('.$db->Quote($this->id).', '.$db->Quote($keyword).')';
				$db->setQuery($insertPublicationKeywordQuery); 
				if(!$db->query()){
					$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
					return false;
				}								
			}		
		}
		     
	    return true;
	}

	/**
	 * Returns an array of book's editors.
	 *
	 */
	function getEditors(){
		if(isset($this->editor)){
			$editor = trim($this->editor);
			if(!empty($editor))
                return preg_split('/\sand\s|,|;/',$editor);
			else
				return array();	
		}else
			return array();	
			
	}
	
	
	/**
	 * Returns the string representation of the publication.
	 * @return string
	 */
	function __toString(){
		return $this->citekey+': '+$this->title;
	}

	/**
	 * Returns the completed publication referred by field crossref.
	 * 
	 * @return JResearchPublication null if the referenced publication could not be found
	 * or the publication does not have a value for crossref field.
	 */
	function getReferencedRecord(){
		$ref = null;
		
		if(isset($this->crossref)){
			$cf = trim($this->crossref);
			if(!empty($cf)){
				$ref = JResearchPublication::getByCitekey($this->crossref);
			}
		}
		
		return $ref;
	}
	
	/**
	 * Returns an associative array with the fields that are defined in any
	 * referenced publication.
	 * 
	 * @return array
	 */
	function getReferencedFields(){
		$ref = $this->getReferencedRecord();
		$exceptions = array('checked_out', 'checked', 'internal');
		$result = array();
		
		if($ref != null){
			if($ref->published){
				$properties = $this->_getClassAttributes();
				foreach($properties as $prop){
					if(!in_array($prop, $exceptions)){
						if(empty($this->$prop)){
							if(!empty($ref->$prop)){
								$result[$prop] = $ref->$prop;
							}
						}
					}
				}
			}
		}
		
		return $result;
		
	}
	
	
	/**
	 * Sets internal status for a single or a set of publications.
	 *
	 * @param array $cid Array of publication ids
	 * @param int $value New value for internal field
	 * @param int $user_id Id of the user performing the operation
	 * @return boolean True if success.
	 */
	function toggleInternal($cid=null, $value = 0, $user_id = 0){
 		JArrayHelper::toInteger( $cid );
        $user_id    = (int) $user_id;
        $publish    = (int) $value;
        $k            = $this->_tbl_key;
        if (count( $cid ) < 1)
        {
            if ($this->$k) {
                $cid = array( $this->$k );
            } else {
                $this->setError("No items selected.");
                return false;
            }
        }
        $cids = $k . '=' . implode( ' OR ' . $k . '=', $cid );
        $query = 'UPDATE '. $this->_tbl
        . ' SET internal = ' . (int) $value
        . ' WHERE ('.$cids.')';

        $checkin = in_array( 'checked_out', array_keys($this->getProperties()) );
        if ($checkin)
        {
            $query .= ' AND (checked_out = 0 OR checked_out = '.(int) $user_id.')';
        }
        $this->_db->setQuery( $query );
        if (!$this->_db->query())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (count( $cid ) == 1 && $checkin)
        {
            if ($this->_db->getAffectedRows() == 1) {
                $this->checkin( $cid[0] );
                if ($this->$k == $cid[0]) {
                    $this->internal = $value;
                }
            }
        }
        $this->setError('');
        return true;		
	}
	
}
?>