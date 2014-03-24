<?php
/**
* @package		JResearch
* @subpackage	
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class JResearchProjectsHelper{
	
	/**
	 * Gets a list of all years used for projects
	 */
   	public static function getYears(){
       $db = JFactory::getDBO();
       $db->setQuery('SELECT DISTINCT YEAR(start_date) as year FROM '.$db->quoteName('#__jresearch_project').' ORDER BY '.$db->quoteName('start_date').' DESC ');
       return $db->loadColumn();
   	}
   	
   	/**
   	 * Get the list of all authors who are involved in projects
   	 */
   	public static function getAllAuthors(){
   		$db = JFactory::getDBO();
   		$db->setQuery('SELECT DISTINCT mid, member_name FROM '.$db->quoteName('#__jresearch_all_project_authors'));
   		return $db->loadAssocList();
   	}
   	
	/**
	 * Returns a project given its id.
	 * @param int $id
	 */
	public static function getProject($id){
		jresearchimport('tables.project', 'jresearch.admin');
		
		$project = JTable::getInstance('Project', 'JResearch');
		if($project->load($id)){
			return $project;
		}else{
			return null;
		}
	}   	
}
?>