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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


/**
* Implementation of IEEE citation style for proceedings records.
*
*/
class JResearchIEEEProceedingsCitationStyle extends JResearchIEEECitationStyle{
	

	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication){
		return $this->getReference($publication);
	}
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication, $authorLinks=false){
		return $this->getReference($publication, true, $authorLinks);
	}
	
		
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
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		$eds = $nEditors > 1? JText::_('JRESEARCH_APA_EDS_LOWER'):JText::_('JRESEARCH_APA_ED_LOWER');
		
		if($nAuthors <= 0){
			if($nEditors == 0){
				// If neither authors, nor editors
				$authorsText = '';
				$address = '';
				$editorsText = '';
			}else{
				// If no authors, but editors
				$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
				$authorsText .= " $eds";

			}
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
			$editorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);			
		}
		
		$title = trim($publication->title);	
		$title = $html?"<i>$title</i>":$title;
			
		if(!empty($authorsText))
			$header = "$authorsText. $title";
		else
			$header = $title;	

		if(!empty($editorsText))
			$header .= '. '.$editorsText.' '.$eds;				
			
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$header .= ". $address";
	
				
		$year = trim($publication->year);	
		if($year != null && $year != '0000')		
			$header .= '. '.$year;
			
		return $header.'.';
				
			
	}

}
?>