<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'chicago'.DS.'chicago.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


/**
* Implementation of Chicago citation style for manual records.
*
*/
class JResearchChicagoManualCitationStyle extends JResearchChicagoCitationStyle{
		
		
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

		$title = $html?'<i>'.trim($publication->title).'</i>':trim($publication->title);
		
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
			
		$edition = trim($publication->edition);
		if(!empty($edition)){
			$ed = JText::_('JRESEARCH_APA_EDITOR_LOWER');
			$text .= '. '.$edition.' '.$ed;	
		}
		
		$adr = trim($publication->address);
		if(!empty($adr))
			$text .= '. '.$adr;
		
		$publ = trim($publication->organization);	
		if(!empty($adr)){
			if(!empty($publ))
				$text .= " : $publ";		
		}else{
			if(!empty($publ))
				$text .= '. '.$publ;
		}	
		
		$month = trim($publication->month);
		if(!empty($month))
			$text .= ', '.$month;
			
		return $text.'.';
	}
	


}
?>