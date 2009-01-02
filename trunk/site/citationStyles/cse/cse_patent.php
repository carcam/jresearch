<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'cse'.DS.'cse_article.php');

/**
 * CSE citation style implementation for patents
 *
 */
class JResearchCSEPatentCitationStyle extends JResearchCSECitationStyle{
		
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param boolean $html Add html tags for formats like italics or bold
	* @param boolean $authorPreviouslyCited If true
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = JText::_('JRESEARCH_BIBTEXT_AUTHOR_SEP');
		$nAuthors = $publication->countAuthors();
		$text = '';

		
		if($nAuthors <= 0){
			$authorsText = '['.JText::_('JRESEARCH_ANONYMOUS').']';
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$text .= rtrim($authorsText, '.');				
		$title = trim($publication->title);	
		$text .= '; '.$title;
		
		$number = trim($publication->patent_number);
		if(!empty($number))
			$text .= '. '.JText::_('JRESEARCH_PATENT').' '.$number;
		
		$issue_date = trim($publication->issue_date);
		if(!empty($issue_date))
			$text .= '. '.$issue_date;
			
		return $text.'.';
	}
}
?>