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
				
		$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);

		$year = trim($publication->year);
		if($year != '0000' && $year != null)
			$year = " ($year)";
		else
			$year = '';			
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');						
			if(!empty($year))		
				$text .= "$authorsText.$year";
		}
		
		$number = trim($publication->patent_number);
		if(!empty($number))
			$text .= '. '.JText::_('JRESEARCH_PATENT').' '.JText::_('JRESEARCH_ABB_NUMBER').'. '.$number;

		$country = trim($publication->country);
		if(!empty($country))
			$text .= '. '.$country;	
		
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