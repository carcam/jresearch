<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'language.php');

/**
 * This class holds useful methods for dealing with publications records.
 *
 */
class JResearchPublicationsHelper{
	
	/**
	 * Returns an associative array with the components of an author name according to 
	 * Bibtex definition.
	 * Authors names in Bibtex records may have a maximun of four components:
	 * - First name with key is 'firstname'
	 * - Last name with key 'lastname'
	 * - Von prefix with key 'von'
	 * - Jr. with key 'jr'
	 * The formats accepted for Bibtex for authors names are:
	 * - firstname lastname
	 * - lastname, firstname
	 * - firstname von lastname
	 * - von lastname, firstname
	 * - von lastname, jr, firstname
	 * 
	 * @param string The complete author name in one of the format exposed above.
	 * @return array Associative array of the components using the keys exposed above. 
	 */
	public static function getAuthorComponents($authorname){
		$doc = JFactory::getDocument();
		$matches = array();
		$result = array();
		
		$extraCharacters = extra_word_characters();
		// We use regular expresions to match any of the formats.
		$pattern1 = "/^([\w$extraCharacters.']+)\s+([\w$extraCharacters']+)$/u";
		$pattern2 = "/^([\w$extraCharacters.']+)\s+([\w$extraCharacters.']+)\s+([\w$extraCharacters']+)$/u";
		$pattern3 = "/^([\w$extraCharacters.']+)\s+([\w$extraCharacters']+)\s*,\s*([\w$extraCharacters.']+)$/u";
		$pattern4 = "/^([\w$extraCharacters.']+)\s+([\w$extraCharacters.']+)\s*,\s*([\w$extraCharacters.,]+)\s*,\s*([\w$extraCharacters.']+)$/u";
		$pattern5 = "/^([\w$extraCharacters']+)\s*,\s+([\w$extraCharacters.']+)$/u";
	
		if(preg_match($pattern1, $authorname, $matches)){
			$result['firstname'] = $matches[1];
			$result['lastname'] = $matches[2];
		}elseif(preg_match($pattern2, $authorname, $matches)){
			$result['firstname'] = $matches[1];
			$result['von'] = $matches[2];
			$result['lastname'] = $matches[3];
		}elseif(preg_match($pattern3, $authorname, $matches)){
			$result['von'] = $matches[1];
			$result['lastname'] = $matches[2];
			$result['firstname'] = $matches[3];
		}elseif(preg_match($pattern4, $authorname, $matches)){
			$result['von'] = $matches[1];
			$result['lastname'] = $matches[2];
			$result['jr'] = $matches[3];
			$result['firstname'] = $matches[4];
		}elseif(preg_match($pattern5, $authorname, $matches)){
			$result['lastname'] = $matches[1];
			$result['firstname'] = $matches[2];		
		}else{
			$result['lastname'] = $authorname;
		} 

		return $result;
	}

	
	/**
	 * Determines if two publications has the same authors.
	 *
	 * @param JResearchPublication $publication1
	 * @param JResearchPublication $publication2
	 * 
	 * @return boolean True if publications have the same authors even if they are
	 * in different order.
	 */
	public static function sameAuthors($publication1, $publication2){
		$authors1 = $publication1->getAuthors();
		$authors2 = $publication2->getAuthors();
		
		if(count($authors1) != count($authors2))
			return false;
		foreach($authors1 as $auth){
			if(!in_array($auth, $authors2))
				return false;
		}
		
		return true;
	}

	/**
	 * Returns the first word of the specified title.
	 *
	 * @param string $title
	 * @return string
	 */

	public static function getFirstWord($title){
		$separators = '/[,\\s;.:]/';
		$words =  preg_split($separators, $title, -1, PREG_SPLIT_NO_EMPTY);
		return $words[0];
	}
}

?>
