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
$user = JFactory::getUser();

// Common needed files
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'init.php');

// Handle upgrade case
$mode = JRequest::getVar('mode');
if($mode == 'upgrader'){
    // Verify the plugin is installed in the system
    if(!JPluginHelper::isEnabled('jresearch', 'jresearch_upgrader')){
        JError::raiseWarning(1, JText::_('JRESEARCH_UPGRADE_PLUGIN_NOT_INSTALLED'));
        $mainframe->redirect('index.php?option=com_jresearch');
    }
}

// Require specific controller. Publications is the default
$controller = JRequest::getVar('controller', null);
$task = JRequest::getVar('task');
$prefix = 'JResearchAdmin';
$availableControllers = array('publications', 'projects', 'theses', 'staff', 'cooperations', 'teams', 'facilities', 'researchAreas', 'financiers', 'member_positions');

if($controller == null || !in_array($controller, $availableControllers)){
	// It is the default controller
	require_once (JRESEARCH_COMPONENT_ADMIN.DS.'controller.php');
	$classname = $prefix.'Controller';
}else{
	// That task is the exception
	if($task == 'tocontrolPanel'){
		// It is the default controller
		require_once (JRESEARCH_COMPONENT_ADMIN.DS.'controller.php');
		$classname = $prefix.'Controller';
	}else{				
		require_once (JRESEARCH_COMPONENT_ADMIN.DS.'controllers'.DS.$controller.'.php');
		$inlineCitingTasks = array('cite', 'citeFromDialog', 'generateBibliography', 'searchByPrefix', 'ajaxRemoveAll', 'ajaxGenerateBibliography', 'removeCitedRecord' ); 
		
		// If the task is related to cite records, request the frontend controller
		if(in_array($task, $inlineCitingTasks)){
			$prefix = 'JResearch';
			require_once(JRESEARCH_COMPONENT_SITE.DS.'controllers'.DS.$controller.'.php');
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

$pluginhandledRequest = JResearchPluginsHelper::onBeforeExecuteJResearchTask();
// Perform the request task if none of the plugins decided to do it
if(!$pluginhandledRequest)
	$controller->execute($task);
	
$mainframe->triggerEvent('onAfterExecuteJResearchTask' , array());

// Redirect if set by the controller
if(!$pluginhandledRequest)
	$controller->redirect();
?>
