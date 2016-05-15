<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Frontend
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file is the main entry for component JResearch. Its purpose is to load
* the right controller based in request. Controllers reside in folder site/controllers
* and are implemented in files with the same name. The frontend interface of JResearch
* is administered by the following controllers:
*  - JResearchPublicationsController
*  - JResearchProjectsController
*  - JResearchResearchAreasController
* - JResearchMember_positionsController
* - JResearchStaffController
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

$mainframe = JFactory::getApplication();
$jinput = $mainframe->input;
// Common needed files
require_once(JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'init.php');
require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'controller.php');
jresearchimport('helpers.access', 'jresearch.admin');

$controller = $jinput->get('controller', null);
// Verify if view parameter is set (usually for frontend requests and map to a controller
if($controller === null)
    $controller = __mapViewToController();
else{
    $availableControllers = array('publications', 'projects', 'staff', 'researchareas');
    if(!in_array($controller, $availableControllers))
        $controller = '';
}

if(empty($controller)){
    JError::raiseError(404, JText::_('Controller undefined'));
    return;
}

require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
//Session
$session = JFactory::getSession();

if($session->get('citedRecords', null, 'jresearch') == null){
    $session->set('citedRecords', array(), 'jresearch');
}

// Make an instance of the controller
$controllerObj = JControllerLegacy::getInstance('JResearch'.ucfirst($controller));

$pluginhandledRequest = JResearchPluginsHelper::onBeforeExecuteJResearchTask();
// Perform the request task if none of the plugins decided to do it
if (!$pluginhandledRequest) {
    $controllerObj->execute($jinput->get('task'));
}

$mainframe->triggerEvent('onAfterExecuteJResearchTask' , array());

// Redirect if set by the controller
$controllerObj->redirect();


/**
 * Maps the view requested to the controller that should process the request.
 * Useful when accessing JResearch from a menu item which include view parameter instead of
 * controller.
 *
 * @return string
 */
function __mapViewToController(){
    $jinput = JFactory::getApplication()->input;                
    $view = $jinput->get('view');    
    switch($view){
        case 'staff': case 'member':
            $value = 'staff';
            break;
        case 'publications': case 'publication':
            $value = 'publications';
            break;
        case 'projects': case 'project':
            $value = 'projects';
            break;
        case 'researchareas': case 'researcharea':
            $value = 'researchareas';			
            break;
        default:
            $value = '';
            break;	
    }

    $jinput->set('controller', $value);
    return $value;
}

?>