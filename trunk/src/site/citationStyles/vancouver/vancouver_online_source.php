<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'vancouver'.DS.'vancouver.php');


/**
* Implementation of Vancouver citation style for online sources.
*
*/
class JResearchVancouverOnline_sourceCitationStyle extends JResearchVancouverCitationStyle{
		
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
		$text = '';
		
		if($nAuthors <= 0){
			$authorsText = '';
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		$text .= rtrim($authorsText, '.');		
		
		$title = $html?'<i>'.trim($publication->title).'</i>':trim($publication->title);	
		if(!empty($text))
			$text .= '. '.$title;
		else
			$text .= $title;

		switch($publication->source_type){
			case 'image':
				$type = JText::_('JRESEARCH_IMAGE');
				break;
			case 'video':
				$type = JText::_('JRESEARCH_VIDEO');
				break;
			case 'website': 
				$type = '';
				break;
			case 'blog':
				$type = JText::_('JRESEARCH_WEBLOG');
				break;
			case 'audio':
				$type = JText::_('JRESEARCH_AUDIO_PODCAST');	
		}	
		
		if(!empty($type))
			$text .= '. '.$type;	
		
		$text .= '. ['.JText::_('JRESEARCH_ONLINE').']';
			
		$url = trim($publication->url);
		if(!empty($url)){
			$url = $html? "<a href=\"$url\">$url</a>":$url;
			$available = JText::sprintf('JRESEARCH_AVAILABLE_FROM', $url);
			$text .= ' '.$available;
		}
		
		$access_date = trim($publication->access_date);
		if(!empty($access_date) && $access_date != '0000-00-00'){			
			$retrievedText = JText::sprintf('JRESEARCH_ACCESSED', date('dS F Y', strtotime($access_date)));
			$text .= ' ['.$retrievedText.']';		
		}
		
		
		return $text.'.';			
	}
}

?>
