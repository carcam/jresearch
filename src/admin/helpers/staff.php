<?php
/**
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

jresearchimport('tables.member', 'jresearch.admin');

/**
 * 
 * Utility class for staff-related routines
 * @author lgalarra
 *
 */
class JResearchStaffHelper{
	
	public static function getMember($memberId){
		$db = JFactory::getDBO();
		$member = null;
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('id').' = '.$db->Quote(1);
		$db->setQuery($query);
		$result = $db->loadAssoc();
		
		if(!empty($result)){
			$member = JTable::getInstance('Member', 'JResearch');
			$member->load((int)$memberId);
		}
				
		return $member;		
	}
}

?>