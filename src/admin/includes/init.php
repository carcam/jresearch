<?php
/**
* @version	$Id$
* @package	JResearch
* @subpackage	Includes
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license	GNU/GPL
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
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jresearch'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php');
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

//Toolbar
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'toolbar.jresearch.html.php');

//Stylesheet
$mainframe = JFactory::getApplication();
$document = JFactory::getDocument();
$url = JURI::root();
$document->addStyleSheet($url.'administrator/components/com_jresearch/css/jresearch_admin_styles.css');

//Loading Bootstrap for Joomla! 2.5
if (version_compare(JVERSION, "3.0.0", 'le'))
{
	JLoader::register('JHtmlBootstrap',JPATH_LIBRARIES."/jresearch/bootstrap/bootstrap.php");
	JLoader::register('JHtmlJquery',JPATH_LIBRARIES."/jresearch/bootstrap/jquery.php");
	JLoader::register('JLayout',JPATH_LIBRARIES."/jresearch/jlayouts/layout.php");
	JLoader::register('JLayoutHelper',JPATH_LIBRARIES."/jresearch/jlayouts/helper.php");
	JLoader::register('JLayoutFile',JPATH_LIBRARIES."/jresearch/jlayouts/file.php");
	JLoader::register('JLayoutBase',JPATH_LIBRARIES."/jresearch/jlayouts/base.php");

}

?>