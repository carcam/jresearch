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
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jresearch'.DS.'includes'.DS.'defines.php');

// Plugin management
JPluginHelper::importPlugin('jresearch');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'plugins.php');

//Helpers
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'cite.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'jresearch.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'view.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'unlocker.php');


//HTML helpers
JHTML::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'html');
JHTML::addIncludePath(JRESEARCH_COMPONENT_SITE.DS.'helpers'.DS.'html');
JTable::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'tables');

//Citation factory
require_once(JRESEARCH_COMPONENT_SITE.DS.'citationStyles'.DS.'factory.php');

//Tables
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'publication.php');

//Needed plugins
require_once(JRESEARCH_COMPONENT_SITE.DS.'plg_jresearch_native_plugins'.DS.'plg_jresearch_entities_load_cited_records.php');
require_once(JRESEARCH_COMPONENT_SITE.DS.'plg_jresearch_native_plugins'.DS.'plg_jresearch_entities_save_cited_records.php');


//Time to import all plugins of type jresearch
JResearchPluginsHelper::requireJResearchPlugins();

//Toolbar
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'toolbar.jresearch.html.php');

//Stylesheet
global $mainframe;
$document = &JFactory::getDocument();
$url = $mainframe->getSiteURL();
$document->addStyleSheet($url.'administrator/components/com_jresearch/css/jresearch_admin_styles.css');


?>