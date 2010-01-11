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
	function fetchElement($name, $value, &$node, $control_name)
	{
	    global $mainframe;
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
		
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($url."components/com_jresearch/helpers/html/projectselector.js");
		
		//Generate element
		$html = '<input type="hidden" name="'.$fieldName.'" id="'.$name.'" value="'.$value.'" /> ';
		$html .= JHTML::_('select.genericlist', $projectsOptions, 'projectslist', 'multiple="multiple" class="inputbox" size="5" onchange="changeValue(\''.$name.'\');"', 'value', 'text', explode(',',$value));
		$html .= '&nbsp;&nbsp;';
		$html .= '<input type="button" name="selectbtn" id="selectbtn" value="'.JText::_('JRESEARCH_PROJECT_PARAM_SELECTION_SELECT_ALL').'" onclick="selectAll(\''.$name.'\');" style="vertical-align: top;" />';
		$html .= '&nbsp;';
		$html .= '<input type="button" name="resetbtn" id="resetbtn" value="'.JText::_('JRESEARCH_PROJECT_PARAM_SELECTION_RESET').'" onclick="unselectAll(\''.$name.'\');" title="'.JText::_('JRESEARCH_PROJECT_PARAM_SELECTION_TOOLTIP').'" style="vertical-align: top;" />';
		
		return $html;
	}
}
?>