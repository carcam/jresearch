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
* Implementation of APA citation style for manual records.
*
*/
class JResearchAPAInbookCitationStyle extends JResearchAPACitationStyle{
	
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
		$in = JText::_('JRESEARCH_IN');
		$text = ''; 
		$titleUsed = false;
				
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
		$authorsText = rtrim($authorsText, '.');					
		if(empty($authorsText)){
			$authorsText = trim($publication->title);
			$titleUsed = true;
		}
		
		$text .= $authorsText;
		
		$letter = isset($publication->__yearLetter)?$publication->__yearLetter:'';		
		$year = trim($publication->year);
		if($year != '0000' && $year != null){
			$year = " ($year$letter)";
			$text .= $year;				
		}
		

		if(!$titleUsed){
			$title = trim($publication->title);
			$title = $html?"<i>$title</i>":$title;
			$text .= '. '.$title;
		}
		
		$chapter = trim($publication->chapter);
		if(!empty($chapter))
			$text .= ', '.JText::_('JRESEARCH_CHAPTER_LOWER').' '.$chapter;
					
		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages)){
			$text .= ', '.JText::_('JRESEARCH_PAGES_LOWER').' '.$pages;	
		}
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;
			
		return $text.'.';
	}
	
}


?>