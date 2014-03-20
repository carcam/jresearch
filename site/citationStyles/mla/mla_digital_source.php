<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'mla'.DS.'mla.php');
/**
* Implementation of MLA citation style for digital sources.
*
*/
class JResearchMLADigital_sourceCitationStyle extends JResearchMLACitationStyle{
				
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
		$this->lastAuthorSeparator = JText::_('JRESEARCH_AND');
		$nAuthors = $publication->countAuthors();
		$text = '';
				
		if(!$publication->__authorPreviouslyCited){
			if($nAuthors > 0){
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
			}
		}else{
			$authorsText = '---';
		}
		
		$title = '"'.trim($publication->title).'"';
		

		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');
			$header = $authorsText.'. '.$title;
		}else
			$header = $title;	

		$text .= $header;					

		$type = trim($publication->source_type);
		switch($type){
			case 'cdrom':
				$type = JText::_('JRESEARCH_CDROM');
				break;
			case 'film':
				$type = JText::_('JRESEARCH_FILM');
				break;		
		}
		$text .= '. '.$type;
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;
		
		$year = trim($publication->year);
		if($year!= null && $year != '0000')
			$text .= ', '.$year;		
						
		return $text.'.';	
	}
	
	
}
?>
