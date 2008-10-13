<?php 
/**
 * @version			$Id$
 * @package			Joomla
 * @subpackage		JResearch	
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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');

/**
* Implementation of APA citation style for manual records.
*
* @subpackage		JResearch
*/
class JResearchAPAInbookCitationStyle extends JResearchAPACitationStyle{
	
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
		$this->lastAuthorSeparator = '&';
		$in = JText::_('In');
		if(count($publication->getEditors()) > 1){
			$ed = JText::_('Eds.');
		}else{
			$ed = JText::_('Ed. ');
		} 
				
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));

		if(empty($authorsText)){
			$authorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication))." ($eds)";
		}else{			 
			$editorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication));
			if(!empty($editorsText))
				$editorsText = "$in $editorsText ($ed),";
		}
		
		$title = $html?"<i>$publication->title</i>":$publication->title;
		$title = trim($title);
		
		$year = $publication->year;
		if($year != '0000' && $year != null)
			$year = " ($year)";
		else
			$year = '';			
		
		if(empty($authorsText))
			$header = "$title$year";
		else
			$header = "$authorsText$year. $publication->title"; 	
		
		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages))
			$pages = "(pp. $publication->pages)";


		$address = $this->_getAddressText($publication);
			
		return "$header. $editorsText $pages. $address.";
	}
	
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication){
		return $this->getReference($publication);		
	}
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication, $authorLinks=false){
		return $this->getReference($publication, true, $authorLinks);
	}
	
}


?>