<?php
/**
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Utility methods for teams
 * @author lgalarra
 *
 */
class JResearchTeamsHelper{
	
	/**
	 * 
	 * Returns an array with all published teams
	 */
	public static function getTeams(){
		$db = JFactory::getDbo();
		$teams = array();
		jresearchimport('tables.team', 'jresearch.admin');
		
		$db->setQuery('SELECT * FROM '.$db->quoteName('#__jresearch_team').' WHERE published = '.$db->Quote(1));
		$result = $db->loadAssocList();
		foreach($result as $row){
			$team = JTable::getInstance('Team', 'JResearch');
			$team->bind($row);
			$teams[] = $team;			
		}
		
		return $teams;
	}

}