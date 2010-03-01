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


/**
* Implementation of CSE citation style for proceedings records.
*
*/
class JResearchCSEProceedingsCitationStyle extends JResearchCSECitationStyle{
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
		$in = JText::_('JRESEARCH_IN');
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_LC_EDITORS'):JText::_('JRESEARCH_LC_EDITOR');
		
		if($nAuthors <= 0){
			if($nEditors == 0){
				// If neither authors, nor editors
				$authorsText = '['.JText::_('JRESEARCH_ANONYMOUS').']';
				$editorsText = '';
			}else{
				// If no authors, but editors
				$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
				$authorsText .= ' '.$eds.' ';
			}
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}

		$text .= rtrim($authorsText, '.');

		$year = trim($publication->year);
		if(!empty($year) && $year != '0000'){		
			$text .= '. '.$year;			
			if(isset($publication->__sameAuthorAsBefore)){	
				$text .= $publication->__previousLetter;
			}
		}
				
		$title = trim($publication->title);	
		$text .= '. '.$title;
		
		
		$series = trim($publication->series);
		if(!empty($series)){
			$text .= '. '.$in.': ';			
			$text .= $series;
		}

		$month = trim($publication->month);
		if(!empty($month))
			$text .= '; '.JResearchPublicationsHelper::formatMonth($month);	
		
		$address = trim($publication->address);
		$institution = trim($publication->organization);
		if(!empty($institution)){
			if(!empty($address))
				$address .= ': '.$institution;			
			else
				$address = $institution;	
		}
		
		$text .= '; '.$address;
		return $text.'.';

	}
}
?>