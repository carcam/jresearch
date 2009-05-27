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

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'exporters'.DS.'exporter.php');

/**
 * This class allows to convert JResearchPublication instances into bibtex
 * text.
 *
 */
class JResearchPublicationBibtexExporter extends JResearchPublicationExporter{
	/*
	* Array with the names of all supported types.
	*/	
	private static $_supportedFields;
	
	/**
	 * Parse the array of JResearchPublication objects into a bibtex text.
	 *
	 * @param mixed $publications JResearchPublication object or array of them. 
	 * @return string Representation of the objects in a bibtext format, null if it is
	 * not possible to parse the objects.
	 */
	function parse($publications){
		$output = "";
		if(!is_array($publications))
			return $this->parseSingle($publications);
		else{
			foreach($publications as $pub){
				$output .= $this->parseSingle($pub)."\n";
			}
		}
		
		return $output;
			
	}
	
	/**
	* Parse a single JResearchPublication object into a bibtex text.
	*
	* @param JResearchPublication Object to parse.
	*/
	private function parseSingle($publication){
		$output = null;
		require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');	

		if($publication instanceof JResearchPublication){
			$properties = $publication->__toArray();
			$citekey = $publication->citekey;
			$type = $publication->pubtype;

			$output = '@'.$type. '{'.$citekey.','."\n";
			$authors = $publication->getAuthors();

			$authorsText = implode(" and ", JResearchPublicationsHelper::utf8ToBibCharsFromArray($authors));
			$output .= "author = \"$authorsText\",\n";
			$properties = JResearchPublicationBibtexExporter::getSupportedFields();
			foreach($properties as $p){
				$value = JResearchPublicationsHelper::utf8ToBibCharsFromString($publication->$p);
				if(!empty($value)){
					$output .= "$p = \"$value\",";
					$output .= "\n";
				}				
			}
			$output .= "}\n\n";			
		}
		
		return $output;
	}

	/**
	* Returns an array with the names of all supported fields for publications records.
	* 
	* @return array 	 
	*/
	private static function getSupportedFields(){
		if(!isset(self::$_supportedFields)){		
			$db = &JFactory::getDBO();
			
			$db->setQuery('SELECT * FROM '.$db->nameQuote('#__jresearch_property'));
			self::$_supportedFields = $db->loadResultArray();
		}
		
		return self::$_supportedFields;
	}
	
}
?>