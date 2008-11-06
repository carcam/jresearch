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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');

/**
* Implementation of APA citation style for phd thesis records.
*
*/
class JResearchAPAPhdthesisCitationStyle extends JResearchAPACitationStyle{
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
				
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));
		$title = $html?"<i>$publication->title</i>":$publication->title;
		$title = trim($title);

		$year = $publication->year;
		if($year != '0000' && $year != null)
			$year = " ($year)";
		else
			$year = '';			
		
		if(!empty($authorsText)){
			if(!empty($year))		
				$header = "$authorsText.$year. $title";
			else
				$header = "$authorsText. $title";	
		}else
			$header = "$title$year";	
			
		
		return "$header. $publication->school. $publication->address.";
	}
	
}


?>