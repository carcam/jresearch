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
* Implementation of MLA citation style for online sources.
*
*/
class JResearchMLAOnline_sourceCitationStyle extends JResearchMLACitationStyle{
				
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
		$nAuthors = $publication->countAuthors();
		$text = '';
				
		if(!$publication->__authorPreviouslyCited){
			if($nAuthors > 0){
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
			}
		}else{
			$authorsText = '---';
		}
		
		$title = '"'.trim($publication->title).'"';
		

		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');
			$header = $authorsText.'. '.$title;
		}else
			$header = $title;	

		$text .= $header;					

		$year = trim($publication->year);
		if($year!= null && $year != '0000'){
			$date = '';
			$month = trim($publication->month);
			if(!empty($month)){
				$month = JResearchPublicationsHelper::formatMonth($month, true);
				$day = trim($publication->day);
				if(!empty($day))
					$date .= $day.' ';
				$date .= $month.' ';
			}
			$date .= $year;	
			$text .= '. '.$date;
		}
		
		switch($publication->source_type){
			case 'image':
				$type = JText::_('JRESEARCH_ONLINE_IMAGE');
				break;
			case 'video':
				$type = JText::_('JRESEARCH_ONLINE_VIDEOCLIP');
				break;
			case 'website':
				$type = '';
				break;
			case 'blog':
				$type = JText::_('JRESEARCH_BLOG');
				break;				
			case 'audio':
				$type = JText::_('JRESEARCH_AUDIO_PODCAST');	
				break;
		}
		if(!empty($type))
			$text .= '. '.$type;
				
		$access_date = trim($publication->access_date);				
		if(!empty($access_date) && $access_date != '0000-00-00'){			
			$retrievedText = date('d M Y', strtotime($access_date));
			$text .= '. '.$retrievedText;				
		}
		
		$url = trim($publication->url);
		if(!empty($url)){
			if($html)
				$url = "&lt;<a href=\"$url\">$url</a>&gt;";
			else
				$url = '<'.$url.'>';
			
			$text .= ' '.$url;
		}		
						
		return $text.'.';	
	}
	
	
}
?>
