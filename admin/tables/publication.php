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


jimport('joomla.utilities.date');

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'activity.php');

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
	public $featured;


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
            // Add custom properties
            $this->_addCustomFields();
	}

        /**
         * Adds to the object those fields defined in external plugins (jresearch-pubtype)
         */
        private function _addCustomFields(){
            $extraFields = JResearchPluginsHelper::getPubTypeColumns();
            foreach($extraFields as $field){
                $this->$field = 0;
            }
        }
	
	/**
	* Parse the publication into an associative array considering just the public 
	* attributes.
	* @return array
	*/
	public function __toArray(){
            $db = JFactory::getDBO();
            $properties = get_class_vars(get_class($this));
            $resultProperties = array();
            foreach($properties as $k=>$v){
                if($k{0} != '_')
                    $resultProperties[$k] = $v;
            }

            $extraProperties = JResearchPluginsHelper::getPubTypeColumns();
            foreach($extraProperties as $property){
                $db->setQuery('SELECT '.$db->nameQuote($property).' FROM '.$db->nameQuote('#__jresearch_publication').' WHERE id = '.$db->Quote($this->id));
                $result = $db->loadResult();
                $resultProperties[$property]= $fieldResult;
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
            $extra = JResearchPluginsHelper::getPubTypeColumns();
            return array_merge($result, $extra);
	}
		
	/**
	 * Returns the publication with the citekey provided. The citekey is
	 * the label used for tools like Bibtex to identify a record in the database
	 * when citing.
	 * @param string $citekey
	 * @return JResearchPublication
	 */

	public static function getByCitekey($citekey){
		$result = null;
		$db = JFactory::getDBO();
		$citekeyName = $db->nameQuote('citekey');
		$citekeyQ = $db->Quote($citekey, false);
		$query = "SELECT * FROM ".$db->nameQuote('#__jresearch_publication')." WHERE $citekeyName = $citekeyQ";
		$db->setQuery($query);
		$result = $db->loadAssoc();
		$publication = new JResearchPublication();

		if(!empty($result)){
			$publication->bind($result);
			$publication->_loadAuthors();
			return $publication;
		}else{
			return null;
		}

	}
	
	/**
	 * Returns the publication with the database integer id provided. 
	 *
	 * @param int $id
	 * @return JResearchPublication or null.
	 * 
	 */
	public static function getById($id){
		$result = null;
		$db = JFactory::getDBO();
		$idQ = $db->Quote($id, false);
		$query = "SELECT * FROM ".$db->nameQuote('#__jresearch_publication')." WHERE id = $idQ";
		$db->setQuery($query);
		$result = $db->loadAssoc();
		$publication = JTable::getInstance('Publication', 'JResearch');

		if(!empty($result)){
			$publication->bind($result, array(), true);
			return $publication;
		}else{
			return null;
		}	
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

            if ($oid === null) {
               return false;
            }
        
            $this->$k = $oid;

            $db =& $this->getDBO();
            $table = $db->nameQuote($this->_tbl);
                    $this->_loadAuthors();

            $query = 'SELECT * '
            . ' FROM '.$db->nameQuote($this->_tbl)
            . ' WHERE '.$db->nameQuote($this->_tbl_key).' = '.$db->Quote($oid);
            $db->setQuery($query);

            if (($result = $db->loadAssoc( ))) {
                return $this->bind($result);
            }else{
                $this->setError( $db->getErrorMsg() );
                return false;
            }
			
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
                    require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'charsets.php');
                    $extra = implode('', JResearchCharsetsHelper::getLatinWordSpecialChars());

                    if(!preg_match("/^[-_'\w$extra\s\d]+([,;][-_'\w$extra\s\d]+)*[,;]*$/", $this->keywords)){
                            $this->setError(JText::_('Error in the keywords field. They must be provided as several words separated by commas'));
                            $withoutErrors = false;
                    }
            }

            if(!empty($this->journal_acceptance_rate)){
                    $this->journal_acceptance_rate = trim($this->journal_acceptance_rate);
                    if(!is_numeric($this->journal_acceptance_rate)){
                            $this->setError(JText::_('Journal acceptance rate must be a number'));
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
	public function store($updateNulls = false){				
            $db = $this->getDBO();
            // Time to insert the information of the publication per se
            $j = $this->_tbl_key;

            $isNew = $this->$j?false:true;
            if($isNew){
                    $now = new JDate();
                    $this->created = $now->toMySQL();
            }

            $parentProperties = $this->_getClassAttributes();
            $parentObject = (object)array();
            $parentObject->$j = $this->$j;
            foreach($parentProperties as $prop){
                     if($this->$prop !== null)
                            $parentObject->$prop = $this->$prop;
            }
            // Time to insert the attributes
            if($this->$j){
                    $ret = $db->updateObject( $this->_tbl, $parentObject, $this->_tbl_key, $updateNulls );
            }else{
                    $ret = $db->insertObject( $this->_tbl, $parentObject, $this->_tbl_key );
                    $this->$j = $db->insertid();
            }

            if( !$ret ){
                $this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
                return false;
            }
   

            // Delete the information about internal and external references
            $deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_publication_internal_author').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($this->$j);
            $deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_publication_external_author').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($this->$j);

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

            foreach($this->_internalAuthors as $author){
                    $id_staff_member = $db->Quote($author['id_staff_member']);
                    $idStaffField = $db->nameQuote('id_staff_member');
                    $order = $db->Quote($author['order']);
                    $tableName = $db->nameQuote('#__jresearch_publication_internal_author');
                    $insertInternalQuery = "INSERT INTO $tableName($idPubField,$idStaffField,$orderField) VALUES ($this->id, $id_staff_member,$order)";
                    $db->setQuery($insertInternalQuery);
                    if(!$db->query()){
                            $this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
                            return false;
                    }
            }

            foreach($this->_externalAuthors as $author){
                    $order = $db->Quote($author['order'], false);
                    $authorName = $db->Quote($db->getEscaped($author['author_name'], true), false);

                    $authorField = $db->nameQuote('author_name');
                    $tableName = $db->nameQuote('#__jresearch_publication_external_author');
                    $insertExternalQuery = "INSERT INTO $tableName($idPubField, $authorField, $orderField) VALUES($this->id, $authorName, $order)";
                    $db->setQuery($insertExternalQuery);
                    if(!$db->query()){
                            $this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
                            return false;
                    }
            }
     
	    return true;
	}

	/**
	* Returns an array with the supported publications datatypes. 
	* @param $type native retrieves types supported originally by J!Research,
        * extended, only the ones available as plugins.
        *
	* @return array
	*/	
	public static function getPublicationsSubtypes($mode = 'all'){
            $db = JFactory::getDBO();
            $result1 = array();
            $result2 = array();

            if($mode == 'all'){
                $query1 = 'SELECT '.$db->nameQuote('name').' FROM '.$db->nameQuote('#__jresearch_publication_type');
                $db->setQuery($query1);
                $result1 = $db->loadResultArray();
                $query2 = 'SELECT '.$db->nameQuote('element').' FROM '.$db->nameQuote('#__plugins').' WHERE folder = '.$db->Quote('jresearch-pubtypes').' AND '.$db->nameQuote('published').' = 1';
                $db->setQuery($query2);
                $result2 = $db->loadResultArray();
            }elseif($mode == 'native'){
                $query1 = 'SELECT '.$db->nameQuote('name').' FROM '.$db->nameQuote('#__jresearch_publication_type');
                $db->setQuery($query1);
                $result1 = $db->loadResultArray();                
            }elseif($mode == 'extended'){
                $query2 = 'SELECT '.$db->nameQuote('element').' FROM '.$db->nameQuote('#__plugins').' WHERE folder = '.$db->Quote('jresearch-pubtypes').' AND '.$db->nameQuote('published').' = 1';
                $db->setQuery($query2);
                $result2 = $db->loadResultArray();                
            }

            return array_merge($result1, $result2);
	}

	
	/**
	 * Returns an array of book's editors.
	 *
	 */
	function getEditors(){
            if(isset($this->editor)){
                $editor = trim($this->editor);
                if(!empty($editor))
                        return preg_split("/and|,|;/",$editor);
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
