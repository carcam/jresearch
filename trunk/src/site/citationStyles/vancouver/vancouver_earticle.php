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

/**
* Implementation of APA citation style for electronic articles.
*
*/
class JResearchVancouverEarticleCitationStyle extends JResearchAPACitationStyle{
	
	
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

		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$usedTitle = false;
		$usedJournal = false;
		$title = trim($publication->title);	
		$journal = trim($publication->journal);
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');			
			$text .= $authorsText; 
		}else{
			if(empty($journal)){
				$text .= $title;
				$usedTitle = true;
			}else{
				$text .= $journal;	
				$usedJournal = true;	
			}
		}

		if(!$usedTitle)
			$text .= '. '.$title;

		if(!$usedJournal){	
			$journal = $html?"<i>".$journal."</i>":$journal;
			$text .= '. '.$journal;			
		}
		
		$text .= '. ['.JText::_('JRESEARCH_ONLINE').']';
		
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
			$text .= ' '.$date;
		}	
			
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
