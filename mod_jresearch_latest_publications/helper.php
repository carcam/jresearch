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
	 * Gets latest papers from database
	 *
	 * @return array
	 */
	public static function getNewestPapers($limit = 5, $criteria = 'entry_date')
	{
		$papers = array();
		$limit = intval($limit, 10);
		$db = JFactory::getDBO();
		$orderBy = '';
		if($criteria == 'entry_date'){
			$orderBy = 'created';
		}else{
			$orderBy = "STR_TO_DATE('month day, year', '%M %d, %Y'), created";
		}
		
		$query = "SELECT * FROM #__jresearch_publication WHERE published = 1 AND internal = 1 ORDER by featured DESC, $orderBy DESC LIMIT 0, $limit";		
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $item){
			$pub = JTable::getInstance("Publication", "JResearch");
			$pub->bind($item, array(), true);
			$papers[] = $pub;
		}
						
		return $papers;
	}
	
	public static function getSponsorTeam($publication){
		$db = JFactory::getDBO();
		$pubId = $db->Quote($publication->id);
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_team').' WHERE '.$db->nameQuote('id').' = '.$db->Quote($publication->id_team);
		$db->setQuery($query);
		$result = $db->loadAssoc();						
		return $result;
	}
	
}