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
 * Imports all the subclasses of JResearchPublication. Must be invoked
 * for any script that works with JResearchPublication entities.
 *
 */
function __loadPublicationsSubclasses(){
	jimport('joomla.filesystem.path');
	jimport('joomla.filesystem.folder');
	$db = JFactory::getDBO();
	$db->setQuery('SELECT '.$db->nameQuote('name').' FROM '.$db->nameQuote('#__jresearch_publication_type'));
	
	$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'tables';
	$files = JFolder::files($path, '.php');
	foreach($files as $f){
		require_once($path.DS.$f);
	}
	
}

/**
 * This file loads common classes and files used by J!Research.
 * 
 */

// Common needed files		
require_once(JPATH_COMPONENT_SITE.DS.'includes'.DS.'defines.php');

//Helpers
require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'acl.php');
require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'cite.php');
require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'text.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'view.php');

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

// Verify if Jxtended is available as a plugin in the system.
if(!function_exists('jximport'))
	require_once(JPATH_COMPONENT_SITE.DS.'includes'.DS.'jxtended.php');


// Plugin management
JPluginHelper::importPlugin('jresearch');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'plugins.php');

__loadPublicationsSubclasses();

?>