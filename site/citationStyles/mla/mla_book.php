<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Citation
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'mla'.DS.'mla.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


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
		$this->lastAuthorSeparator = 'and';
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		
		$eds = $nEditors > 1? JText::_('Eds.'):JText::_('Ed.');
		
		if(!$publication->__authorPreviouslyCited){
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
		
		$ed = JText::_('ed.');

		$address = $this->_getAddressText($publication);
		
		$edition = trim($publication->edition); 
		if(!empty($edition))
			$edition = "($publication->edition $ed)";
		else
			$edition = "";	
		
		$title = trim($publication->title);	
		$title = $html?"<u>$title</u>":$title;
		
		if(!empty($authorsText))
				$header = "$authorsText. $title $edition";
		else
				$header = "$title ";	
		
		if($publication->year != null && $publication->year != '0000')		
			return "$header. $address, $publication->year";
		else
			return "$header. $address";	
	}
	



}
?>