<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Teams
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');

/**
 * This class represents a JResearch team in database.
 *
 */
class JResearchTeam extends JTable
{
	public $id;
	public $id_leader;
	public $name;
	public $description;
	public $published;
	public $checked_out;
  	public $checked_out_time;
	
	protected $_members = array();
	
	public function __construct(&$db)
	{
		parent::__construct('#__jresearch_team', 'id', $db);
	}
	
	/**
	 * Loads a J!Research team from the database with the given $oid
	 *
	 * @param int $oid
	 * @return JResearchTeam
	 */
	public function load($oid=null)
	{
		$result = parent::load($oid);
		
		if($oid != null)
			$this->_loadMembers($oid);
		
		return $result;
	}
	
	public function store($updateNulls = null)
	{
		if(!parent::store($updateNulls))
			return false;
			
		$db = &$this->getDBO();
		$j = $this->_tbl_key;
		$tableName = $db->nameQuote('#__jresearch_team_member');
		
		// Delete the information about internal and external references
		$deleteQuery = 'DELETE FROM '.$tableName.' WHERE '.$db->nameQuote('id_team').' = '.$db->Quote($this->$j);
		$db->setQuery($deleteQuery);
		if(!$db->query())
		{
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		
		$idTeamField = $db->nameQuote('id_team');
       	$idMemberField = $db->nameQuote('id_member');
       	
		foreach($this->_members as $member)
		{			
			$id_staff_member = $member['id_member'];
			$tableName = $db->nameQuote('#__jresearch_team_member');
			
			$insertInternalQuery = "INSERT INTO $tableName($idTeamField,$idMemberField) VALUES ($this->id, $id_staff_member)";
			$db->setQuery($insertInternalQuery);	
					
			if(!$db->query())
			{
				$this->setError(get_class( $this ).'::store failed - '.$db->getQuery());
				return false;
			}
		}
		
		return true;
	}

	public function delete($oid = null)
	{
		$this->_deleteMembers($oid);
		parent::delete($oid);
	}
	
	/**
	 * Gets all members, an array of member objects
	 *
	 * @return array
	 */
	public function getMembers()
	{
		$db =& JFactory::getDBO();
		$memberObjects = array(); 
		
		foreach($this->_members as $member)
		{
			$memberObject = new JResearchMember($db);
			$memberObject->load($member['id_member']);
			$memberObjects[] = $memberObject;
		}
		
		return $memberObjects;
	}
	
	/**
	 * Gets leader from the team as a member object
	 *
	 * @param DBO $db
	 * @return JResearchMember
	 */
	public function getLeader()
	{
		$db =& JFactory::getDBO();
		$leader = new JResearchMember($db);
		
		if($this->id_leader > 0)
			$leader->load($this->id_leader);
		
		return $leader;
	}

	/**
	 * Sets a member for the team
	 *
	 * @param int $member
	 * @return bool
	 */
	public function setMember($id_member)
	{
		if($id_member > 0)
			$this->_members[] = array('id' => $this->id, 'id_member' => $id_member);
		
		return true;
	}
	
	/**
	 * Returns the number of added members to the team
	 *
	 * @return int
	 */
	public function countMembers()
	{
		return count($this->_members);
	}
	
	/**
	 * Returns true if the given user id is a member of the team otherwise false
	 *
	 * @param int $userid
	 * @return bool
	 */
	public function isMember($userid)
	{
		$id = intval($userid);
		$db =& JFactory::getDBO();
		
		$user = JFactory::getUser($id);
		$umember = new JResearchMember($db);
		
		if($user->username)
		{
			$umember->bindFromUser($user->username);
		
			foreach($this->_members as $member)
			{
				if(($member['id'] == $umember->id) || $this->isLeader($id))
					return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Returns true if the given user id is leader of the team
	 *
	 * @param int $id
	 * @return bool
	 */
	public function isLeader($userid)
	{
		$id = intval($userid);
		$db =& JFactory::getDBO();
		
		$user = JFactory::getUser($id);
		$umember = new JResearchMember($db);
		
		if($user->username)
		{
			$umember->bindFromUser($user->username);
			
			if($this->id_leader == $umember->id)
			{
				return true;
			}
		}
		
		return false;
	}
	
	private function _deleteMembers($oid = null)
	{
		$db = &$this->getDBO();
		$j = $this->_tbl_key;
		$oid = ($oid == null) ? $this->$j : $oid;
		$tableName = $db->nameQuote('#__jresearch_team_member');
		
		// Delete the information about internal and external references
		$deleteQuery = 'DELETE FROM '.$tableName.' WHERE '.$db->nameQuote('id_team').' = '.$db->Quote($oid);
		$db->setQuery($deleteQuery);
		if(!$db->query())
		{
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		
		$this->_members = array();
		
		return true;
	}
	
	private function _loadMembers($oid)
	{
		$db =& JFactory::getDBO();
		$table = $db->nameQuote('#__jresearch_team_member');
		$idTeam = $db->nameQuote('id_team');
		
		$qoid = $db->Quote($oid);
		
		// Get internal authors
        $membersQuery = "SELECT * FROM $table WHERE $idTeam = $qoid";
		$db->setQuery($membersQuery);
        
		if(($result = $db->loadAssocList()))
        {
        	$this->_members = $result;
        }
        else
        {
        	$this->_members = array();	
        }
	}
}
?>