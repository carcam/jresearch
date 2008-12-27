<?php
/**
 * @version		$Id$
 * @package		JResearch
 * @subpackage	Citation
 * @copyright	Copyright (C) 2008 Luis Galarraga.
 * @license		GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'chicago'.DS.'chicago.php');

/**
 * Implementation of Chicago citation style for patents.
 *
 */
class JResearchChicagoPatentCitationStyle extends JResearchChicagoCitationStyle{
	
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
				
		if($nAuthors <= 0){
			$authorsText = '';
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
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
		
		$number = trim($publication->patent_number);
		if(!empty($number))
			$text .= '. '.JText::_('JRESEARCH_PATENT').' '.$number;

		$filing_date = trim($publication->filing_date);
		if(!empty($filing_date))
			$text .= ', '.JText::_('JRESEARCH_FILED').' '.$filing_date;	

		$issue_date = trim($publication->issue_date);
		if(!empty($issue_date))
			$text .= ', '.JText::_('JRESEARCH_AND').' '.JText::_('JRESEARCH_ISSUED').' '.$issue_date;		

		return $text.'.';
	}
	
}
?>