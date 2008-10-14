<?php
/**
* @version		$Id$
* @package		JResearch
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
 * importers objects. Importers are objects that take publications
 * records in different text formats and parse them into JResearchPublications
 * objects.
 *
 */
class JResearchPublicationImporterFactory{
	
	/**
	 * Returns an instance of the importer object that can parse records in the text
	 * format indicated in the parameter $inputFormat. All importers classes must reside
	 * in the frontend folder helpers/importers.
	 *
	 * @param string $inputFormat
	 * @return JResearchPublicationImporter 
	 */
	public static function getInstance($inputFormat){
		static $instances;
		
		$importersFolder  = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'importers';

		if(!$instances){
			$instances = array();
		}			
		// We just construct the name of the class based on the standard defined: JResearch{Format name in original case}Importer
		$classname = 'JResearch'.$inputFormat.'Importer';
		$filename = $importersFolder.DS.strtolower($inputFormat).'.php';
		
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