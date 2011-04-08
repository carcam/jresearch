<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * This class represents a staff member.
 *
 */
class JResearchMember extends JTable{
	/**
	 * Database integer id
	 *
	 * @var int
	 */
	public $id;
	
	/**
	 * Is it a former member?
	 *
	 * @var bool
	 */
	public $former_member;
	
	/**
	* Member's username
	*/	
	public $username;
	
	/**
	 * Member's first name
	 *
	 * @var string
	 */
	public $firstname;
	
	/**
	 * Member's lastname
	 *
	 * @var string
	 */
	public $lastname;
	
	/**
	* Member's email
	* @var string
	*/	
	public $email;
	
	/**
	 * Research area's id
	 *
	 * @var int
	 */
	public $id_research_area;
	
	/**
	 * Member's personal page
	 *
	 * @var string
	 */
	public $url_personal_page;
	
	/**
	* Member's position
	*
	* @var string
	*/
	public $position;	
	
	/**
	* Member's location
	* 
	* @var string
	*/
	public $location;
	
	/**
	 * Published status
	 *
	 * @var boolean
	 */
	public $published;
	
	/**
	 * Ordering number
	 * @var int
	*/
	public $ordering;
	
	/**
	 * Member's phone number
	 *
	 * @var string
	 */
	public $phone_or_fax;
	
	
	/**
	 * Member's photo
	 *
	 * @var string
	 */
	public $url_photo;
	
	/**
	 * Member's description text
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	* User id of the author who is editing the project.
	* 
	* @var int
	*/
	public $checked_out;

	/**
	* When the project was checked out.
	* 
	* @var datetime
	*/
	public $checked_out_time;
	
	
	public $created;
	public $created_by;
	public $modified;
	public $modified_by;
	
	/**
	 * 
	 * Cache for research area objects.
	 * @var arry
	 */
	public $_areas;


	/**
	 * Class constructor. Maps the class to a Joomla table.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct( '#__jresearch_member', 'id', $db );
	}

	/**
	 * Returns the string representation of the member
	 *
	 * @return unknown
	 */
	function __toString(){
		return "$this->lastname, $this->firstname";
	}

	/**
	* Binds the information of the indicated username, so common fields like email and name
	* are imported into the object. Used for impòrting members from Joomla tables.
	*/	
	function bindFromUser($username){
            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');

            $db =& JFactory::getDBO();
            $query = 'SELECT * FROM '.$db->nameQuote('#__users').' WHERE '.$db->nameQuote('username').' = '.$db->Quote($username);
            $db->setQuery($query);

            $result = $db->loadAssoc();
            $this->username = $result['username'];
            $this->email = $result['email'];
            $arrayName = JResearchPublicationsHelper::getAuthorComponents($result['name']);
            if(isset($arrayName['firstname'])){
                $this->firstname = trim($arrayName['firstname']);
                $this->firstname .= (isset($arrayName['jr'])?' '.$arrayName['jr']:'');
            }

            $this->lastname = (isset($arrayName['von'])?$arrayName['von'].' ':'');
            $this->lastname .= $arrayName['lastname'];
			
	}
	
	/**
	 * Inherited from JTable, it updates research area tables
	 * @see trunk/Joomla16/libraries/joomla/database/JTable::store()
	 */
	function store(){
		// Time to insert the member of the publication per se	
		jimport('joomla.utilities.date');				

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
		
		$result = parent::store();
		if(!$result)
			return $result;
				
		//Time to remove research areas too
		$researchareaRemoveQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_member_researcharea').' WHERE id_member = '.$db->Quote($this->id);
		$db->setQuery($researchareaRemoveQuery);
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}		
		
		//And to insert them again
		$idsAreas = explode(',', $this->id_research_area);
		foreach($idsAreas as $area){
			$insertAreaQuery = 'INSERT INTO '.$db->nameQuote('#__jresearch_member_researcharea').'(id_member, id_research_area) VALUES('.$db->Quote($this->id).', '.$db->Quote($area).')';	
			$db->setQuery($insertAreaQuery);
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}					
		}

		return true;

	}

	/**
	 * Binds data from the member table if the username exists in the member-table
	 *
	 * @param string $username
	 */
	function bindFromUsername($username)
	{
            $db =& JFactory::getDBO();

            $query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('username').' = '.$db->quote($username);
            $db->setQuery($query);

            $result = $db->loadAssoc();

            $this->bind($result);
	}
	
	/**
	* Validates the content of the member's profile information.
	* @return boolean. True if all fields of the object have a valid content.
	*/	
	function check(){
            $name_pattern = '/\w[-_\w\s.]*/';
            $phone_pattern = '/\d[-\d]+/';
            $email_pattern = '/^(\w[-\w.]*)@([-a-z0-9]+(\.[-a-z0-9]+)*\.(com|edu|infocom|edu|gov|int|mil|net|org|biz|info|name|museum|coop|aero|[a-z][a-z]))$/i';

            // Validate first and lastname
            if(!preg_match($name_pattern, $this->lastname)){
                    $this->setError(JText::_('Lastname can only contain alphabetic characters plus ._- characters with neither leading nor trailing whitespaces'));
                    return false;
            }

            if(!preg_match($name_pattern, $this->firstname)){
                    $this->setError(JText::_('First name can only contain alphabetic characters plus ._- characters with neither leading nor trailing whitespaces'));
                    return false;
            }

            if($this->phone_or_fax){
                    if(!preg_match($phone_pattern, $this->phone_or_fax)){
                            $this->setError(JText::_('Phone numbers can only contain digits and scores'));
                            return false;
                    }

            }

            if($this->email){
                    if(!preg_match($email_pattern, $this->email)){
                            $this->setError(JText::_('Please provide a valid e-mail address'));
                            return false;
                    }

            }

            return true;		
	}
	
	/**
	 * Returns position of the member as an Table object
	 * @return JResearchMember_position Object or null
	 */
	public function getPosition()
	{
		if(intval($this->position) > 0){
			$position = JTable::getInstance('Member_position', 'JResearch');
			$position->load($this->position);
			
			return $position;
		}
		
		return null;
	}
	
	/**
	 * Returns array of teams, where the member is a member of these teams.
	 * @return array
	 */
	public function getTeams()
	{
		$teams = array();
		
		//Get teams
		$db =& JFactory::getDBO();
		$table = '#__jresearch_team_member';
		$id = $db->nameQuote('id_team');
		$id_member = $db->nameQuote('id_member');
		
		$query = 'SELECT '.$id.' FROM '.$table.' WHERE '.$id_member.'='.$this->id;
		$db->setQuery($query);
		
		$ids = $db->loadResultArray();
		
		foreach($ids as $id)
		{
			$team = new JResearchTeam($db);
			$team->load($id);
			$teams[] = $team;
		}
		
		return $teams;
	}

        /**
         * This function binds a member from an array of name components.
         * @param array $authorComps Associative array containing values for keys:
         * first, last, von and jr corresponding to the components of an author name
         * in the bibtex standard.
         */
        function bindFromArray(array $authorComps){
            $lastname = strtolower($authorComps['last']);           
            $firstname = strtolower($authorComps['first']);
            $changedlast = false;
            $changedfirst = false;
            $db = JFactory::getDBO();

            $query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '
                     .' LOWER(lastname) = '.$db->Quote($lastname).' AND LOWER(firstname) = '.$db->Quote($firstname);

            $db->setQuery($query);
            $result = $db->loadAssoc();
            if(!empty($result)){
                $this->bind($result);
                return true;
            }

            //Now try the full name
             if(!empty($authorComps['von'])){
                $lastname = strtoupper($authorComps['von']).' '.$lastname;
                $changedlast = true;
             }

             if(!empty($authorComps['jr'])){
                $firstname = $firstname.' '.strtoupper($authorComps['jr']);
                $changedfirst = true;
             }

             //Try to run the query again
            if($changedfirst || $changedlast){
                $query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '
                         .' LOWER(lastname) = '.$db->Quote($lastname).' AND LOWER(firstname) = '.$db->Quote($firstname);

                $db->setQuery($query);
                $result = $db->loadAssoc();
                if(!empty($result)){
                    $this->bind($result);
                    return true;
                }

            }

            return false;

        }
        
        /**
         * (non-PHPdoc)
         * @see trunk/Joomla16/libraries/joomla/database/JTable::delete()
         */
        function delete($oid){
			$db = JFactory::getDBO();

			$k = $this->_tbl_key;
			$oid = (is_null($oid)) ? $this->$k : $oid;			
			$result = parent::delete($oid);

			if(!$result)
				return $result;			
		
			$publicationsTable = $db->nameQuote('#__jresearch_publication_internal_author');
			$projectsTable = $db->nameQuote('#__jresearch_project_internal_author');
			$thesesTable = $db->nameQuote('#__jresearch_thesis_internal_author');
			$teamsTable = $db->nameQuote('#__jresearch_team_member');
			$areasTable = $db->nameQuote('#__jresearch_member_researcharea');

			$db->setQuery('DELETE FROM '.$publicationsTable.' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($oid));		
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}	
			
			$db->setQuery('DELETE FROM '.$projectsTable.' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($oid));		
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}	
			
			$db->setQuery('DELETE FROM '.$thesesTable.' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($oid));
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}	
						
			$db->setQuery('DELETE FROM '.$teamsTable.' WHERE '.$db->nameQuote('id_member').' = '.$db->Quote($oid));			
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}	
						
			$db->setQuery('DELETE FROM '.$areasTable.' WHERE '.$db->nameQuote('id_member').' = '.$db->Quote($oid));			
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}	
						
			return true;		        	
        }
        
	/**
	 * 
	 * Returns an array with the research area objects associated to 
	 * the member.
	 * @param string $whatInfo "all" to bring the entire list of objects,
	 * "names" to bring only the names.
	 * @return Array of JResearchResearcharea objects or stdobjects containing ids and names
	 */
	function getResearchAreas($whatInfo = 'all'){
		$db = JFactory::getDBO();
		
		if($whatInfo == 'all'){
			if(!isset($this->_areas)){
				$this->_areas = array();
				$db->setQuery('SELECT * FROM #__jresearch_research_area WHERE id IN ('.$this->id_research_area.')');				
				$areas = $db->loadAssocList();		
				foreach($areas as $row){
					$area = JTable::getInstance('Researcharea', 'JResearch');
					$area->bind($row);
					$this->_areas[] = $area;
				}
			}			
			
			return $this->_areas;			
		}elseif($whatInfo == 'names'){
			$db->setQuery('SELECT id, name FROM #__jresearch_research_area WHERE id IN ('.$this->id_research_area.')');				
			return $db->loadObjectList();
		}else{
			return null;
		}		
	}        
	
}

?>