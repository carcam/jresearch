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
* Implementation of IEEE citation style for conference records.
*
*/
class JResearchIEEEConferenceCitationStyle extends JResearchIEEECitationStyle{
		
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

		$title = '"'.trim($publication->title).'"';	
		
		if(!empty($authorsText))
			$header = "$authorsText. $title";
		else
			$header = $title;	
		

		$booktitle = trim($publication->booktitle);
		if(!empty($booktitle))
			$header .= $html?". <i>$booktitle</i>":'. '.$booktitle;	
			
		if(!empty($editorsText))
			$header .= '. '.$editorsText.' '.$eds;
								
		$year = trim($publication->year);	
		if($year != null && $year != '0000')		
			$header .= '. '.$year;
			
		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages))
			$header .= '. pp. '.$pages;	
			
			
		return $header.'.';
	}
	

}

?>