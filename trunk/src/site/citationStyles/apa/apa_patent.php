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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'apa'.DS.'apa.php');

/**
 * Implementation of APA citation style applied to patents.
 *
 */
class JResearchAPAPatentCitationStyle extends JResearchAPACitationStyle{
	
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = $html?'&amp;':'&';
		$text = '';
				
		$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);

		$year = trim($publication->year);
		$letter = isset($publication->__yearLetter)?$publication->__yearLetter:'';
		if($year != '0000' && $year != null)
			$year = " ($year$letter)";
		else
			$year = '';			
		
		if(!empty($authorsText)){
			$text = rtrim($authorsText, '.');						
			if(!empty($year))		
				$text .= '.'.$year;
			$text .= '. '.trim($publication->title);	
		}else{			
			$text = trim($publication->title);
			if(!empty($year))		
				$text .= '.'.$year;
			
		}
				
		$number = trim($publication->patent_number);
		if(!empty($number))
			$text .= '. '.JText::_('JRESEARCH_PATENT').' '.JText::_('JRESEARCH_ABB_NUMBER').'. '.$number;

		$address = trim($publication->address);
		if(!empty($address))
			$text .= '. '.$address;	
				
		
		$office	= trim($publication->office);
		if(!empty($office)){
			if(!empty($country))
				$text .= ': '.$office;
			else
				$text .= '. '.$office;	
		}
					
		return $text.'.';
	}
	
}
?>