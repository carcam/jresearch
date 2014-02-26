<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Validation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* J!Research is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access');
 
// import Joomla formrule library
jimport('joomla.form.formrule');
 
/**
 * Form Rule class for the Joomla Framework.
 */
class JFormRuleNumber extends JFormRule
{
        /**
         * The regular expression.
         *
         * @access      protected
         * @var         string
         * @since       1.6
         */
        protected $regex;
        
        function __construct(){
        	jresearchimport('helpers.charsets');
            $extra = implode('', JResearchCharsetsHelper::getLatinWordSpecialChars());
        	$regex= "^[-_'\w$extra\s\d]+([,;][-_'\w$extra\s\d]+)*[,;]*$";
        }
}

?>
