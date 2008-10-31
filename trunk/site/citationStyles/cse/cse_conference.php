<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage		JResearch
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'cse'.DS.'cse.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


/**
* Implementation of CSE citation style for conference records.
*
* @subpackage		JResearch
*/
class JResearchCSEConferenceCitationStyle extends JResearchCSECitationStyle{
	
		
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
			$authorsText = JText::_('JRESEARCH_ANONYMOUS');
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$text .= $authorsText;

		$year = trim($publication->year);
		if(empty($year) || $year == '0000'){		
			if($publication->__sameAuthorAsBefore){
				$text = '. '.$year.$publication->__previousLetter;
			}
		}
				
		$title = trim($publication->title);	
		$text .= '. '.$title.'. '.$in.': ';

		$editors = trim($publication->editor);
		if(!empty($editors)){
			$editorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
			$editorsText .= '. '.$in.' '.$eds;
			$text.= $editorsText;
		}

		$booktitle = trim($publication->booktitle);
		if(!empty($booktitle))		
			$text .= '. '.$booktitle;


		$month = trim($publication->month);
		if(!empty($month))
			$text .= '; '.$month;
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;
			
				
		$pages = str_replace('--', '-', trim($publication->pages));		
		if(!empty($pages))
			$text .= '; p. '.$pages;
		
		$url = trim($publication->url_digital_version);
		if(!empty($url))
			$text .= '. '.JText::_('JRESEARCH_AVAILABLE_FROM').': '.$url;	
			
		return $text;
	}

}
?>