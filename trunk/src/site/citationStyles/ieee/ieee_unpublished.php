<?php

/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'ieee'.DS.'ieee.php');


/**
* Implementation of IEEE citation style for techreport records.
*
*/
class JResearchIEEEUnpublishedCitationStyle extends JResearchIEEECitationStyle{
		
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
		
		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		$title = '"'.trim($publication->title).'"';	

		if(!empty($authorsText))
			$header = "$authorsText. $title";
		else
			$header = $title;			

		$month = trim($publication->month);	
		$year = trim($publication->year);	
		if($year != null && $year != '0000'){		
			if(!empty($month))
				$header .=  '. '.JResearchPublicationsHelper::formatMonth($month, true);
			$header .= ". $year";	
		}
	
		return $header.'.';	
				
			
	}
}

?>