<?php
/**
* @version		$Id: author.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @subpackage	JResearch
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Renders a projects element
 *
 * @package 	Joomla
 * @subpackage	JResearch
 * @since		1.0
 */
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
		
		$projectsOptions[0] = JHTML::_('select.option', 0, 'all');
		
		foreach($projects as $project)
		{
			$projectsOptions[] = JHTML::_('select.option', $project->id, $project->title);
		}
		
		return JHTML::_('select.genericlist', $projectsOptions, $name, 'class="inputbox" size="10" multiple="multiple"', 'value', 'text', $value);
	}
}
?>