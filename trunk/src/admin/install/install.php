<?php
/**
 * @version			$Id$
 * @package			JResearch
 * @copyright		Copyright (C) 2008 Luis Galarraga.
 * @license			GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Invoked after JResearch installation to install the files used for TinyMCE native
 * automatic citation.
 * @return boolean True if operations are executed successfully
 */
function com_install(){
    return true;
}

?>