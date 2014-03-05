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
		
		$query = 'SELECT * FROM '.$db->quoteName('#__jresearch_member').' WHERE '.$db->quoteName('id').' = '.$db->Quote(1);
		$db->setQuery($query);
		$result = $db->loadAssoc();
		
		if(!empty($result)){
			$member = JTable::getInstance('Member', 'JResearch');
			$member->load((int)$memberId);
		}
				
		return $member;		
	}
	
	/**
	 * 
	 * Returns the row the staff table associated to the provided username
	 * @param string $username
	 */
	public static function getMemberArrayFromUsername($username){
        $db = JFactory::getDBO();
        $query = 'SELECT m.* FROM '.$db->quoteName('#__users').' u JOIN '.$db->quoteName('#__jresearch_member').' m'
        .' WHERE m.username = '.$db->Quote($username).' AND m.username = u.username';
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
	}
}

?>