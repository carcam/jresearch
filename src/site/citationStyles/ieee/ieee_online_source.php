<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'ieee'.DS.'ieee.php');

/**
* Implementation of IEEE citation style for online sources.
*
*/
class JResearchIEEEOnline_sourceCitationStyle extends JResearchIEEECitationStyle{
	
		
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
		$nAuthors = $publication->countAuthors();
		
		$authorsText = '';
		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$title = trim($publication->title);	
		$title = '"'.$title.'"';
				
		if(!empty($authorsText))
			$header = rtrim($authorsText, '.').'. '.$title;
		else
			$header = $title;	

		$year = trim($publication->year);
		if($year!= null && $year != '0000'){
			$date = '';
			$month = trim($publication->month);
			if(!empty($month)){
				$month = JResearchPublicationsHelper::formatMonth($month, true);
				$day = trim($publication->day);
				if(!empty($day))
					$date .= $day.'. ';
				$date .= $month.'. ';
			}
			$date .= $year;	
			$header .= ' '.$date;
		}
		
		switch($publication->source_type){
			case 'image':
				$type = JText::_('JRESEARCH_IMAGE');
				break;
			case 'video':
				$type = JText::_('JRESEARCH_VIDEO');
				break;
			case 'website': 
				$type = JText::_('JRESEARCH_ONLINE');
				break;
			case 'blog':
				$type = JText::_('JRESEARCH_WEBLOG');
				break;
			case 'audio':
				$type = JText::_('JRESEARCH_AUDIO_PODCAST');	
		}	
		
		if(!empty($type))
			$header .= '. ['.$type.']';
		
		$url = trim($publication->url);
		if(!empty($url)){
			$url = $html? "<a href=\"$url\">$url</a>":$url;
			$available = JText::sprintf('JRESEARCH_AVAILABLE', $url);
			$header .= '. '.$available;
		}
					
		$access_date = trim($publication->access_date);
		if(!empty($access_date) && $access_date != '0000-00-00'){			
			$retrievedText = JText::sprintf('JRESEARCH_ACCESSED_WITH_COLON', date('F. d. Y', strtotime($access_date)));
			$header .= ' ['.$retrievedText.']';
		}		
		
		
		return $header.'.';	

	}

}
?>