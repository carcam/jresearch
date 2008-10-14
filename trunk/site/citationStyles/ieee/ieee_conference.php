<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage		JResearch
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'ieee'.DS.'ieee.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


/**
* Implementation of IEEE citation style for conference records.
*
* @subpackage		JResearch
*/
class JResearchIEEEConferenceCitationStyle extends JResearchIEEECitationStyle{
	

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
		return $this->getReference($publication, true);
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
		$nAuthors = $publication->countAuthors();

		$authorsText = '';
		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		
		$title = '"'.trim($publication->title).'",';	
		
		if(!empty($authorsText))
			$header = "$authorsText. $title";
		else
			$header = $title;	
		
		$booktitle = trim($publication->booktitle);
		if(!empty($booktitle))
			$header .= " in ".($html?"<i>$booktitle</i>":$booktitle);	
			
		$edition = trim($publication->edition); 
		if(!empty($edition))
			$header .= ", $edition $ed";

					
		$volume = trim($publication->volume);
		if(!empty($volume))
			$header .= ', '.JText::_('Vol.').' '.$volume;
				
		if($publication->year != null && $publication->year != '0000')		
			$header .= ', '.$publication->year;
			
		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages))
			$header .= ', pp. '.$pages;	
			
		return $header;
	}
	

}

?>