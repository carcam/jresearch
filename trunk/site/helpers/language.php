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
* Utilities for language configurations. These function return a string containing
* non-ascii word characters ignored by \w regex metacharacter, according to the current
* language. 
*/
function extra_word_characters(){
	$doc = JFactory::getDocument();
	$languageFunction = str_replace('-', '_', $doc->getLanguage()).'_extra_word_characters';
	if(!function_exists($languageFunction))
		return '';
	
	return $languageFunction();
}


function es_ES_extra_word_characters(){
	// This function considers Spanish accented characters
	return '\x{E1}\x{C1}\x{E9}\x{C9}\x{ED}\x{CD}\x{F3}\x{D3}\x{FA}\x{DA}\x{F1}\x{D1}';
}

function en_GB_extra_word_characters(){
	return '';
}


?>