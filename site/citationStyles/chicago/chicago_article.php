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
* Implementation of Chicago citation style for article records.
*
*/
class JResearchChicagoArticleCitationStyle extends JResearchChicagoCitationStyle{
		
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
		$titleCons = false;
		
		if($nAuthors <= 0){
			$authorsText = '';
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}

		$title = trim($publication->title);
		
		if(!empty($authorsText))
			$text .= $authorsText;
		else{
			$titleCons = true;
			$text .= $title;
		}	
		
		$year = trim($publication->year);		
		if(!empty($year) && $year != '0000')
			$text .= $text{strlen($text) - 1} == '.'?$year:'. '.$year;			

		if(!$titleCons)	
			$text .= $text{strlen($text) - 1} == '.'?$title:'. '.$title;
		
		
		$journal = trim($publication->journal);			 
		if(!empty($journal)){
			$journal = $html?'<i>'.trim($publication->journal).'</i>':trim($publication->journal);			
			$text .= '. '.$journal;
		}
		
		$volume = trim($publication->volume);
		if(!empty($volume))
			$text .= ' '.$volume;	
		
		$number = trim($publication->number);
			$text .= "($number)";
				
		$pages = str_replace('--', '-', $publication->pages);
		if(!empty($pages))
			$text .= ': '.$pages;		
			
		
		return $text.'.';
	}
	



}
?>