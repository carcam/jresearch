<?php 
/**
 * @version			$Id$
* @package		JResearch
* @subpackage	Citation
 * @copyright		Copyright (C) 2008 Luis Galarraga.
 * @license			GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'apa'.DS.'apa.php');

/**
* Implementation of APA citation style for techreport records.
*
*/
class JResearchAPATechreportCitationStyle extends JResearchAPACitationStyle{
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param $html Add html tags for formats like italics or bold
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = $html?'&amp;':'&';
		$text = '';
		
		$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		$title = trim($publication->title);
		$title = $html?"<i>$title</i>":$title;
		$month = trim($publication->month);
		
		$year = trim($publication->year);
		$letter = isset($publication->__yearLetter)?$publication->__yearLetter:'';		
		if($year == '0000' || $year == null)
			$year = '';			
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');						
			$header = $authorsText;
			if(!empty($year))
				$header .= ". ($year$letter)";
			$header .= 	'. '.$title;
		}else{
			$header = $title;
			if(!empty($year))
				$header .= " ($year$letter)";	
		}
		$text .= $header;
			
		$address = trim($publication->address);
		if(!empty($address))
			$text .= '. '.$address;
		
		$institution = trim($publication->institution);
		if(!empty($institution))
			$text .= ', '.$institution;
			
		return $text.'.';
	}
	
}


?>