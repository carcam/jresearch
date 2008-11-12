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
 * This is the base class for importers objects. Importers are objects that take publications
 * records in different text formats and parse them into JResearchPublications objects.
 *
 */
abstract class JResearchPublicationImporter{
	
	/**
	 * Parse the contents of $filename into an array of JResearchPublication objects.
	 *
	 * @param string $filename 
	 * @return array of the JResearchPublication objects that could be parsed. null if
	 * there were problems when accessing the file.
	 */
	function parseFile($filename){
		$file = @fopen($filename, "r");
		
		if($file === false)
			return null;

		$text = fread($file, filesize($filename));

		return $this->parse($text);
	}
	
	/**
	 * Parse the text sent as parameter into an array of JResearchPublication objects.
	 *
	 * @param string $text
	 */
	public abstract function parse($text);
}
?>