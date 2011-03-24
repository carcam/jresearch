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
		JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'html');
		
		return JHTML::_('jresearchhtml.researchareas', array(
				'name'=>$control_name.'['.$name.']',
				'selected' => $value
			), 
			array(
				array(
					'id' => 0, 
					'name' => JText::_('All')
				)
			)
		);
	}
}
?>