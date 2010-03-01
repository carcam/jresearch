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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'importers'.DS.'importer.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'importers'.DS.'factory.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'importers'.DS.'bibtex.php');

/**
* Imports sets of bibliographical references in RIS format. 
* For more information about RIS, see http://
* 
*/
class JResearchRISImporter extends JResearchPublicationImporter{
	
	/**
	 * Parse the text sent as parameter in MODS format and converts it into 
	 * an array of JResearchPublication objects.
	 *
	 * @param string $text
	 * @param array of JResearchPublication objects
	 */
	function parse($text){
		$filename = tempnam(JPATH_SITE.DS.'media', "mods_");
		$inputFile = fopen($filename, "w");
		
		/** boolean True if a Windows based host */
		fwrite($inputFile, $text);	

		if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			$folder = 'win32';
		elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'MAC')
			$folder = 'macos';
		else
			$folder = 'unix';	

		$modsParser = JResearchPublicationImporterFactory::getInstance('MODS');
		// Invoke the conversion command
		$conversionCommand = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'bibutils'.DS.$folder.DS.'ris2xml'.' '.$filename;
		
		$output = array();
		exec($conversionCommand, $output);
		$modsText = implode("\n", $output); 
		
		fclose($inputFile);
		@unlink($filename);
		
		return $modsParser->parse($modsText);		
	}
}
?>