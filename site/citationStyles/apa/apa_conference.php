<?php 
/**
 * @version			$Id$
* @package		J!Research
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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');

/**
* Implementation of APA citation style for conference records.
*
*/
class JResearchAPAConferenceCitationStyle extends JResearchAPACitationStyle{
	
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
			$ed = JText::_('Ed.');
		} 
				
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));

		if(empty($authorsText)){
			$authorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication))." ($eds) ";
		}else{			 
			$editorsText = trim($this->getEditorsReferenceTextFromSinglePublication($publication));
			if(!empty($editorsText))
				$editorsText = " $in $editorsText ($ed), ";
		}
		
		$address = $this->_getAddressText($publication);
		
		
		$pages = str_replace('--', '-', trim($publication->pages));
		if(!empty($pages))
			$pages = "(pp. $pages)";
		
		$booktitle = $html?"<i>$publication->booktitle</i>":$publication->booktitle;
		
		$header = $publication->year != '0000' && $publication->year?"$authorsText ($publication->year)":$authorsText;
		
		return "$header. $publication->title.$editorsText $booktitle $pages. $address.";
		
	}
	
	
}


?>