<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	HTML
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');

/**
 * JHTML helper class that implements a control for importing staff
 * members from users table.
 *
 */
class JHTMLStaffImporter{

	/**
	* Renders a HTML control for importing staff members from users table.
	* @param $name HTML name of the control which holds the selected users.
	*/
	public function _($name){
		$db =& JFactory::getDBO();
		$fields = $db->nameQuote('username').', '.$db->nameQuote('lastname').', '.$db->nameQuote('firstname');
		$db->setQuery('SELECT '.$fields.' FROM '.$db->nameQuote('#__jresearch_member'));

		$members = $db->loadAssocList();
		
		$usernames = array();
		foreach($members as $m)
			$usernames[] = $m['username'];
	
		$query = 'SELECT * FROM '.$db->nameQuote('#__users').' WHERE '.$db->nameQuote('block').' = '.$db->Quote('0');
		

		$db->setQuery($query);
		$users = $db->loadAssocList();			
		$joomlaUsers = array();
		
		foreach($users as $u){
			if(!in_array($u['username'], $usernames))
				$joomlaUsers[] = $u;
		}
		

		
		$output = '<table class="staffimporter"><thead><tr><th>'.JText::_('JRESEARCH_MEMBERS_NOT_IN_STAFF').'</th><th></th><th>'.JText::_('JRESEARCH_NEW_STAFF_MEMBERS').'</th></tr></thead><tbody><tr><td>';		
		$output .= '<select name="users" id="users" size="15" class="inputbox staffimporter">';

		foreach($joomlaUsers as $user){
			$value = $user['username'];
			$nameComponents = JResearchPublicationsHelper::getAuthorComponents($user['name']);
			$lastname = $nameComponents['lastname'];
			$firstname = isset($nameComponents['firstname'])?$nameComponents['firstname']:'';
			$output .= "<option id=\"$value\" value=\"$value\">$lastname, $firstname</option>";
		}
		
		$output .= '</select></td>';
		$output .= '<td align="center"><a style="font-size:14px;font-weight:bold;" href="javascript:addHiddenField(document.adminForm.users.options[document.adminForm.users.selectedIndex].value);moveFrom(\'users\', \''.$name.'\');">&gt;&gt;</><br />';
		$output .= '<a style="font-size:14px;font-weight:bold;" href="javascript:removeHiddenField(document.adminForm.'.$name.'.options[document.adminForm.'.$name.'.selectedIndex].value);moveFrom(\''.$name.'\', \'users\');">&lt;&lt;</a><br />';
		$output .= '<a style="font-size:14px;font-weight:bold;" href="javascript:moveAllFrom(\'users\', \''.$name.'\', true);">'.JText::_('All').'>>'.'</a></td>';
		$output .= '<td><select size="15" class="inputbox staffimporter" name="'.$name.'" id="'.$name.'"></select></td>';
		$output .= '</tr></tbody></table>';		
		$output .= '<input type="hidden" name="staffCount" id="staffCount" value="0" />';
		
		return $output;
		
	}
}

?>