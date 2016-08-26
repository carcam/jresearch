<?php
/**
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Functionalities associated to member positions 
*
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

class JResearchMemberpositionsHelper{

	/**
	 * Returns a list of all published member positions
	 * @return array Array of member position table objects
	 */
	public static function getMemberPositions(){
		$db = JFactory::getDBO();
		jresearchimport('tables.member_position');
		
		$db->setQuery('SELECT * FROM '.
				$db->quoteName('#__jresearch_member_position').
				' WHERE published = '.$db->Quote(1).
				' ORDER BY ordering ASC');
		$result = $db->loadAssocList();
		$positions = array();
		
		foreach($result as $row){
			$memberPosition = JTable::getInstance('Member_position', 'JResearch');
			$memberPosition->bind($row);
			$positions[] = $row;
		}
		
		return $positions;
	}
	
}