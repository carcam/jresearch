<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright	Copyright (C) 2008 Florian Prinz
* @license		GNU/GPL
* This file implements the research areaas element.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Renders a researcharea element
 *
 * @package 	JResearch
 * @subpackage	ResearchAreas
 */
class JElementResearchareas extends JElement
{
	/**
	 * Element name
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Researchareas';

	function fetchElement($name, $value, &$node, $control_name)
	{
		/*
		$db =& JFactory::getDBO();
		$sql = "SELECT id, name FROM #__jresearch_research_area WHERE published=1";
		
		$db->setQuery($sql);
		$areas = $db->loadObjectList();
		
		$areasOptions = array();
		$areasOptions[0] = JHTML::_('select.option', 0, 'all');
		
		foreach($areas as $area)
		{
			$areasOptions[] = JHTML::_('select.option', $area->id, $area->name);
		}

		return JHTML::_('select.genericlist', $areasOptions, $control_name.'['.$name.']', 'class="inputbox" size="10"', 'value', 'text', $value);
		*/
		JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		
		return JHTML::_('jresearch.researchreas', array('name'=>$control_name.'['.$name.']', 'selected' => $value), array('id' => 0, 'name' => JText::_('All')));
	}
}
?>