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
					$authorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication));
					$authorsText .= " ($eds)";
				}
			}else{
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
				$editorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication));
			}
		}else{
			$authorsText = '---';
		}

		$address = $this->_getAddressText($publication);
		
		$booktitle = trim($publication->booktitle);
		$booktitle = $html?"<u>$booktitle</u>":$booktitle;
		
		$title = '"'.trim($publication->title).'"';

		if(!empty($authorsText)){
			if(!empty($editorsText))
				$header = "$authorsText. $title. $booktitle. Ed. $editorsText.";
			else
				$header = "$authorsText. $title. $booktitle";	
		}else{
			$header = "$title. $booktitle";	
		}
		
		$pages = str_replace('--', '-', trim($publication->pages));
		
		if($publication->year != null && $publication->year != '0000')
			return "$header. $address, $publication->year. $pages";
		else
			return "$header. $address. $pages";	
	}
	
}
?>