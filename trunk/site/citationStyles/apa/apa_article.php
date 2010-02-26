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
* Implementation of APA citation style for article records.
*
*/
class JResearchAPAArticleCitationStyle extends JResearchAPACitationStyle{
	
	
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param $html Add html tags for formats like italics or bold
	* @param boolean $authorLinks If true, internal authors profile links will be included.
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$this->lastAuthorSeparator = $html?'&amp;':'&';		
		$text = '';
		$header = parent::getReference($publication, false, $authorLinks);

		$numberText = trim($publication->number);
		$numberText = !empty($numberText)?"($numberText)":"";
		$journal = trim($publication->journal);
		$journal = $html?"<i>$journal</i>":$journal;
		$volume = trim($publication->volume);
		$volume = $html?"<i>$volume</i>":$volume;
		$pages = str_replace('--', '-', trim($publication->pages));
		
		$text.= $header;
		if(!empty($journal))
			$text .= ' '.$journal;
		
		if(!empty($volume))
			$text .= ', '.$volume;
		if(!empty($numberText))
			$text .= $numberText;

		if(!empty($pages))
			$text .= ', '.$pages;	
		
		return $text.'.';
	}
	
	
}


?>