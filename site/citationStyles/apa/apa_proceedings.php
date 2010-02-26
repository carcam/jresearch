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
* Implementation of APA citation style for Proceedings records.
*
*/
class JResearchAPAProceedingsCitationStyle extends JResearchAPACitationStyle{
	
		
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

		if($nEditors <= 0){

			if($nAuthors == 0){
				// If neither authors, nor editors
				$authorsText = '';
				$address = '';
				$editorsText = '';
			}else{
				// If no authors, but editors
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
			}
		}else{
			$authorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication));
			$authorsText .= ", $eds";
		}
						
		$title = trim($publication->title);
		$title = $html?"<i>$title</i>":$title;

		$letter = isset($publication->__yearLetter)?$publication->__yearLetter:'';		
		$year = trim($publication->year);
		if($year != '0000' && $year != null)
			$year = " ($year$letter)";
		else
			$year = '';			
		
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');						
			if(!empty($year))
				$header = "$authorsText.$year. $title";
			else
				$header = "$authorsText. $title";
		}else
			$header = "$title.$year";	
		
		$text .= $header;	
		
		$number = trim($publication->number);
		if(!empty($number))
			$text .= ', '.JText::_('JRESEARCH_NUMBER_LOWER').' '.$number;

		$series = trim($publication->series);
		if(!empty($series)){
			if(!empty($number))
				$text .= ', '.JText::_('JRESEARCH_IN').' '.$series;
			else
				$text .= ', '.$series;				
		}
		
		$address = trim($publication->address);	
		if(!empty($address))
			$text .= ', '.$address;

		$publisher = trim($publication->publisher);
		if(!empty($publisher))
			$text .= ', '.$publisher;

		$organization = trim($publication->organization);
		if(!empty($organization))
			$text .= ', '.$organization;
			
		return $text.'.';
	}
	
}


?>