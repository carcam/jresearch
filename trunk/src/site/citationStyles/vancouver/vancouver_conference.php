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
* Implementation of Vancouver citation style for book records.
*
*/
class JResearchVancouverConferenceCitationStyle extends JResearchVancouverCitationStyle{
		
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
		$nEditors = count($publication->getEditors());
		$text = '';
		$editorsConsidered = false;
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_VANCOUVER_EDITORS'):JText::_('JRESEARCH_VANCOUVER_EDITOR');
		
		if($nAuthors <= 0){
			if($nEditors == 0){
				// If neither authors, nor editors
				$authorsText = '';
			}else{
				// If no authors, but editors
				$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
				$authorsText .= ", $eds";
				$editorsConsidered = true;
			}
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		$text .= rtrim($authorsText, '.');		
		
		$title = trim($publication->title);	
		if(!empty($authorsText))
			$text .= '. '.$title;
		else
			$text .= $title;		
			
		$in = JText::_('JRESEARCH_IN');	
		$text .= '. '.$in.': ';	
		
		if(!$editorsConsidered){	
			$editors = $this->getEditorsReferenceTextFromSinglePublication($publication);
			if(!empty($editors)){
				$text .= $editors.', '.JText::_('JRESEARCH_VANCOUVER_EDITORS').'.';
			}
		}
		
		$booktitle = trim($publication->booktitle);
		if(!empty($booktitle))
			$text .= ' '.$booktitle;

		$number = trim($publication->number);
		if(!empty($number))
			$text .= ', '.JText::_('JRESEARCH_ABB_NUMBER').'. '.$number;

		$series = trim($publication->series);
		if(!empty($series)){
			if(!empty($number))
				$text .= ', '.JText::_('JRESEARCH_IN').' '.$series;
			else
				$text .= ', '.$series;				
		}	
			
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '; '.$address;	
			
		
		$year = trim($publication->year);	
		if($year != null && $year != '0000')		
			$text .= '; '.$year;
				
		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages)){
			$text .= '. '.$pages;		
		}
		
		return $text.'.';	
	}
}
?>
