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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');

/**
 * JHTML helper class that implements a control which allow to select
 * authors from staff members.
 *
 */
class JHTMLAuthorsSelector{
	
	
	/**
	* Renders the control. It allows to select authors from staff members. For external
	* authors it is possible to use simple text fields.
	*
	* @param string $baseName Base name of the control. It represents the name of the fields that
	* will be sent when submitting the form. For internal authors it will be a list. For external 
	* ones, it will be a text input. Only letters and _ are allowed.
	* @param array Mixed sorted array. JResearchMember instances will be considered as internal 
	* staff member's while strings are considered as external authors names.
	* @param boolean $allowPrincipals If true, a checkbox will be displayed for each entry to define
	* if the member is a principle investigator in the activity.
	* @param boolean $isPrincipal Array with the flags indicating if every author stored in $values
	* is a principal author.
	*/
	static function _($baseName, $values = null, $allowPrincipals=false, $isPrincipalsArray=null){
		global $mainframe;
		$doc =& JFactory::getDocument();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($url."components/com_jresearch/helpers/html/authorsselector.js");
		
		$result = self::_internalAuthor(null, 'sample', true, $allowPrincipals, false);
		$result .= '<div id="'.$baseName.'" >';
		$j = 0;
		
		if(empty($values)){
			$result .= self::_externalAuthor('', $baseName.$j, false, $allowPrincipals, false);
			$j++;
		}else{
			foreach($values as $value){
				if($value instanceof JResearchMember){
					// Staff id
					if($j > 0)
						$result .= self::_internalAuthor($value, $baseName.$j, true, $allowPrincipals, $isPrincipalsArray[$j]);	
					else
						$result .= self::_internalAuthor($value, $baseName.$j, false, $allowPrincipals, $isPrincipalsArray[$j]);	
				}elseif(is_string($value)){
					// Author name
					if($j > 0)
						$result .= self::_externalAuthor($value, $baseName.$j, true, $allowPrincipals, $isPrincipalsArray[$j]);
					else
						$result .= self::_externalAuthor($value, $baseName.$j, false, $allowPrincipals, $isPrincipalsArray[$j]);	
				}
				$result .= '<br />';
				$j++;
			}
		}
		$result .= self::_fetchHiddenField($j - 1, "max".$baseName);
		$result .= '</div>';
		$result .= '<div>'.self::_fetchAddControl($baseName.($j-1), "max".$baseName, $baseName, $allowPrincipals).'</div>';
		return $result;
		
	}
	
	/**
	* Renders the control used for entering external authors. It includes a text field
	* and two links: one for adding a new author and the other to delete the current one.
	*
	* @param $authorName Value of the text field
	* @param $controlName Name of the control (used for name and id HTML attributes)
	* @param $deleteLink If true, the link for deleting the current author is rendered.
	* @param boolean $allowPrincipals If true, a checkbox will be displayed for each entry to define
	* if the member is a principle investigator in the activity.
	* @param boolean $isPrincipal If true, the author is marked as principal investigator in the activity
	* is assigned to. This parameter makes sense if $allowPrincipals = true. 
	* @return string 
	*/
	private static function _externalAuthor($authorName, $controlName, $deleteLink=true, $allowPrincipals, $isPrincipal){
		// Value is a mixed array	
		$select = JText::_('JRESEARCH_SELECT_FROM_STAFF');
		$enterName = JText::_('JRESEARCH_ENTER_NAME');
		$authorText = JText::_('JRESEARCH_ADD_AUTHOR');
		$deleteText = JText::_('Delete');
		$js = "javascript:switchType('$controlName', '$select', '$enterName')";
		$class = "";
		$result  = "<div id=\"div_$controlName\"><input type=\"text\" class=\"$class\" name=\"$controlName\" id=\"$controlName\" value=\"$authorName\" size=\"15\" maxlength=\"255\" />";
		$result .= "&nbsp;";
		$result .= "<a href=\"$js\" id=\"a_$controlName\">$select</a>";
		
		$result .= "<br />";
		if($deleteLink)	
			$result .= self::_fetchDeleteControl($controlName);
			
		if($allowPrincipals){
			$checked = $isPrincipal?'checked="checked"':'';
			$principalMember = JText::_('JRESEARCH_PROJECT_LEADER');
			$principalCheck = "&nbsp;&nbsp;$principalMember: <input type=\"checkbox\" name=\"check_$controlName\" id=\"check_$controlName\" $checked >";		
			$result .= $principalCheck;
		}
					
		$result .= '</div>';
		return $result;

	}
	
	/**
	* Renders the control used for selecting internal authors. It includes a list
	* with published staff members.
	*
	* @param $author JResearchMember instance
	* @param $controlName HTML name of the rendered control.
	* @param $addLink If true, the link for adding new authors is rendered.
	* @param $deleteLink If true, the link for deleting the current author is rendered.
	* @param boolean $allowPrincipals If true, a checkbox will be displayed for each entry to define
	* if the member is a principle investigator in the activity. 
	* @param boolean $isPrincipal If true, the author is marked as principal investigator in the activity
	* is assigned to. This parameter makes sense if $allowPrincipals = true.
	* @return string HTML text of the control.
	*/
	private static function _internalAuthor($author, $controlName, $deleteLink=true, $allowPrincipals, $isPrincipal){
		$db =& JFactory::getDBO();
		$enterName = JText::_('JRESEARCH_ENTER_NAME');
		$select = JText::_('JRESEARCH_SELECT_FROM_STAFF');
		$js = "javascript:switchType('$controlName', '$select', '$enterName')";

		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('published').'='.$db->Quote('1').' ORDER BY lastname';
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$options = array();
		
		foreach($result as $r){
			$options[] = JHTML::_('select.option', $r['id'], $r['lastname'].' '.$r['firstname']);
		}

		$list = JHTML::_('select.genericlist', $options, $controlName, 'class="inputbox"', 'value', 'text', $author?$author->id:null);
		if($deleteLink)	
			$deleteControl = self::_fetchDeleteControl($controlName);
		else
			$deleteControl = '';	

		if($allowPrincipals){	
			$principalMember = JText::_('JRESEARCH_PROJECT_LEADER');			
			$checked = $isPrincipal?'checked="checked"':'';
			$principalCheck = "$principalMember: <input type=\"checkbox\" name=\"check_$controlName\" id=\"check_$controlName\" $checked >";	
		}else{
			$principalCheck = '';
		}
		
		$a = "<a href=\"$js\" id=\"a_$controlName\" >$enterName</a>";

		return "<div id=\"div_$controlName\">$list&nbsp;&nbsp;$a<br />$deleteControl&nbsp;&nbsp;$principalCheck</div>";
	}
	
	/**
	* Renders a link to add an additional input for a new author.
	* @param $controlName HTML name tag of the control the link is next to.
	* @return string HTML text
	*/
	private static function _fetchAddControl($controlName, $maxName, $basename, $allowPrincipals){
		$enterName = JText::_('JRESEARCH_ENTER_NAME');
		$text = JText::_('Add');			
		$deleteText = JText::_('Delete');
		$select = JText::_('JRESEARCH_SELECT_FROM_STAFF');
		$principalMember = JText::_('JRESEARCH_PROJECT_LEADER');
		$result =  '<a id="add'.$basename.'" href="javascript:addControl(\''.$controlName.'\', \''.$text.'\', \''.$deleteText.'\', \''.$select.'\', \''.$enterName.'\', \''.$maxName.'\', \''.$allowPrincipals.'\', \''.$principalMember.'\')">'.$text.'</a>';
		return $result;
	}
	
	/**
	* Renders a link to delete the current author.
	* @param $controlName HTML name tag of the control the link is next to.
	* @return string HTML text
	*/
	private static function _fetchDeleteControl($controlName){
		$text = JText::_('Delete');
		$result =  '<a href="javascript:deleteControl(\''.$controlName.'\')">'.$text.'</a>';
		return $result;
	}
	
	/**
	* Renders the hidden field that stores the greater index used for control.
	* @param int $value
	*/
	private static function _fetchHiddenField($value, $name){
		return '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'" />';
	}
	
	/**
	 * Control to add recava groups
	 * @param $name
	 * @param $value
	 * @param $show
	 * @return string
	 */
	public function recavagroups($name, $value, $visible){
		global $mainframe;
		$doc =& JFactory::getDocument();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($url."components/com_jresearch/helpers/html/authorsselector.js");
		$delete = JText::_('Delete');		
		$doc->addScriptDeclaration("var deleteMessage = '$delete'");
		
		$output = '<div id="div_'.$name.'" style="padding:0px;display:'.($visible?'block':'none').'">';
		$group = JText::_('JRESEARCH_GROUP');
		$author = JText::_('JRESEARCH_AUTHOR');
		$output .= "<table style=\"vertical-align:top;width:80%;text-align:center;\"><thead><tr><th style=\"width:30%;\">$group</th><th style=\"width:60%;\">$author</th><th></th></tr></thead><tbody id=\"body_$name\">";
		
		$rows = explode(';', $value);
		$j = 0;
		foreach($rows as $row){
			$output .= '<tr id="tr_'.$name.$j.'">';
			$cols = explode(',', $row);
			$output .= '<td><input name="group_'.$name.$j.'" value="'.$cols[0].'" type="text" size="10" /></td>';
			$output .= '<td><input name="author_'.$name.$j.'" value="'.$cols[1].'" type="text" size="10" /></td>';			
			$output .= '<td><a href="javascript:removeRow(\'tr_'.$name.$j.'\', \''.$name.'\')" >'.$delete.'</a></td>';					
			$output .= '</tr>';				
			$j++;
		}
		
		$add = JText::_('Add');
		$output .= '</tbody></table>';
		$output .= '<div style="width:80%;text-align:center;"><a href="javascript:addGroupRow(\'body_'.$name.'\', \''.$name.'\');">'.$add.'</a><input type="hidden" value="'.$j.'" id="count_'.$name.'" name="count_'.$name.'" /></div>';
		$output .= '</div>';		
				
		return $output;
	}
	
	/**
	 * 
	 * @param $name
	 * @param $value
	 * @param $visible
	 * @return unknown_type
	 */
	public static function recavaplatforms($name, $value, $visible){
		global $mainframe;
		$doc =& JFactory::getDocument();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($url."components/com_jresearch/helpers/html/authorsselector.js");
		$delete = JText::_('Delete');		
		$doc->addScriptDeclaration("var deleteMessage = '$delete'");
		
		$output = '<div id="div_'.$name.'" style="display:'.($visible?'block':'none').'">';
		$platform = JText::_('JRESEARCH_PLATFORM_NAME');
		$output .= "<table style=\"vertical-align:top;width:80%;text-align:center;\"><thead><tr><th style=\"width:90%;\">$platform</th><th></th></tr></thead><tbody id=\"body_$name\">";
		
		$rows = explode(';', $value);
		$j = 0;
		foreach($rows as $row){
			$output .= '<td><input name="platform_'.$name.$j.'" value="'.$row.'" type="text" size="18" /></td>';			
			$output .= '<td><a href="javascript:removeRow(\'tr_'.$name.$j.'\', \''.$name.'\')" >'.$delete.'</a></td>';					
			$output .= '</tr>';				
			$j++;
		}
		
		$add = JText::_('Add');
		$output .= '</tbody></table>';
		$output .= '<div style="width:80%;text-align:center;"><a href="javascript:addPlatformRow(\'body_'.$name.'\', \''.$name.'\');">'.$add.'</a><input type="hidden" value="'.$j.'" id="count_'.$name.'" name="count_'.$name.'" /></div>';
		$output .= '</div>';		
		
		return $output;
		
	}
	
	/**
	 * 
	 * @param $name
	 * @param $value
	 * @return unknown_type
	 */
	public static function recavaotherlines($name, $value){
		$lines = explode(";", $value);
		$output = "<div><ul>";		
		foreach($lines as $line){
			$lineValues = explode("=", $line);
			$lineValues[0] = trim($lineValues[0]);
			$lineValues[1] = trim($lineValues[1]);			

			$output .= '<li>';
			$output .= '<input type="checkbox" name="'.$name.'_'.$lineValues[0].'" value="'.$lineValues[1].'" />';			
			$output .= '<label for="'.$name.'_'.$lineValues[0].'" />'.JText::_('JRESEARCH_'.strtoupper($lineValues[0]));			
			$output .= '</li>';			
		}
		
		$output .= "</ul></div>";
		return $output;
	}
	
	/**
	 * 
	 * @param $memberId
	 * @return JResearchMember
	 */
	private static function _getMember($memberId){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
		$member = new JResearchMember(JFactory::getDBO());
		$member->load($memberId);
		return $member;
	}
	
	
}

?>