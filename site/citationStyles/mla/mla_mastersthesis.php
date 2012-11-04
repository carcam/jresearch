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
* Implementation of MLA citation style for master thesis records.
*
*/
class JResearchMLAMastersthesisCitationStyle extends JResearchMLACitationStyle{
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication){
		return $this->getReference($publication);
	}
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication, $authorLinks=false){
		return $this->getReference($publication, true, $authorLinks);
	}
	
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
			if($nAuthors <= 0){
				$authorsText = '';
			}else{
				$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
			}
		}else{
			$authorsText = '---';
		}

		$address = $this->_getAddressText($publication);
		
		$title = '"'.$publication->title.'"';

		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');
			$text .= $authorsText.'. '.$title;
		}else{
			$text .= $title;	
		}
		
		$type = trim($publication->type);
		if(!empty($type))
			$text .= '. '.$type;
		
		$school = trim($publication->school);
		if(!empty($school))
			$text .= '. '.$school;

		$year = trim($publication->year);			
		if($year != null && $year != '0000')		
			$text .= ', '.$year;
			
		return $text.'.';
	}
	
	
}
?>
