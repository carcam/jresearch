<?php
/**
* @version		$Id: helper.php 10214 2008-04-19 08:59:04Z eddieajau $
* @package		JResearch-Modules
* @subpackage 	JResearch
* @copyright	Copyright (C) 2008 Florian Prinz. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class modJResearchMembersHelper
{
	function getMembers($params)
	{
		$members = array();
		$db =& JFactory::getDBO();
		
		$former = (int) $params->get('former_members');
		$order = $params->get('order');
		$order_dir = strtoupper($params->get('order_dir'));
		
		$query = 'SELECT id, firstname, lastname FROM #__jresearch_member WHERE published=1';
		
		if($former > 0)
		{
			$query .= ' AND former_member=1';
		}
		elseif($former < 0)
		{
			$query .= ' AND former_member=0';
		}
				
		$query .= ' ORDER by '.$order.' '.$order_dir;
		
		$db->setQuery($query);
		
		$members = $db->loadObjectList();

		return $members;
	}
}