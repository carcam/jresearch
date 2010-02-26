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
* Implementation of Chicago citation style for conference records.
*
*/
class JResearchChicagoConferenceCitationStyle extends JResearchChicagoCitationStyle{
		
		
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param boolean $html Add html tags for formats like italics or bold
	* @param boolean $authorLinks If true, internal authors profile links will be included.
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = JText::_('JRESEARCH_BIBTEXT_AUTHOR_SEP');
		$nAuthors = $publication->countAuthors();
		$text = '';
		$in = JText::_('JRESEARCH_IN');
		$nEditors = count($publication->getEditors());
		$eds = $nEditors > 1? JText::_('JRESEARCH_LC_EDITORS'):JText::_('JRESEARCH_LC_EDITOR');
						
		if($nAuthors <= 0){
			if($nEditors == 0){
				// If neither authors, nor editors
				$authorsText = '';
				$editorsText = '';
			}else{
				// If no authors, but editors
				$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
				$authorsText .= ' '.$eds.' ';
			}
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
			$editorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
		}

		$title = trim($publication->title);
		
		if(!empty($authorsText))
			$text .= $authorsText;
		else{
			$titleCons = true;
			$text .= $title;
		}	
		
		$year = trim($publication->year);		
		if(!empty($year) && $year != '0000')
			$text .= '. '.$year;			

		if(empty($titleCons))	
			$text .= '. '.$title;
				
		$booktitle = trim($publication->booktitle);
		if(!empty($booktitle))
			$text .= '. '.$in.' '.($html?'<i>'.$booktitle.'</i>':$booktitle);			
		
		if(!empty($editorsText)){
			$ed = JText::_('JRESEARCH_APA_EDITOR_LOWER');
			$text .= ', '.$ed.'. '.$editorsText;
		}	
		
		$series = trim($publication->series);
		if(!empty($series)){
			$text .= '. '.$series;
		}
		
		$address = $this->_getAddressText($publication);
		if(!empty($address)){
			$text .= '. '.$address;
		}
		
		$month = JResearchPublicationsHelper::formatMonth(trim($publication->month));
		if(!empty($month)){
			$text .= ', '.$month;
		}
		
		return $text.'.';
	}
	


}
?>