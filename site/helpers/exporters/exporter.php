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

/**
 * This is the base class for exporters objects. Exporters are objects that take JResearchPublication
 * objects and parse them into diferent formats. Examples of formats are Bibtex, MODS (XML), RDF/XML,
 * entre otros.
 *
 */
abstract class JResearchPublicationExporter{
	
	/**
	 * Parse the array of JResearchPublication objects into a text format.
	 *
	 * @param mixed $publications JResearchPublication object or array of them. 
	 * @return string Representation of the objects in a text format.
	 */
	abstract function parse($publications);

	/**
	* Returns the MIME encoding of the output generated by the parser. The default
	* is text/plain as these classes return string variables, but derived classes
	* can override the method to specify other text formats.
	*/	
	function getMimeEncoding(){
		return "text/plain";
	}
}
?>