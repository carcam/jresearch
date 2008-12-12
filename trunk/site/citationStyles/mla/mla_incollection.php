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
* Implementation of MLA citation style for incollection records.
*
*/
class JResearchMLAIncollectionCitationStyle extends JResearchMLACitationStyle{
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param boolean $html Add html tags for formats like italics or bold.
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = JText::_('JRESEARCH_AND');
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		$text = '';
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_APA_EDS').'.':JText::_('JRESEARCH_APA_ED').'.';
		
		if(!$publication->__authorPreviouslyCited){
			if($nAuthors <= 0){
				if($nEditors == 0){
					// If neither authors, nor editors
					$authorsText = '';
					$address = '';
					$editorsText = '';
				}else{
					// If no authors, but editors
					$authorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication, $authorLinks));
					$authorsText .= " ($eds)";
				}
			}else{
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
				$editorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication));
			}
		}else{
			$authorsText = '---';
		}
		
		
		$title = '"'.trim($publication->title).'"';
		$ed = JText::_('JRESEARCH_APA_ED');
		
		$booktitle = trim($publication->booktitle);
		$booktitle = $html?"<u>$booktitle</u>":$booktitle;
		
		if(!empty($authorsText)){
			if(!empty($editorsText))
				$header = "$authorsText. $title. $booktitle. $ed. $editorsText.";
			else
				$header = "$authorsText. $title. $booktitle";	
		}else{
			$header = "$title. $series";	
		}
		$text .= $header;
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;
		

		$year = trim($publication->year);
		if($year != null && $year != '0000')
			$text .= ', '.$year;
			
		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages))
			$text .= '. '.$pages;			

		return $text;
	}
	
}
?>