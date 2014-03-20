<?php
/**
 * @version			$Id$
 * @package			JResearch
 * @subpackage		Citation
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
* Implementation of APA citation style for digital sources.
*
*/
class JResearchAPADigital_sourceCitationStyle extends JResearchAPACitationStyle{
	
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param $html Add html tags for formats like italics or bold
	* @param boolean $authorLinks If true, internal authors profile links will be included.
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = $html?'&amp;':'&';
		$nAuthors = $publication->countAuthors();
		$text = '';

		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
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
			$text .= '. ('.$year.$letter.')';			
		}

		if(!$usedTitle)
			$text .= '. '.$title;
				
		switch($publication->source_type){
			case 'cdrom':
				$type = JText::_('JRESEARCH_CD');
				break;
			case 'film':
				$type = JText::_('JRESEARCH_MOTION_PICTURE');
				break;	
		}	
		
		if(!empty($type))
			$text .= ' ['.$type.']';
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;
			
		return $text.'.';
	}
	
	
}


?>