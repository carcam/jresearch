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
class JFormRuleDate extends JFormRule
{
        /**
         * The regular expression.
         *
         * @access      protected
         * @var         string
         * @since       1.6
         */
        protected $regex = '^\d{4}(-\d{2}){2}$';
}

?>
