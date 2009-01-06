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
* Implementation of CSE citation style for inbook records.
*
*/
class JResearchCSEInbookCitationStyle extends JResearchCSECitationStyle{
	
		
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
		$nEditors = count($publication->getEditors());
		$text = '';
		$editorsConsidered = false;
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_LC_EDITORS'):JText::_('JRESEARCH_LC_EDITOR');
		$in = JText::_('JRESEARCH_IN');
		
		if($nAuthors <= 0){
			if($nEditors == 0){
				// If neither authors, nor editors
				$authorsText = '['.JText::_('JRESEARCH_ANONYMOUS').']';
				$editorsText = '';
			}else{
				// If no authors, but editors
				$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
				$authorsText .= ' '.$eds.' ';
				$editorsConsidered = true;
			}
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


		if(!$editorsConsidered){
			$editorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
			$editorsText .= ' '.$eds;
			$text.= '. '.$in.': '.$editorsText;
		}

		$edition = trim($publication->edition);
		$ed = JText::_('JRESEARCH_APA_ED_LOWER');
		if(!empty($edition)){
			$text .= '. '.$edition.' '.$ed;
		}

		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;

		$pages = str_replace('--', '-', trim($publication->pages));		
		if(!empty($pages))
			$text .= '. p '.$pages;
		
		return $text.'.';
	}
	



}
?>
