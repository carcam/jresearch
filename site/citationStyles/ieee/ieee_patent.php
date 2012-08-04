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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'ieee'.DS.'ieee.php');

/**
 * Implementation of IEEE citation style for patents.
 *
 */
class JResearchIEEEPatentCitationStyle extends JResearchIEEECitationStyle{
	
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
		
		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		$title = '"'.trim($publication->title).'",';	

		if(!empty($authorsText))
			$header = "$authorsText. $title";
		else
			$header = $title;			
		
		$number = trim($publication->patent_number);
		if(!empty($number))
			$header .= ' '.JText::_('JRESEARCH_PATENT').' '.$number;
		
		$issue_date = trim($publication->issue_date);
		if(!empty($issue_date)){
			$header = rtrim($header, ',');
			$header .= ', '.$issue_date;		
		}
	
		return $header.'.';	
			
	}
}
?>