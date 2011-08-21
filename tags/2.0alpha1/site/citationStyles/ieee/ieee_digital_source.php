<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'ieee'.DS.'ieee.php');


/**
* Implementation of IEEE citation style for digital sources.
*
*/
class JResearchIEEEDigital_sourceCitationStyle extends JResearchIEEECitationStyle{
	
		
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
		$nAuthors = $publication->countAuthors();
		
		$authorsText = '';
		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$title = trim($publication->title);	
		$title = '"'.$title.'"';
				
		if(!empty($authorsText))
			$header = rtrim($authorsText, '.').'. '.$title;
		else
			$header = $title;	

		
		switch($publication->source_type){
			case 'cdrom':
				$type = JText::_('JRESEARCH_CDROM');
				break;
			case 'film':
				$type = JText::_('JRESEARCH_FILM');
				break;
		}	
		
		if(!empty($type))
			$header .= '. ['.$type.']';
					
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$header .= '. '.$address;
		
		if($year != null && $year != '0000'){
			$header .= '. '.$year;
		}	
		
		return $header.'.';	

	}

}
?>