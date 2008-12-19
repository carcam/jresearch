<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage		JResearch
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once('JResearchCitationStyle.php');

/**
* Base class for implementation of Chicago citation style
*
* @subpackage		JResearch
*/
abstract class JResearchChicagoCitationStyle implements JResearchCitationStyle{
	
	/**
	* Takes a publication and returns the string that would be printed when citing the work in a non­
	* parenthetical way.
	* @return 	string
	*/
	function getCitationText(JResearchPublication $publication);


	/**
	* Takes a publication and returns the HTML string output that would be printed when citing the work 
	* in a non­parenthetical way.
	* @return 	string
	*/
	function getCitationHTMLText(JResearchPublication $publication);

	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication);
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication);
	
	/**
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way.
	* @return 	string
	*/  
	function getParentheticalCitationText(JResearchPublication $publication);
	

	/** Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way
	* @return 	string
	*/
	function getParentheticalCitationHTMLText(JResearchPublication $publication);
}
?>
