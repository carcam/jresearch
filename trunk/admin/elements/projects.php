<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Florian Prinz
* @license		GNU/GPL
* This file implements the projects element.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class JElementProjects extends JElement
{
	/**
	 * Element name
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Projects';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db =& JFactory::getDBO();
		$sql = "SELECT id, title FROM #__jresearch_project WHERE published=1";
		
		$db->setQuery($sql);
		$projects = $db->loadObjectList();
		
		$projectsOptions = array();
		foreach($projects as $project)
		{
			$projectsOptions[] = JHTML::_('select.option', $project->id, $project->title);
		}
		
		$fieldName = $control_name.'['.$name.']';
		$doc = JFactory::getDocument();
		
		//Javascript
		$script = 'function changeValue () {';
		
		$script .= 'var selBox = document.getElementById(\'projectslist\');';
		$script .= 'var txtBox = document.getElementById(\''.$name.'\');';
		$script .= 'var value = selBox.options[selBox.selectedIndex].value;';
		
		$script .= 'var valueIn = false;';
		$script .= 'var values = txtBox.value.split(\',\');';
		
		$script .= 'for(var i=0;i<values.length;i++) { if(values[i] == value) { valueIn = true; break; } }';
		
		$script .= 'if(!valueIn){';
		
		$script .= 'if(txtBox.value == "0" || txtBox.value == "") { txtBox.value = value; }';
		$script .= 'else { txtBox.value += \',\' + value; }';
		
		$script .= '}}';
		
		//Add script
		$doc->addScriptDeclaration($script);
		
		//Generate element
		$html = '<input type="text" name="'.$fieldName.'" id="'.$name.'" readonly="readonly" value="'.$value.'" maxlength="65535" /> ';
		$html .= '<input type="button" name="resetbtn" id="resetbtn" value="Reset" onclick="document.getElementById(\''.$name.'\').value=\'0\';" /><br />';
		$html .= JHTML::_('select.genericlist', $projectsOptions, 'projectslist', 'class="inputbox" size="5" onchange="changeValue();"', 'value', 'text');
		
		return $html;
	}
}
?>