<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	HTML
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'language.php');
/**
 * JHTML helper class used for enabling client side validation of JResearch
 * forms.
 *
 */
class JHTMLValidator{
	
	/**
	* Renders the DHTML code needed to enable validation in JResearch forms.
	*/
	static function _(){
		$doc =& JFactory::getDocument();
		$token = JUtility::getToken();
		JHTML::_('behavior.formvalidation');
	$message = JText::_('JRESEARCH_FORM_NOT_VALID');
    	$doc->addScriptDeclaration("function validate(f) {
			if(document.adminForm.task.value != 'cancel'){
	    		if (document.formvalidator.isValid(f)) {
					return true; 
				}else {
					alert('$message');
					return false;
				}
    		}else
    			return true;
		}");
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'date\', function(value) {
			regex=/^\d{4}(-\d{2}){2}$/;
			return regex.test(value); })
		})');				
    	
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'url\', function(value) {
			regex=/^(ftp|http|https|ftps):\/\/([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}|localhost|\d{1,3}(\.\d{1,3}){3})(:\d{2,5})?(([0-9]{1,5})?\/.*)?$/i;
			return regex.test(value); })
		})');
    	
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'year\', function(value) {
			regex=/^\d{4}$/i;
			return regex.test(value); })
		})');
    	
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'quantity\', function(value) {
			regex=/^([0-9]+[.][0-9]*|[0-9]*[.][0-9]+|[0-9]+)$/i;
			return regex.test(value); })
		})');
    	
    	require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'language.php');
		$extra = extra_word_characters();
    	$doc->addScriptDeclaration("window.onDomReady(function() {
			document.formvalidator.setHandler('keywords', function(value) {
			regex=/^[-_'\w$extra\s\d]+([,;][-_'\w$extra\s\d]+)*[,;]*$/i;
			return regex.test(value); })
		})");
    	
    	
	}
}

?>