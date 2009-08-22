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
	    JHTML::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
	    
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
		$doc->addScript($url."components/com_jresearch/js/projectselector.js");
		
		//Generate element
		$html = JHTML::_('jresearchhtml.input', $fieldName, $value, 'hidden', array('id' => $name));
		
		if(count($projectsOptions) > 0)
		{
			$html .= JHTML::_('select.genericlist', $projectsOptions, 'projectslist', 'multiple="multiple" class="inputbox" size="5" onchange="changeValue(\''.$name.'\');"', 'value', 'text', explode(',',$value));
			$html .= '&nbsp;&nbsp;';
			$html .= JHTML::_(
				'jresearchhtml.input',
				'selectbtn',
				JText::_('JRESEARCH_PROJECT_PARAM_SELECTION_SELECT_ALL'),
				'button',
			    array(
					'id' => 'selectbtn',
					'onclick' => 'selectAll(\''.$name.'\');',
					'style' => 'vertical-align: top;'
			    )
	        );
			$html .= '&nbsp;';
			$html .= JHTML::_(
				'jresearchhtml.input',
				'resetbtn',
				JText::_('JRESEARCH_PROJECT_PARAM_SELECTION_RESET'),
				'button',
			    array(
			    	'id' => 'resetbtn',
			    	'onclick' => 'unselectAll(\''.$name.'\');',
			    	'style' => 'vertical-align: top;',
			    	'title' => JText::_('JRESEARCH_PROJECT_PARAM_SELECTION_TOOLTIP')
			    )
		    );
		}
		else 
		{
			$html .= JText::_('JRESEARCH_NO_PROJECTS');
		}
		
		return $html;
	}
}
?>