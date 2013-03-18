<?php
/**
* @version		$Id: helper.php 10214 2008-04-19 08:59:04Z eddieajau $
* @package		JResearch-Modules
* @subpackage 	Facilities
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

class modJResearchFacilitiesHelper
{
	function getFacilities($params)
	{
		$facs = array();
		$areas = array();
		$db =& JFactory::getDBO();
		
		$area = (int) $params->get('research_area');
		$order = $params->get('order');
		$order_dir = strtoupper($params->get('order_dir'));
		
		//Building query
		$query = 'SELECT id, id_research_area, name FROM #__jresearch_facilities WHERE published=1';
		
		if($area > 0)
		{
			$query .= ' AND id_research_area='.$area;
			$areas[0] = $area;
		}
		else
		{
			$areasQuery = 'SELECT id_research_area FROM #__jresearch_facilities GROUP by id_research_area';
			$db->setQuery($areasQuery);
			
			$areasResult = $db->loadObjectList();

			foreach($areasResult as $row)
			{
				$areas[] = $row->id_research_area;
			}
		}
		
		$query .= ' ORDER by id_research_area ASC, '.$order.' '.$order_dir;
		
		//Set query
		$db->setQuery($query);
		
		//Get objects
		$facs = $db->loadObjectList();
	
		return array("facs" => $facs, "areas" => $areas);
	}
}