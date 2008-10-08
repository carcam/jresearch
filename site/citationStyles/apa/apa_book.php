<?php
/**
 * @version			$Id$
 * @package			Joomla
 * @subpackage		JResearch	
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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');

/**
* Implementation of APA citation style for book records.
*
* @subpackage		JResearch
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
				if($publication->year != '0000' && $publication->year != null)
					return "<i>$publication->title</i> ($publication->year)";
				else
					return "<i>$publication->title</i>";
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
				if($publication->year != '0000' && $publication->year != null)
					return "$publication->title ($publication->year)";
				else
					return $publication->title;
				
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
				if($publication->year != '0000' && $publication->year != null)
					return "<i>$publication->title</i> ($publication->year)";
				else
					return "<i>$publication->title</i>";
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
		$this->lastAuthorSeparator = '&';
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		
		$eds = $nEditors > 1? JText::_('Eds.'):JText::_('Ed.');

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
		
		$ed = JText::_('ed.');

		$address = $this->_getAddressText($publication);
				
		$edition = trim($publication->edition);
		if(!empty($edition))
			$edition = "($edition $ed)";
		else
			$edition = "";	
		
		$title = $html?"<i>$publication->title</i>":$publication->title;
		
		$year = $publication->year;
		if($year != '0000' && $year != null)
			$year = "($year)";
		else
			$year = '';			
		
		if(!empty($authorsText))
			if(!empty($year))
				$header = "$authorsText. $year. $title $edition";
			else
				$header = "$authorsText. $title $edition";	
		else
			$header = "$title $year";	
		
		return "$header. $address.";
	}
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication){
		return $this->getReference($publication);		
	}
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication, $authorLinks=false){
		return $this->getReference($publication, true, $authorLinks);
	}
	

}

?>