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
* Implementation of MLA citation style for book records.
*
*/
class JResearchMLABookCitationStyle extends JResearchMLACitationStyle{
		
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
		$this->lastAuthorSeparator = JText::_('JRESEARCH_AND');
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_APA_EDS').'.':JText::_('JRESEARCH_APA_ED').'.';
		$text = '';
		
		if(!isset($publication->__authorPreviouslyCited)){
			if($nAuthors <= 0){
				if($nEditors == 0){
					// If neither authors, nor editors
					$authorsText = '';
					$address = '';
					$editorsText = '';
				}else{
					// If no authors, but editors
					$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
					$authorsText .= " ($eds)";
				}
			}else{
				$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
			}
		}else{
			$authorsText = '---';
		}
		
		$ed = JText::_('JRESEARCH_APA_EDITOR_LOWER');
		
		$title = trim($publication->title);	
		$title = $html?"<u>$title</u>":$title;
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');
			$header = $authorsText.'. '.$title;
		}else
			$header = $title;	

		$text .= $header;
		
		$edition = trim($publication->edition); 
		if(!empty($edition)){
			$edition = "($edition $ed)";
			$text .= ' '.$edition;
		}
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;

		$year = trim($publication->year);		
		if($year != null && $year != '0000')	
			$text .= ', '.$year;	

		return $text.'.';	
	}
	



}
?>
