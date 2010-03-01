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
* Implementation of Vancouver citation style for inbook records.
*
*/
class JResearchVancouverInbookCitationStyle extends JResearchVancouverCitationStyle{
		
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
		$editorsConsidered = false;
		
		if($nAuthors <= 0){
			$authorsText = '';
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$text .= rtrim($authorsText, '.');		

		$chapter = trim($publication->chapter);
		if(!empty($chapter))
			$text .= '. '.$chapter;
		
		$in = JText::_('JRESEARCH_IN');				
		$text .= '. '.$in.': ';			
		
		$editors = $this->getEditorsReferenceTextFromSinglePublication($publication);
		if(!empty($editors)){			
			$text .= $editors.', '.$eds.'.';	
		}
		
		$title = trim($publication->title);	
		if(!empty($title)){		
			$text .= ' '.$title;					
		}
		
		$ed = JText::_('JRESEARCH_APA_EDITOR_LOWER').'.';		
		$edition = trim($publication->edition); 
		if(!empty($edition)){
			$edition = "$edition $ed";
			$text.= '. '.$edition;
		}

		$address = $this->_getAddressText($publication);
		if(!empty($address)){
			if($text{strlen($text) - 1}  == '.')
				$text .= ' '.$address;
			else	
				$text .= '. '.$address;	
		}
		
		$year = trim($publication->year);	
		if($year != null && $year != '0000')		
			$year = '; '.$year;

		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages)){
			$text .= '. '.$pages;		
		}
			
		return $text.'.';	
	}
}
?>
