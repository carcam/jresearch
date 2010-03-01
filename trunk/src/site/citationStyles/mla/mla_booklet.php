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
* Implementation of MLA citation style for booklet records.
*
*/
class JResearchMLABookletCitationStyle extends JResearchMLACitationStyle{
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
		$this->lastAuthorSeparator = $html?'&amp;':'&';
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		$text = '';
		
		$eds = $nEditors > 1? JText::_('Eds.'):JText::_('Ed.');
		
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
		
		$title = trim($publication->title);
		$title = $html?"<u>$title</u>":$title;
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');
			$header = $authorsText.'. '.$title;
		}else
			$header = $title;	

		$text .= $header;	
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= '. '.$address;
						
		$year = trim($publication->year);		
		if($year != null && $year != '0000')		
			$text .= ", $year";

		return $text.'.';	
	}


}
?>
