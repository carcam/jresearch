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
* Implementation of MLA citation style for technical reports.
*
*/
class JResearchMLATechreportCitationStyle extends JResearchMLACitationStyle{
		
			
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param boolean $html Add html tags for formats like italics or bold
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorsLinks=false){		
		$this->lastAuthorSeparator = JText::_('JRESEARCH_AND');
		$authors = $publication->getAuthors();
		$n = count($authors);
		$text = '';
		
		// For techreports, authors are usually organizations, so do not extract lastnames
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorsLinks));
		$title = trim($publication->title);
		$title = $html? "<i>$title</i>":$title;

		if(empty($authorsText))
			$head = "$title";
		else{
			$authorsText = rtrim($authorsText, '.');
			$head .= $authorsText.'. '.$title;
		}
		$text .= $head;
			
		$institution = trim($publication->institution);	
		if(!empty($institution))
			$text .= '. '.$institution;
		
		$address = $this->_getAddressText($publication);
		if(!empty($address))
			$text .= !empty($institution)?', '.$address:'. '.$address;
			
		$year = trim($publication->year);	
		if($year != null && $year != '0000')
			$text .= ', '.$year;		

		return $text.'.';	

	}
	
}
?>
