<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file declares the base interface for all citation styles.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


/**
* Base interface for all citation styles
*
*/
interface JResearchCitationStyle{
	
	/**
	* Takes a publication and returns the string that would be printed when citing the work in a non­
	* parenthetical way. It is used when the author is subject or object in the sentence that includes
	* the cite.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return string
	*/
 	function getCitationText($publication);


	/**
	* Takes a publication and returns the HTML string output that would be printed when citing the work 
	* in a non­parenthetical way.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getCitationHTMLText($publication);

	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication);
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* 
	* @param JResearchPublication $publication 
	* @param $authorLinks If true, internal authors names are included as links to their profiles.
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication, $authorLinks = false);
	
	/**
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way. It means the author is neither subject nor object in 
	* the sentence that includes the cite.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/  
	function getParentheticalCitationText($publication);
	

	/** 
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getParentheticalCitationHTMLText($publication);
	
	/**
	 * Takes an array of JResearchPublication objects and returns the HTML that
	 * would be printed considering those publications were cited.
	 *
	 * @param array $publicationsArray
	 * @param boolean $authorsLinks
	 */
	function getBibliographyHTMLText($publicationsArray, $authorsLinks = false);
	
	/**
	 * Returns an array of sorted publications according to citation styles rules for generation
	 * of "References" section.
	 * @since 1.2
	 * @param array $publicationsArray
	 * @return array Sorted array of publications according to citation style rules.
	 */
	function sort(array $publicationsArray);
	
}
?>
