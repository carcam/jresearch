<?php
/**
* @version		$Id$
* @package		J!Research
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

/**
 * This class is used to implement the factory pattern design for 
 * exporters objects. Exporters are objects that take JResearchPublication objects
 * and parse them to different text formats like MODS, Bibtex, RDF, and RSS
 *
 */
class JResearchPublicationExporterFactory{
	
	/**
	 * Returns an instance of the exporter object that can convert JResearchPublication objects
	 * into the output format.
	 *
	 * @param string $outputFormat, e.g MODS, Bibtex, RDF, etc.
	 * @return JResearchPublicationExporter 
	 */
	public static function &getInstance($outputFormat){
		static $instances;
		
		$exportersFolder  = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'exporters';

		if(!$instances){
			$instances = array();
		}			
		// We just construct the name of the class based on the standard defined: JResearch{Format name in original case}Exporter
		$classname = 'JResearchPublication'.$outputFormat.'Exporter';
		$filename = $exportersFolder.DS.strtolower($outputFormat).'.php';
		if(!isset($instances[$classname])){	
			if(!class_exists($classname)){
				if(!file_exists($filename))
					return null;
		
				require_once($filename);
					
				if(class_exists($classname))
					$instances[$classname] = new $classname();	
				else
					return null;
			}else{
				$instances[$classname] = new $classname();
			}
		}
		
		return $instances[$classname];
		
	}
}

?>