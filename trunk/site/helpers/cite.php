<?php
/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * This class implements the application of citation styles based on 
 * the commands sent by user.
 *
 */
class JResearchCite{
	
	/**
	 * Returns a reference to the global JResearchCite object
	 * @access public
	 * @static 
	 */
	public static function &getInstance(){
		static $citer;	
		
		if(!isset($citer)){
			$citer = new JResearchCite();
		}
		
		return $citer;		
		
	}
	
	/**
	 * Returns the cite text in a non-parenthetical way for the provided publications.
	 * 
	 * @param array $publications Array of JResearchPublication objects
	 * @return The appropiate cited text, based on the default citation style.
	 */
	public function cite($publications){
		global $mainframe;
		
		$params =& JComponentHelper::getParams('com_jresearch');
		$configuredCitationStyle = $params->get('citationStyle', 'APA');
		$style =& JResearchCitationStyleFactory::getInstance($configuredCitationStyle);
		if(count($publications) > 1)
			return $style->getCitationHTMLText($publications);
		else
			return $style->getCitationHTMLText($publications[0]);
	}
	
	/**
	 * Returns the cite text in a parenthetical way for the provided publications.
	 * 
	 * @param array $publications Array of JResearchPublication objects
	 * @return The appropiate cited text, based on the default citation style.
	 */
	public function citep($publications){
		global $mainframe;
		
		$params =& JComponentHelper::getParams('com_jresearch');
		$configuredCitationStyle = $params->get('citationStyle', 'APA');
		$style =& JResearchCitationStyleFactory::getInstance($configuredCitationStyle);
		if(count($publications) > 1)
			return $style->getParentheticalCitationHTMLText($publications);
		else
			return $style->getParentheticalCitationHTMLText($publications[0]);	
	}
	
	/**
	 * Returns the year of publication of the provided records.
	 * 
	 * @param array $publications Array of JResearchPublication objects
	 * @return The appropiate cited text, in this case, the years of publication of every
	 * record in the array, separated by commas.
	 */
	public function citeyear($publications){
		$result = '(';
		$years = array();
		
		foreach($publications as $p){
			$years[] = $p->year;
		}
		
		$result .= implode(',', $years); 	
		$result .= ')';
		return $result;
	}
	
	/**
	 * Invoked when requesting a nocite command which does not return anything
	 * but stores the key in the session.
	 *
	 * @param array $publications
	 * @return string
	 */
	public function nocite($publications){
		return ' ';
	}
	
	/**
	 * Returns the "Reference" section in HTML format based on the publications provided.
	 * @param array $publications Array of JResearchPublication objects
	 * @return The appropiate HTML text
	 */
	public function bibliography($publications){
		global $mainframe;
		
		$params =& JComponentHelper::getParams('com_jresearch');
		$configuredCitationStyle = $params->get('citationStyle', 'APA');

		$style =& JResearchCitationStyleFactory::getInstance($configuredCitationStyle);
		return $style->getBibliographyHTMLText($publications);
		
	}
}

?>