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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');

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
				
		if(!$publication->__authorPreviouslyCited){
			if($nAuthors > 0){
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
			}
		}else{
			$authorsText = '---';
		}
				
		$title = '"'.trim($publication->title).'"';
		$journal = trim($publication->journal);
		$journal = $html? "<u>$journal</u>":$journal;

		if(!empty($authorsText))
			$header = "$authorsText. $title $edition";
		else
			$header = "$title";	

		if(!empty($publication->volume)){
			$vol = trim($publication->volume);
			if(!empty($publication->number))
				$vol .= '.'.trim($publication->number);
		}
				
		$pages = str_replace('--', '-', $publication->pages);
		if($publication->year!= null && $publication->year != '0000')
			return "$header. $journal $vol ($publication->year): $pages.";
		else
			return "$header. $journal $vol: $pages.";	
	}
	
	
}
?>