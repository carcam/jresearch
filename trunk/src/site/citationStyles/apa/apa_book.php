<?php
/**
 * @version			$Id$
* @package		JResearch
* @subpackage	Citation
 * @copyright		Copyright (C) 2008 Luis Galarraga.
 * @license			GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'apa'.DS.'apa.php');

/**
* Implementation of APA citation style for book records.
*
*/
class JResearchAPABookCitationStyle extends JResearchAPACitationStyle{
	

	
	/**
	* Takes a publication and returns the HTML string output that would be printed when citing the work 
	* in a non­parenthetical way.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getCitationHTMLText($publication){
		if($publication instanceof JResearchPublication){
			if($publication->countAuthors() == 0){
				$year = trim($publication->year);
				$title = trim($publication->title);
				if($year != '0000' && $year != null)
					return "<i>$title</i> ($year)";
				else
					return "<i>$title</i>";
			}
		}
		
		$citation = parent::getCitationHTMLText($publication);		
		return $citation;
	}
	
	
	
	/**
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way. It means, the author is subject nor object in 
	* the sentence containing the cite.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/  
	function getParentheticalCitationText($publication){
		if($publication instanceof JResearchPublication){
			if($publication->countAuthors() == 0){
				$year = trim($publication->year);
				$title = trim($publication->title);				
				if($year != '0000' && $year != null)
					return "$title ($year)";
				else
					return $title;
				
			}
		}
		$citation = parent::getParentheticalCitationText($publication);		
		return $citation;
	}
	
	/**
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way. It means, the author is subject nor object in 
	* the sentence containing the cite.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/  
	function getParentheticalCitationHTMLText($publication){
		if($publication instanceof JResearchPublication){
			if($publication->countAuthors() == 0){
				$year = trim($publication->year);
				$title = trim($publication->title);				
				if($year != '0000' && $year != null)
					return "<i>$title</i> ($year)";
				else
					return "<i>$title</i>";
			}
		}
		$citation = parent::getParentheticalCitationText($publication);		
		return $citation;
	}
	
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param $html Add html tags for formats like italics or bold
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = $html?'&amp;':'&';
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		$text = '';
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_APA_EDITORS'):JText::_('JRESEARCH_APA_EDITOR');

		if($nAuthors <= 0){

			if($nEditors == 0){
				// If neither authors, nor editors
				$authorsText = '';
				$address = '';
				$editorsText = '';
			}else{
				// If no authors, but editors
				$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
				$authorsText .= " ($eds)";
			}
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$ed = JText::_('JRESEARCH_APA_ED_LOWER');
		
		$usedTitle = false;
		$title = trim($publication->title);	
		$title = $html?"<i>$title</i>":$title;
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');			
			$text .= $authorsText; 
		}else{
			$text .= $title;
			$usedTitle = true;
		}
		
		$letter = isset($publication->__yearLetter)?$publication->__yearLetter:'';		
		$year = trim($publication->year);
		if($year != '0000' && $year != null){
			$year = "($year$letter)";
			$text .= '. '.$year;
		}

		if(!$usedTitle)
			$text .= '. '.$title;
				
		$edition = trim($publication->edition);
		if(!empty($edition)){
			$edition = "($edition $ed)";
			$text .= ' '.$edition;
		}
			
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;		
		
		return $text.'.';
	}

}

?>