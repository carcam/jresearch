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
require_once(JPATH_COMPONENT_SITE.DS.'includes'.DS.'defines.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'includes'.DS.'import.php');

//Helpers
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'cite.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'jresearchutilities.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'includes'.DS.'view.php');

//HTML helpers
JHTML::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'html');
JHTML::addIncludePath(JRESEARCH_COMPONENT_SITE.DS.'helpers'.DS.'html');

//Citation factory
require_once(JRESEARCH_COMPONENT_SITE.DS.'citationStyles'.DS.'factory.php');

// Plugin management
JPluginHelper::importPlugin('jresearch');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'plugins.php');

//Require media and styles
$document = JFactory::getDocument();
$url = JURI::base();
$document->addStyleSheet($url.'/components/com_jresearch/css/jresearch_styles.css');


?>