<?php
/**
* @version		$Id$
* @package		JResearch
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
 * This file loads common classes and files used by J!Research.
 * 
 */

// Common needed files		
require_once(JPATH_ADMINISTRATOR.'/'.'components'.'/'.'com_jresearch'.'/'.'includes'.'/'.'defines.php');
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'includes'.'/'.'import.php');

//Helpers
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'helpers'.'/'.'cite.php');
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'helpers'.'/'.'jresearchutilities.php');
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'includes'.'/'.'view.php');

//HTML helpers
JHTML::addIncludePath(JRESEARCH_COMPONENT_ADMIN.'/'.'helpers'.'/'.'html');
JHTML::addIncludePath(JRESEARCH_COMPONENT_SITE.'/'.'helpers'.'/'.'html');

//Citation factory
require_once(JRESEARCH_COMPONENT_SITE.'/'.'citationStyles'.'/'.'factory.php');

// Plugin management
JPluginHelper::importPlugin('jresearch');
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'helpers'.'/'.'plugins.php');

//Toolbar
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'toolbar.jresearch.html.php');

//Stylesheet
$mainframe = JFactory::getApplication();
$document = JFactory::getDocument();
$url = JURI::root();
$document->addStyleSheet($url.'administrator/components/com_jresearch/css/jresearch_admin_styles.css');

?>