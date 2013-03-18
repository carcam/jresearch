<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'mla'.DS.'mla.php');


/**
* Implementation of MLA citation style for manual records.
*
*/
class JResearchMLAManualCitationStyle extends JResearchMLACitationStyle{
			
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
		$this->lastAuthorSeparator = JText::_('JRESEARCH_AND');
		$authors = $publication->getAuthors();
		$n = count($authors);
		$text = '';
		
		// For techreports, authors are usually organizations, so do not extract lastnames
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
		$title = trim($publication->title);
		$title = $html? "<i>$title</i>":$title;

		if(empty($authorsText))
			$text .= $title;
		else{
			$authorsText = rtrim($authorsText, '.');
			$text .= $authorsText.'. '.$title;
		}
		
		$ed = JText::_('JRESEARCH_APA_EDITOR_LOWER');
		$edition = trim($publication->edition);
		if(!empty($edition))
			$text .= '. '.$edition.' '.$ed;

		$organization = trim($publication->organization);
		if(!empty($organization))
			$text .= '. '.$organization;
	
		$address = trim($publication->address);
		if(!empty($address))
			$text .= '. '.$address;	

		$year = trim($publication->year);			
		if($year != null && $year != '0000')		
			$text .= ', '.$year;

		return $text.'.';	
	}
	
	
	
}
?>
