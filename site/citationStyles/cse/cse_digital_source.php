<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'cse'.DS.'cse.php');

/**
* Implementation of CSE citation style for digital sources.
*
*/
class JResearchCSEDigital_sourceCitationStyle extends JResearchCSECitationStyle{
	
		
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param boolean $html Add html tags for formats like italics or bold
	* @param boolean $authorPreviouslyCited If true
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = JText::_('JRESEARCH_BIBTEXT_AUTHOR_SEP');
		$nAuthors = $publication->countAuthors();
		$text = '';
		
		if($nAuthors <= 0){
			$authorsText = '['.JText::_('JRESEARCH_ANONYMOUS').']';
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		

		$text .= rtrim($authorsText, '.');

		$year = trim($publication->year);
		if(!empty($year) && $year != '0000'){		
			$text .= '. '.$year;			
			if(isset($publication->__sameAuthorAsBefore)){	
				$text .= $publication->__previousLetter;
			}
		}
		
		$title = trim($publication->title);	
		$text .= '. '.$title;
		
		switch($publication->online_source_type){
			case 'cdrom':
				$type = JText::_('JRESEARCH_CDROM');
				break;
			case 'film':
				$type = JText::_('JRESEARCH_FILM');
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