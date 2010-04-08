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
 * This helper class is used for internationalization of certain information
 * that is usually taken from database like properties and types of publications.
 * Its use is similar to JText class which is part of Joomla Framework
 *
 */
class JResearchText{
	
	/**
	 * Returns the associated label to the argument according to the site 
	 * configured language. 
	 *
	 * @param string $value
	 */
	static function _($value){
		static $translations;
		
		if(!isset($translations)){
			$translations = array('article'=> JText::_('JRESEARCH_ARTICLE'), 
								'book'=> JText::_('JRESEARCH_BOOK'),
								'booklet' => JText::_('JRESEARCH_BOOKLET'),
								'conference' => JText::_('JRESEARCH_CONFERENCE'),
								'inbook' => JText::_('JRESEARCH_INBOOK'),
								'incollection' => JText::_('JRESEARCH_INCOLLECTION'),
								'inproceedings' => JText::_('JRESEARCH_INPROCEEDINGS'),
								'manual' => JText::_('JRESEARCH_MANUAL'),
								'masterthesis' => JText::_('JRESEARCH_MASTERSTHESIS'),
								'patent' => JText::_('JRESEARCH_PATENT'),
								'misc' => JText::_('JRESEARCH_MISC'),
								'phdthesis' => JText::_('JRESEARCH_PHDTHESIS'),
								'proceedings' => JText::_('JRESEARCH_PROCEEDINGS'),
								'techreport' => JText::_('JRESEARCH_TECHREPORT'),
								'unpublished' => JText::_('JRESEARCH_UNPUBLISHED'),
								'abstract' => JText::_('JRESEARCH_ABSTRACT'),
								'howpublished' => JText::_('JRESEARCH_HOWPUBLISHED')
								  	
			);
		}
		
		$text = $translations[$value];
		return $text? $text:ucfirst($value);	
	}
	
}


?>
