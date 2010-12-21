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

class modJResearchPapersHelper
{
	/**
	 * Gets cooperations from database
	 *
	 * @return array
	 */
	function getNewestPapers($limit=5)
	{
		$limit = intval($limit,10);
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__jresearch_publication WHERE published = 1 AND status = 'finished' AND source = ´ORW´ ORDER by created DESC LIMIT 0,$limit";
		
		return $this->_getResult($query);
	}
	
	function getMostViewed($limit=5)
	{
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__jresearch_publication WHERE published = 1 AND status = 'finished' AND source = ´ORW´ ORDER by hits DESC LIMIT 0,$limit";
		
		return $this->_getResult($query);
	}
	
	private function _getResult($query)
	{
		$db = &JFactory::getDBO();
		$papers = array();		
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $item){
			$pub = JTable::getInstance("Publication", "JResearch");
			$pub->bind($item, array(), true);
			$papers[] = $pub;
		}
		
		return $papers;
	}
}
