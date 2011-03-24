<?php
/**
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class modJResearchInstitutesHelper
{
	/**
	 * Gets institutes from database
	 *
	 * @return array
	 */
	function getHitparade($limit=5)
	{
		$institutes = array();
		$limit = intval($limit,10);
		$db = &JFactory::getDBO();
		$query = "SELECT i.*, COUNT(i.id) AS count FROM #__jresearch_institute AS i LEFT JOIN #__jresearch_publication AS p ON (p.id_institute = i.id AND p.published = 1 AND i.name != 'unknown' AND p.status = 'finished' AND p.source = 'ORW') GROUP by i.id ORDER by count DESC LIMIT 0,$limit";
		
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $item){
			$inst = JTable::getInstance("Institute", "JResearch");
			$inst->load($item['id']);
			$institutes[] = array("i" => $inst, "count" => $item['count']);
		}
		
		return $institutes;
	}
}
