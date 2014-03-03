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

require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'models'.'/'.'modelSingleRecord.php');
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'tables'.'/'.'team.php');

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
		$db =& JFactory::getDBO();
		
		$team = new JResearchTeam($db);
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
			$memberObject = new JResearchMember($db);
			$memberObject->load($member['id_member']);
			$memberObjects[] = $memberObject;
		}
		
		return $memberObjects;
	}
}
?>