<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Teams
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');

/**
* Model class for holding a single team record.
*
*/
class JResearchModelTeam extends JResearchModelSingleRecord
{
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$team = JTable::getInstance('Team', 'JResearch');
		$result = $team->load($itemId);
		
		return ($result) ? $team : null;
	}
	
	public function getMembers($itemId)
	{
		$item = $this->getItem($itemId);
		$members = $item->getMembers();
		
		$db =& JFactory::getDBO();
		$memberObjects = array(); 
		
		foreach($members as $member)
		{
			$memberObject = JTable::getInstance('Member', 'JResearch');
			$memberObject->load($member['id_member']);
			$memberObjects[] = $memberObject;
		}
		
		return $memberObjects;
	}
	
	public function getNumberTheses($teamId){
		$db = JFactory::getDBO();
		
		$id_staff_member = $db->nameQuote('id_staff_member');
		$team_member = $db->nameQuote('#__jresearch_team_member');
		$id_thesis = $db->nameQuote('id_thesis');
		$internal_author = $db->nameQuote('#__jresearch_thesis_internal_author');
		$teamValue = $db->Quote($teamId);
		$id_team = $db->nameQuote('id_team');
		$id_member = $db->nameQuote('id_member');
		$team_table = $db->nameQuote('#__jresearch_team');
		$thes_table = $db->nameQuote('#__jresearch_thesis');
		
		$query = "SELECT COUNT(*) FROM (SELECT DISTINCT $id_thesis FROM $internal_author, $team_member, $thes_table WHERE $team_member.$id_team = $teamValue "
				 ." AND $internal_author.$id_staff_member = $team_member.$id_member AND $thes_table.id = $internal_author.$id_thesis AND $thes_table.published = 1"
				 ." UNION (SELECT DISTINCT $id_thesis FROM $internal_author pia, $team_table t, $thes_table th WHERE t.id = $teamValue AND "
		         	 ."pia.$id_staff_member = t.id_leader AND th.id = pia.$id_thesis AND th.published = 1)) as R1";
		
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	public function getNumberProjects($teamId){
		$db = JFactory::getDBO();
		
		$id_staff_member = $db->nameQuote('id_staff_member');
		$team_member = $db->nameQuote('#__jresearch_team_member');
		$id_project = $db->nameQuote('id_project');
		$proj_internal_author = $db->nameQuote('#__jresearch_project_internal_author');
		$teamValue = $db->Quote($teamId);
		$id_team = $db->nameQuote('id_team');
		$id_member = $db->nameQuote('id_member');
		$team_table = $db->nameQuote('#__jresearch_team');

		$query = "SELECT COUNT(*) FROM (SELECT DISTINCT $id_project FROM $proj_internal_author pia, $team_member tm WHERE tm.$id_team = $teamValue"
				 ." AND pia.$id_staff_member = tm.$id_member"
				 ." UNION (SELECT DISTINCT $id_project FROM $proj_internal_author pia, $team_table t WHERE t.id = $teamValue AND "
		         	 ."pia.$id_staff_member = t.id_leader)) as R1";

		$db->setQuery($query);
		return $db->loadResult();
	
	}

	public function getNumberPublications($teamId){
		$db = JFactory::getDBO();
		
		$id_staff_member = $db->nameQuote('id_staff_member');
		$team_member = $db->nameQuote('#__jresearch_team_member');
		$id_publication = $db->nameQuote('id_publication');
		$pub_internal_author = $db->nameQuote('#__jresearch_publication_internal_author');
		$teamValue = $db->Quote($teamId);
		$id_team = $db->nameQuote('id_team');
		$id_member = $db->nameQuote('id_member');
		$team_table = $db->nameQuote('#__jresearch_team');
		$pub_table = $db->nameQuote('#__jresearch_publication');
		
		$query = "SELECT COUNT(*) FROM (SELECT DISTINCT $id_publication FROM $pub_internal_author, $team_member, $pub_table WHERE $team_member.$id_team = $teamValue "
			." AND $pub_internal_author.$id_staff_member = $team_member.$id_member AND $pub_table.id = $pub_internal_author.$id_publication AND $pub_table.internal = 1 AND $pub_table.published"				 ." UNION (SELECT DISTINCT $id_publication FROM $pub_internal_author pia, $team_table t, $pub_table p WHERE t.id = $teamValue AND "
		        ."pia.$id_staff_member = t.id_leader AND p.id = pia.$id_publication AND p.published = 1 AND p.internal = 1)) AS R1";
	
		$db->setQuery($query);
		return $db->loadResult();
	
	}	

}
?>