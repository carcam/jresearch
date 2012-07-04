<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
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

jresearchimport('tables.activity', 'jresearch.admin');
jresearchimport('tables.financier', 'jresearch.admin');

/**
 * This class represents a JResearch project in database.
 *
 */
class JResearchProject extends JResearchActivity{
			
	
	/**
	 * Project's status. It can be: not_started, in_progress, finished
	 *
	 * @var string
	 */
	public $status;
	
	/**
	 * Proposed start date
	 *
	 * @var datetime
	 */
	public $start_date;
	
	/**
	* Proposed end date
	*
	* @var datetime
	*/	
	public $end_date;
	
	/**
	* Url to the an image that represents the project
	*
	* @var string
	*/
	public $url_project_image;	
	
	/**
	 * Project's complete description
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	 * Project's full funding value
	 *
	 * @var float
	 */
	public $finance_value;
	
	/**
	 * Fundings currency
	 *
	 * @var string
	 */
	public $finance_currency;
	
	/**
	 * Holds financiers of the project
	 * @var array
	 */
	protected $_financiers = array();
	
	/**
	 * Holds cooperations of the project
	 * @var array
	 */
	protected $_cooperations = array();
	
	/**
	 * Holds an array with the principal investigators
	 */
	protected $_principals = array();
	
	/**
	 * Holds an array with the non-principal investigators
	 */
	protected $_nonprincipals = array();
	
	
	/**
	 * Class constructor. Maps the class to a Joomla table.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
            parent::__construct( '#__jresearch_project', 'id', $db );
            $this->_type = 'project';
	}
	
	/**
	* Validates the content of the project's information.
	* @return boolean. True if all fields of the project have a valid content.
	*/	
	
	function check(){
/*            $date_pattern = '/^\d{4}-\d{2}-\d{2}$/';

            // Verify the integrity of members
            if(!parent::checkAuthors())
                    return false;

            // Validate dates
            if(!empty($this->start_date)){
                    if(!preg_match($date_pattern, $this->start_date)){
                            $this->setError(JText::_('Please provide a proposed start date for the project in format YYYY-MM-DD'));
                            return false;
                    }
            }

            if(!empty($this->end_date)){
                    if(!preg_match($date_pattern, $this->start_date)){
                            $this->setError(JText::_('Please provide a proposed deadline for the project in format YYYY-MM-DD'));
                            return false;
                    }
            }

            if((!empty($this->end_date) && $this->end_date != '0000-00-00') && (!empty($this->start_date) && $this->start_date != '0000-00-00')){
                    $startDateObj = new JDate($this->start_date);
                    $endDateObj = new JDate($this->end_date);

                    if($endDateObj->toUnix() < $startDateObj->toUnix()){
                            $this->setError(JText::_('Start date is greater than end date'));
                            return false;
                    }
            }

            if(!empty($this->finance_value))
            {
                    $this->finance_value = round($this->finance_value, 2);

                    if($this->finance_value <= 0.0)
                    {
                            $this->setError(JText::_('Funding must be greater than 0'));
                    }
            }


            if(empty($this->title)){
                    $this->setError(JText::_('Provide a title for the project'));
                    return false;
            }
*/
            return true;

	}
	
	/**
	 * Loads a row from the database and binds the fields to the object properties.
	 *
	 * @param int $oid
	 * @return True if successful
	 */
	public function load($oid = null){
    	$result = parent::load($oid);
        $this->_loadAuthors($oid);
        return $result;
	}
	
	/**
	 * Returns the complete list of authors (internal and externals) suitably ordered. 
	 * Internal authors are displayed in format [lastname, firstname].
	 * External authors are taken as they appear in the database.
	 *
	 * @return array Array of mixed elements. Internal authors are instances of JResearchMember.
	 * External ones are strings.
	 */
	public function getAuthors(){
		if(empty($this->_authorsArray)){
			$this->_authorsArray = array();
			
			if(!empty($this->authors))
				$tmpAuthorsArray = explode(';', $this->authors);
			else
				$tmpAuthorsArray = array();	
			
			foreach($tmpAuthorsArray as $rauthor){
				$authorComps = explode('|', $rauthor);
				if(count($authorComps) == 1){
					$author = $rauthor;
				}else{
					$author = $authorComps[0];
				}
				
				if(is_numeric($author)){
					$member = JTable::getInstance('Member', 'JResearch');
					$member->load((int)$author);
					$this->_authorsArray[] = $member;
				}else{
					$this->_authorsArray[] = $author;
				}
			}
		}
		
		return $this->_authorsArray;
		
	}
	
	/**
	 * Inserts a new row if id is zero or updates an existing row in the 
	 * database table.
	 *
	 * @param boolean $updateNulls If false, null object variables are not updated
	 * @return true if successful
	 */
	public function store($updateNulls = false){
		// Time to insert the information of the publication per se			
 		$db = JFactory::getDBO();
 		$user = JFactory::getUser();
		$now = new JDate(); 		

		if(isset($this->id)){
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
	    $deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_internal_author').' WHERE '.$db->nameQuote('id_project').' = '.$db->Quote($this->id);
		$deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_external_author').' WHERE '.$db->nameQuote('id_project').' = '.$db->Quote($this->id);
			
		$db->setQuery($deleteInternalQuery);
		JError::raiseWarning(1, $deleteInternalQuery);
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}	
		
		$db->setQuery($deleteExternalQuery);
		JError::raiseWarning(1, $deleteExternalQuery);		
		if(!$db->query()){			
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}		
		
        // Insert members' information
        $orderField = $db->nameQuote('order');
        $idPubField = $db->nameQuote('id_project');
        $idStaffField = $db->nameQuote('id_staff_member');
        $isPrincipalField = $db->nameQuote('is_principal');
		$orderField = $db->nameQuote('order');        		
		$order = 0;
		if(empty($this->authors))
			$authorsArray = array();
		else
			$authorsArray = explode(';', $this->authors);
		
		foreach($authorsArray as $authorEntry){
			$authorEntryArray = explode('|', $authorEntry);
			$author = $authorEntryArray[0];
			$idValue = $db->Quote($this->id);
			$orderValue = $db->Quote($order);
			$principal = $db->Quote((isset($authorEntryArray[1]) && $authorEntryArray[1] == '1'), false);
			
			if(is_numeric($author)){
				$id_staff_member = $db->Quote($author);
				$idStaffField = $db->nameQuote('id_staff_member');
				$tableName = $db->nameQuote('#__jresearch_project_internal_author');
            	$query = "INSERT INTO $tableName($idPubField,$idStaffField,$orderField,$isPrincipalField) VALUES ($this->id, $id_staff_member,$order,$principal)";
			}else{
				$authorField = $db->nameQuote('author_name');
				$tableName = $db->nameQuote('#__jresearch_project_external_author');
				$authorName = $db->Quote($author);
				$query = "INSERT INTO $tableName($idPubField, $authorField, $orderField, $isPrincipalField) VALUES($this->id, $authorName, $order, $principal)";				
			}			
			
			$db->setQuery($query);
			JError::raiseWarning(1, $query);			
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}
			
			$order++;
		}
		
		//Time to remove research areas too
		$researchareaRemoveQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_researcharea').' WHERE id_project = '.$db->Quote($this->id);
		$db->setQuery($researchareaRemoveQuery);
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}		
		
		//And to insert them again
		$idsAreas = explode(',', $this->id_research_area);
		foreach($idsAreas as $area){
			$insertAreaQuery = 'INSERT INTO '.$db->nameQuote('#__jresearch_project_researcharea').'(id_project, id_research_area) VALUES('.$db->Quote($this->id).', '.$db->Quote($area).')';	
			$db->setQuery($insertAreaQuery);
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}					
		}
		
		//Time to remove keyword relationships
		$keywordsRemoveQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_keyword').' WHERE id_project = '.$db->Quote($this->id);
		$db->setQuery($keywordsRemoveQuery);	
		if(!$db->query()){			
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;			
		}
		
		
		//Time to insert keywords
		$keywords = explode(';', trim($this->keywords));
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
				
				$insertProjectKeywordQuery = 'INSERT INTO '.$db->nameQuote('#__jresearch_project_keyword').' VALUES('.$db->Quote($this->id).', '.$db->Quote($keyword).')';
				$db->setQuery($insertProjectKeywordQuery); 
				if(!$db->query()){
					$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
					return false;
				}								
			}		
		}
		     
	    return true;			
	}
	
	/**
	 * Get the members that were defined as principal investigators in the 
	 * project.
	 * 
	 * @return array
	 */
	function getPrincipalInvestigators(){
		if($this->_principals == null){
			$this->getAuthors();
			$this->_principals = array();
			$this->_nonprincipals = array();
			$index = 0;
			$rawAuthors = explode(';', $this->authors);
			foreach($rawAuthors as $auth){
				$authComps = explode('|', $auth);
				if($authComps > 1)
					$this->_principals[] = $this->_authorsArray[$index];
				else
					$this->_nonprincipals[] = $this->_authorsArray[$index];					
				
				$index++;
			}
		}
		
		return $this->_principals;
	}
	
	/**
	 * Get the members that were not defined as principal investigators in the 
	 * project.
	 * 
	 * @return array
	 */
	function getNonPrincipalInvestigators(){
		if($this->_nonprincipals == null){
			$this->getPrincipalInvestigators();
		}
				
		return $this->_nonprincipals;
	}
}

?>