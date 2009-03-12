<?php 
/**
 * @version			$Id$
* @package		JResearch
* @subpackage	Citation
 * @copyright		Copyright (C) 2008 Luis Galarraga.
 * @license			GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'apa'.DS.'apa.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');

/**
* Implementation of APA citation style for online sources.
*
*/
class JResearchAPAOnline_sourceCitationStyle extends JResearchAPACitationStyle{
	
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param $html Add html tags for formats like italics or bold
	* @param boolean $authorLinks If true, internal authors profile links will be included.
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = $html?'&amp;':'&';
		$nAuthors = $publication->countAuthors();
		$text = '';
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_APA_EDITORS'):JText::_('JRESEARCH_APA_EDITOR');

		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$usedTitle = false;
		$title = trim($publication->title);	
		$title = $html?"<i>$title</i>":$title;
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');			
			$text .= $authorsText; 
		}else{
			$text .= $title;
			$usedTitle = true;
		}
		
		$year = trim($publication->year);
		if($year != '0000' && $year != null){
			$text .= '. ('.$year.')';			
		}

		if(!$usedTitle)
			$text .= '. '.$title;
				
		switch($publication->source_type){
			case 'image':
				$type = JText::_('JRESEARCH_PICTURE');
				break;
			case 'video':
				$type = JText::_('JRESEARCH_MOTION_PICTURE');
				break;
			case 'website': case 'blog':
				$type = '';
				break;
			case 'audio':
				$type = JText::_('JRESEARCH_AUDIO_PODCAST');	
		}	
		
		if(!empty($type))
			$text .= '['.$type.']';
		
		$access_date = trim($publication->access_date);
		$url = trim($publication->url);
		if($html)
			$url = "<a href=\"$url\">$url</a>";
			
		if(!empty($access_date))			
			$retrievedText = JText::sprintf('JRESEARCH_RETRIEVED_WITH_ACCESS_DATE', date('F dS, Y', strtotime($access_date)), $url);
		else
			$retrievedText = JText::sprintf('JRESEARCH_RETRIEVED_WITHOUT_ACCESS_DATE', $url);

		$text .= '. '.$retrievedText;
			
		return $text.'.';
	}
	
	
}


?>