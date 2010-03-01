<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'chicago'.DS.'chicago.php');


/**
* Implementation of Chicago citation style for article records.
*
*/
class JResearchChicagoOnline_sourceCitationStyle extends JResearchChicagoCitationStyle{
		
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
		$titleCons = false;
		
		if($nAuthors <= 0){
			$authorsText = '';
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}

		$title = trim($publication->title);
		
		if(!empty($authorsText))
			$text .= $authorsText;
		else{
			$titleCons = true;
			$text .= $title;
		}	
		
		$year = trim($publication->year);		
		
		if(!$titleCons)
			$text .= '. '.$title;
			
		$url = trim($publication->url);
		if(!empty($url)){
			$url = $html? "<a href=\"$url\">$url</a>":$url;
			$from = JText::_('JRESEARCH_FROM').': '.$url;
			$text .= '. '.$from;
		}
					
		$access_date = trim($publication->access_date);
		if(!empty($access_date) && $access_date != '0000-00-00'){			
			$retrievedText = JText::sprintf('JRESEARCH_ACCESSED', date('F d, Y', strtotime($access_date)));
			$text .= ' ('.$retrievedText.')';
		}
		
		return $text.'.';
	}
}
?>