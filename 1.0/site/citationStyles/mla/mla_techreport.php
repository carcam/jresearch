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
* Implementation of MLA citation style for technical reports.
*
* @subpackage		JResearch
*/
class JResearchMLATechreportCitationStyle extends JResearchMLACitationStyle{
	
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
	function getReferenceHTMLText(JResearchPublication $publication, $authorsLinks=false){
		return $this->getReference($publication, true, $authorsLinks);
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
	protected function getReference(JResearchPublication $publication, $html=false, $authorsLinks=false){		
		$this->lastAuthorSeparator = 'and';
		$authors = $publication->getAuthors();
		$n = count($authors);
		
		// For techreports, authors are usually organizations, so do not extract lastnames
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorsLinks));
		$title = trim($publication->title);
		$title = $html? "<u>$title</u>":$title;

		if(empty($authorsText))
			$head = "$title";
		else
			$head = "$authorsText. $title";	
		
		$institution = trim($publication->institution);	
		if($publication->year != null && $publication->year != '0000')		
			return "$head. $institution, $publication->year";
		else
			return "$head. $institution";	

	}
	
}
?>