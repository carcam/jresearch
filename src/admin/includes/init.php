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


function jresearchimport($entity, $space = 'system'){
    if($space == 'system')
        jimport($entity);
    elseif($space == 'jresearch'){

    }elseif($space == 'jresearch.site'){

    }elseif($space == 'jresearch.admin'){

    }else{
        
    }

}

/**
 * This file loads common classes and files used by J!Research.
 * 
 */

// Common needed files		
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'includes'.DS.'defines.php');

//Helpers
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'cite.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'includes'.DS.'view.php');

//HTML helpers
JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'html');
JHTML::addIncludePath(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'html');

//Citation factory
require_once(JPATH_COMPONENT_SITE.DS.'citationStyles'.DS.'factory.php');

//Tables
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'publication.php');

//Needed plugins
require_once(JPATH_COMPONENT_SITE.DS.'plg_jresearch_native_plugins'.DS.'plg_jresearch_entities_load_cited_records.php');
require_once(JPATH_COMPONENT_SITE.DS.'plg_jresearch_native_plugins'.DS.'plg_jresearch_entities_save_cited_records.php');

// Plugin management
JPluginHelper::importPlugin('jresearch');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'plugins.php');

//Toolbar
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'toolbar.jresearch.html.php');

//Stylesheet
$mainframe = JFactory::getApplication();
$document = JFactory::getDocument();
$url = JURI::root();
$document->addStyleSheet($url.'administrator/components/com_jresearch/css/jresearch_admin_styles.css');

?>