<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'ieee'.DS.'ieee.php');

/**
* Implementation of IEEE citation style for book records.
*
*/
class JResearchIEEEBookCitationStyle extends JResearchIEEECitationStyle{
	
		
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
		
		$eds = count($publication->getEditors())>1?JText::_('JRESEARCH_APA_EDS_LOWER'):JText::_('JRESEARCH_APA_ED_LOWER');
		
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
		}
		
		$ed = JText::_('JRESEARCH_APA_EDITOR_LOWER');
		
		$title = trim($publication->title);	
		$title = $html?"<i>$title</i>":$title;
		
		if(!empty($authorsText))
			$header = "$authorsText. $title";
		else
			$header = $title;	
		
		$edition = trim($publication->edition); 
		if(!empty($edition))
			$header .= ". $edition $ed";

		$volume = trim($publication->volume);
		if(!empty($volume))
			$header .= ', '.JText::_('JRESEARCH_VOL_LOWER').'. '.$volume;

			
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$header .= ", $address";
	
		$year = trim($publication->year);		
		if($year != null && $year != '0000')		
			return "$header, $year.";
		else
			return $header.'.';	

	}
	

}
?>