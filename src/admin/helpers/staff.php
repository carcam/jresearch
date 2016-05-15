<?php
/**
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jresearchimport('tables.member', 'jresearch.admin');

/**
 * 
 * Utility class for staff-related routines
 * @author lgalarra
 *
 */
class JResearchStaffHelper{
    
    public static function getMemberName($memberId) {
        $db = JFactory::getDBO();
        $member = null;
        $cparams = JComponentHelper::getParams('com_jresearch');

        $query = 'SELECT firstname, lastname FROM '.$db->quoteName('#__jresearch_member').' WHERE '.$db->quoteName('id').' = '.$db->Quote(1);
        $db->setQuery($query);
        $result = $db->loadAssoc();
        if ($result != null) {
            if ($cparams->get('staff_format', 'last_first') == 'last_first') {
                return $result['lastname'].', '.$result['firstname'];
            } else {
                return $result['firstname'].' '.$result['lastname'];                
            }
        }

        return $result;        
    }
	
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
    
    /**
     * Returns all the members whose names or lastnames start with the provided 
     * prefix. If $prefix is null, then the function returns all published members
     * from the DB.
     * 
     * @param type $prefix
     */
    public static function getMembers($prefix = null) {
        $db = JFactory::getDBO();
        $query = 'SELECT * FROM '.$db->quoteName('#__jresearch_member')
                .' WHERE '.$db->quoteName('published').' = '.$db->Quote('1');
        if ($prefix != null) {
            $query .= ' AND ('.$db->quoteName('firstname').' LIKE '
                    .$db->Quote( $prefix.'%', false );
            $query .= ' OR '.$db->quoteName('lastname').' LIKE '
                    .$db->Quote( $prefix.'%', false ).')';
        }
        
        $db->setQuery($query);
        $members = $db->loadAssocList();
        return $members;
    }
    
    /**
     * Formats members information into JSON.
     * @param array $members Array of associative arrays with basic information
     * about the members.
     */
    public static function members2JSON($members) {
        $arr = array();
        $cparams = JComponentHelper::getParams('com_jresearch');
        foreach($members as $member){
            if ($cparams->get('staff_format', 'last_first') == 'last_first') {
                $name = $member['lastname'].', '.$member['firstname'];
            } else {
                $name = $member['firstname'].', '.$member['lastname'];                
            }
            $arr[] = "{\"value\": \"".$member['id'].'|'.$name."\", \"label\": \"".$name."\"}";
        }
        $output = "[".implode(", ", $arr)."]";
        return $output;
    }
}
?>