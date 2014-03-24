<?php
/**
* @package		JResearch
* @subpackage	helpers
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Utilities related to research areas
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * This class holds useful methods for dealing with publications records.
 *
 */
class JResearchResearchareasHelper{
	
	/**
	 * Returns a list of all published research areas
	 * @return array
	 */
	public static function getResearchAreas(){
		$db = JFactory::getDbo();
		$areas = array();
		jresearchimport('tables.researcharea', 'jresearch.admin');
		
		$db->setQuery('SELECT * FROM '.$db->quoteName('#__jresearch_research_area').' WHERE published = '.$db->Quote(1));
		$result = $db->loadAssocList();
		foreach($result as $row){
			$area = JTable::getInstance('Researcharea', 'JResearch');
			$area->bind($row);
			$areas[] = $area;			
		}
		
		return $areas;
	}
	
	/**
	 * Return a researcharea given its id
	 * @param int $id
	 */
	public static function getResearchArea($id){
		jresearchimport('tables.researcharea', 'jresearch.admin');
		$area = JTable::getInstance('Researcharea', 'JResearch');
		
		if($area->load($id)){
			return $area;
		}else{
			return null;
		}
		
	}
}