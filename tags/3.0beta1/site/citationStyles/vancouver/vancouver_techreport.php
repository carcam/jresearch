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
* Implementation of Vancouver citation style for techreport records.
*
*/
class JResearchVancouverTechreportCitationStyle extends JResearchVancouverCitationStyle{
		
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
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_VANCOUVER_EDITORS'):JText::_('JRESEARCH_VANCOUVER_EDITOR');
		
		if($nAuthors <= 0){
			if($nEditors == 0){
				// If neither authors, nor editors
				$authorsText = '';
			}else{
				// If no authors, but editors
				$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
				$authorsText .= ", $eds";
			}
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		$text .= rtrim($authorsText, '.');		
		
		$title = $html?"<i>".trim($publication->title)."</i>":trim($publication->title);	
		if(!empty($authorsText))
			$text .= '. '.$title;
		else
			$text .= $title;				
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;	
			
			
		$institution = trim($publication->institution);
		if(!empty($institution))
			$text .= '. '.$institution;	
					
		$year = trim($publication->year);	
		if($year != null && $year != '0000')		
			$text .= '; '.$year;

		$number = trim($publication->number);
		if(!empty($number))
			$text .= '. '.$number;
			
		return $text.'.';	
	}
}
?>
