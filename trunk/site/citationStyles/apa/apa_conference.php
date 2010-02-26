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
* Implementation of APA citation style for conference records.
*
*/
class JResearchAPAConferenceCitationStyle extends JResearchAPACitationStyle{
	
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
		if(count($publication->getEditors()) > 1){
			$ed = JText::_('JRESEARCH_APA_EDITORS');
		}else{
			$ed = JText::_('JRESEARCH_APA_EDITOR');
		} 
		$text = '';
				
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));

		if(empty($authorsText)){
			$authorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication))." ($eds) ";
		}
		
		$authorsText = rtrim($authorsText, '.');
		$title = trim($publication->title);

				
		$year = trim($publication->year);
		$letter = isset($publication->__yearLetter)?$publication->__yearLetter:'';		
		$header = $year != '0000' && $year?"$authorsText ($year$letter)":$authorsText;
		
		$text .= $header;
		if(!empty($text))
			$text .= '. '.$title;
		else
			$text .= $title;

		$text .= '. '.$in;	
		$editorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication));
		if(!empty($editorsText)){
			$editorsText = "$editorsText ($ed)";
			$text .= ' '.$editorsText;
		}
		
		$booktitle = trim($publication->booktitle);				
		if(!empty($booktitle)){
			$booktitle = $html?"<i>$booktitle</i>":$booktitle;
			if(!empty($editorsText))
				$text .= ', '.$booktitle;
			else
				$text .= ' '.$booktitle;		
		}
		
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