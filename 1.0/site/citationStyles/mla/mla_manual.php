<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage		JResearch
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'mla'.DS.'mla.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


/**
* Implementation of MLA citation style for manual records.
*
* @subpackage		JResearch
*/
class JResearchMLAManualCitationStyle extends JResearchMLACitationStyle{
	

	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication){
		return $this->getReference($publication);
	}
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication, $authorLinks=false){
		return $this->getReference($publication, true, $authorLinks);
	}
			
			
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param boolean $html Add html tags for formats like italics or bold
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = 'and';
		$authors = $publication->getAuthors();
		$n = count($authors);
		
		// For techreports, authors are usually organizations, so do not extract lastnames
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
		$title = trim($publication->title);
		$title = $html? "<u>$title</u>":$title;

		if(empty($authorsText))
			$head = "$title";
		else
			$head = "$authorsText. $title";	
		$organization = trim($publication->organization);

		if($publication->year != null && $publication->year != '0000')		
			return "$head. $organization, $publication->year";
		else
			return "$head. $organization";	

	}
	
	
	
}
?>