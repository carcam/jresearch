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

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'exporters'.DS.'exporter.php');

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
	
	private static $_bibtexTypes = array('article', 'book', 'booklet', 'conference', 'inbook', 'incollection', 'inproceedings', 'manual', 'mastersthesis', 'misc', 'phdthesis', 'proceedings', 'techreport', 'unpublished');

	private static $_supportedTypes;

	private static $_typesMap = array('patent' => 'misc', 'earticle' => 'article', 'digital_source' => 'misc', 'online_source' => 'misc');
	
	/**
	 * Parse the array of JResearchPublication objects into a bibtex text.
	 *
	 * @param mixed $publications JResearchPublication object or array of them. 
	 * @param array $options An associate array containing a series of configuration options.
	 * @return string Representation of the objects in a bibtext format, null if it is
	 * not possible to parse the objects.
	 */
	function parse($publications, $options = array()){
		$output = "";
		if(!is_array($publications))
			return $this->parseSingle($publications);
		else{
			foreach($publications as $pub){
				$output .= $this->parseSingle($pub, $options)."\n";
			}
		}
		
		return $output;
			
	}
	
	/**
	* Parse a single JResearchPublication object into a bibtex text.
	* @param array $options An associate array containing a series of configuration options. The following keys are accepted:
	* - strict_bibtex If true, non standard bibtex types will be mapped to standard types according to the following rules:
	*   - patents, online_source and digital_source will be converted to misc
	*   - earticle will be converted to article
	* @param JResearchPublication Object to parse.
	*/
	private function parseSingle($publication, $options = array()){
		$output = null;
		require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');	

		if($publication instanceof JResearchPublication){
			$properties = $publication->__toArray();
			$citekey = $publication->citekey;
			$type = $publication->pubtype;
			
			if(isset($options['strict_bibtex'])){
				if($options['strict_bibtex'])
					$supportedTypes = $this->_getBibtexTypes();
				else
					$supportedTypes = $this->_getSupportedTypes();	
			}else{
				$supportedTypes = $this->_getSupportedTypes();	
			}
			
			if(in_array($type, $supportedTypes))
				$output = '@'.$type. '{'.$citekey.','."\n";	
			else
				$output	= '@'.$this->_mapNonStandardType($type).'{'.$citekey.','."\n";
						
			$authors = $publication->getAuthors();
			$authorsText = implode(" and ", JResearchPublicationsHelper::utf8ToBibCharsFromArray($authors));
			$output .= "author = \"$authorsText\",\n";
			$properties = $this->_getSupportedFields();
			foreach($properties as $p){
				if(isset($publication->$p)){
					$value = JResearchPublicationsHelper::utf8ToBibCharsFromString($publication->$p);
					
					if($p == 'title')
						$value = JResearchPublicationsHelper::formatBibtexTitleForExport($value);					

					if(!empty($value)){
						$output .= "$p = \"$value\",";
						$output .= "\n";
					}
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
	private function _getSupportedFields(){
		if(!isset(JResearchPublicationBibtexExporter::$_supportedFields)){		
			$db = JFactory::getDBO();
			
			$db->setQuery('SELECT * FROM '.$db->nameQuote('#__jresearch_property'));
			JResearchPublicationBibtexExporter::$_supportedFields = $db->loadResultArray();
		}
		
		return JResearchPublicationBibtexExporter::$_supportedFields;
	}
	
	/**
	 * Returns an array with all standard Bibtex types.
	 * @return array
	 */
	private function _getBibtexTypes(){
		return JResearchPublicationBibtexExporter::$_bibtexTypes;
	}
	
	/**
	 * Returns an array with the names of all J!Research supported publication types
	 * @return array
	 */
	private function _getSupportedTypes(){
		if(!isset(JResearchPublicationBibtexExporter::$_supportedTypes)){		
			$db = JFactory::getDBO();
			
			$db->setQuery('SELECT * FROM '.$db->nameQuote('#__jresearch_publication_type'));
			JResearchPublicationBibtexExporter::$_supportedTypes = $db->loadResultArray();
		}
		
		return JResearchPublicationBibtexExporter::$_supportedTypes;
		
	}
	
	/**
	 * Maps non standard publication types to standard Bibtex types. 
	 * @return string $type
	 */
	private function _mapNonStandardType($type){
		return JResearchPublicationBibtexExporter::$_typesMap[$type];
	}
	
}
?>