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

class JResearchCountryHelper{
	
	/**
	 * Returns an associative array with all information about languages
	 * supported by J!Research
	 * @return array
	 */
	static function getCountries(){
		$db = JFactory::getDBO();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_country').' ORDER BY name ASC';
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	
	/**
	 * Returns the information about a single language
	 * @param string $key The column by which the query is performed. 
	 * @param string $value
	 * @return $value
	 */
	static function getCountry($value){
		$db = JFactory::getDBO();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_country').' WHERE '.$db->nameQuote('id').' = '.$db->Quote($value);
		$db->setQuery($query);
		return $db->loadAssoc();		
	}
}

?>