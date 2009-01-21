<?php 
/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file is the main entry for component JResearch backend. Its purpose is to load
* the right controller based on request. Controllers reside in folder admin/controllers
* and are implemented in files with the same name. The frontend interface of JResearch
* is administered by the following controllers:
*  - JResearchAdminPublicationsController
*  - JResearchAdminProjectsController
*  - JResearchAdminThesesController
*  - JResearchAdminResearchAreasController
*  - JResearchAdminController for configuration tasks
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

// Common needed files
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'activity.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'publication.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'cite.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'text.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'factory.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'plg_jresearch_native_plugins'.DS.'plg_jresearch_entities_load_cited_records.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'plg_jresearch_native_plugins'.DS.'plg_jresearch_entities_save_cited_records.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'toolbar.jresearch.html.php');

$document = &JFactory::getDocument();
$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
$document->addStyleSheet($url.'components/com_jresearch/css/jresearch_styles.css');

// Require specific controller. Publications is the default
$controller = JRequest::getVar('controller', null);
$task = JRequest::getVar('task');
$prefix = 'JResearchAdmin';

if($controller == null){
	// It is the default controller
	require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php');
	$classname = $prefix.'Controller';
}else{
	// That task is the exception
	if($task == 'tocontrolPanel'){
		// It is the default controller
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php');
		$classname = $prefix.'Controller';
	}else{				
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$controller.'.php');
		$inlineCitingTasks = array('cite', 'citeFromDialog', 'generateBibliography', 'searchByPrefix', 'ajaxRemoveAll', 'ajaxGenerateBibliography', 'removeCitedRecord' ); 
		
		// If the task is related to cite records, request the frontend controller
		if(in_array($task, $inlineCitingTasks)){
			$prefix = 'JResearch';
			require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'controllers'.DS.$controller.'.php');
			$session =& JSession::getInstance(null, null);
		
			if($session->get('citedRecords', null, 'jresearch') == null){
				$session->set('citedRecords', array(), 'jresearch');
			}
		}
		
	
		// Make an instance of the controller
		$classname  = $prefix.ucfirst($controller).'Controller';
	}
}

$controller = new $classname( );
// Perform the request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
?>
