<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2008 Luis Galárraga.
* @license		GNU/GPL
* 
*/

/**
 * Helper class for functions related to language functionalities.
 */

class JResearchLanguageHelper{
	
	/**
	 * Returns an associative array with all information about languages
	 * supported by J!Research
	 * @return array
	 */
	static function getLanguages(){
		$db = JFactory::getDBO();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_language');
		$db->setQuery($query);
		return $db->loadAssocList();
	}
}

?>