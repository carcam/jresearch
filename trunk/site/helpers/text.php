<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Helpers
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
			$translations = array('article'=> JText::_('Article'), 
								'book'=> JText::_('Book'),
								'booklet' => JText::_('Booklet'),
								'conference' => JText::_('Conference'),
								'inbook' => JText::_('Inbook'),
								'incollection' => JText::_('Incollection'),
								'inproceedings' => JText::_('Inproceedings'),
								'manual' => JText::_('Manual'),
								'masterthesis' => JText::_('Master Thesis'),
								'misc' => JText::_('Misc'),
								'phdthesis' => JText::_('Phd Thesis'),
								'proceedings' => JText::_('Proceedings'),
								'techreport' => JText::_('Technical Report'),
								'unpublished' => JText::_('Unpublished'),
								'abstract' => JText::_('Abstract'),
								'howpublished' => JText::_('How published')
								  	
			);
		}
		
		$text = $translations[$value];
		return $text? $text:ucfirst($value);	
	}
	
}


?>