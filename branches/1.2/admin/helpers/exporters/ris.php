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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'exporters'.DS.'exporter.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'exporters'.DS.'factory.php');

/**
 * This class allows to export sets of JResearchPublication objects into RIS
 * output.
 *
 */
class JResearchPublicationRISExporter extends JResearchPublicationExporter{
	
	/**
	 * Parse the array of JResearchPublication objects into MODS text.
	 *
	 * @param mixed $publications JResearchPublication object or array of them. 
	 * @param array $options An associate array containing a series of configuration options.
	 * @return string Representation of the objects in MODS format, null if it is
	 * not possible to parse the objects.
	 */
	function parse($publications, $options = array()){
		if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			$folder = 'win32';
		elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'MAC')
			$folder = 'macos';
		else
			$folder = 'unix';	 
		
		$filename = tempnam(JPATH_SITE.DS.'media', "mods_");
		$inputFile = fopen($filename, "w");
		
		$modsExporter =& JResearchPublicationExporterFactory::getInstance('MODS');
		$modsText = $modsExporter->parse($publications);
		
		fwrite($inputFile, $modsText);						
		
		$conversionCommand = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'bibutils'.DS.$folder.DS.'xml2ris'.' -i utf8 '.escapeshellarg($filename);
		$output = array();
		exec($conversionCommand, $output);
		$risText = implode("\n", $output); 

		fclose($inputFile);
		@unlink($filename);	
		
		return $risText; 
	}	
}
?>