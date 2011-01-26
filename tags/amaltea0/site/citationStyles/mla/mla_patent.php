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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'mla'.DS.'mla.php');

/**
 * Implementation of MLA citation style applied to patents.
 *
 */
class JResearchMLAPatentCitationStyle extends JResearchMLACitationStyle{
	
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = JText::_('JRESEARCH_AND');
		$nAuthors = $publication->countAuthors();
		$text = '';		
		
		if(!isset($publication->__authorPreviouslyCited)){
			if($nAuthors <= 0){
				$authorsText = '';
			}else{
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
			}
		}else{
			$authorsText = '---';
		}

		$address = $this->_getAddressText($publication);
		
		$title = '"'.trim($publication->title).'"';

		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');
			$text .= $authorsText.'. '.$title;
		}else{
			$text .= $title;	
		}
		
		$number = trim($publication->patent_number);
		if(!empty($number))
			$text .= '. '.JText::_('JRESEARCH_PATENT').' '.$number;

		$date = trim($publication->issue_date);
		if(!empty($date))
			$text .= '. '.$date;	

		return $text.'.';	
	}
	
}
?>