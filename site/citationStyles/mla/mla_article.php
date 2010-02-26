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
* Implementation of MLA citation style for article records.
*
*/
class JResearchMLAArticleCitationStyle extends JResearchMLACitationStyle{
				
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
				
		if(!isset($publication->__authorPreviouslyCited)){
			if($nAuthors > 0){
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
			}
		}else{
			$authorsText = '---';
		}
		
		$title = '"'.trim($publication->title).'"';
		

		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');
			$text .= $authorsText.'. '.$title;
		}else
			$text .= $title;	

		$journal = trim($publication->journal);
		if(!empty($journal)){
			$journal = $html? "<u>$journal</u>":$journal;			
			$text .= '. '.$journal;
		}
		$volume = trim($publication->volume);	
		if(!empty($volume)){
			$number = trim($publication->number);
			if(!empty($number))
				$volume .= '.'.$number;

			$text .= ' '.$volume;	
		}
						

		$year = trim($publication->year);
		if($year!= null && $year != '0000')
			$text .= " ($year)";		
		
		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages))
			$text .= ': '.$pages;
			
		return $text.'.';	
	}
	
	
}
?>
