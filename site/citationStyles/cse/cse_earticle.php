<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'cse'.DS.'cse.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


/**
* Implementation of CSE citation style for article records.
*
*/
class JResearchCSEEarticleCitationStyle extends JResearchCSECitationStyle{
	
		
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

		$year = trim($publication->year);
		if(!empty($year) && $year != '0000'){		
			$text .= '. '.$year;			
			if($publication->__sameAuthorAsBefore){	
				$text .= $publication->__previousLetter;
			}
		}
		
		
		$title = trim($publication->title);	
		$text .= '. '.$title;

		$journal = trim($publication->journal);
		if(!empty($journal))		
			$text .= '. '.$journal;

		$text .= ' ['.JText::_('JRESEARCH_INTERNET').']';	
		$access_date = trim($publication->access_date);
		if(!empty($access_date) && $access_date != '0000-00-00'){			
			$retrievedText = JText::sprintf('JRESEARCH_CITED_LOWER', date('Y M d', strtotime($access_date)));
			$text .= '. ['.$retrievedText.']';
		}			
			

		$volume = trim($publication->volume);
		if(!empty($volume))
			$text .= '. '.$volume;
		
		$number = trim($publication->number);
		if(!empty($number))
			$text .= "($number)";

		$pages = str_replace('--', '-', trim($publication->pages));		
		if(!empty($pages))
			$text .= ': '.$pages;

		$url = trim($publication->url);
		if(!empty($url)){
			$url = $html? "<a href=\"$url\">$url</a>":$url;
			$from = JText::sprintf('JRESEARCH_AVAILABLE_FROM', $url);
			$text .= '. '.$from;
		}
			
		return $text.'.';
	}

}
?>