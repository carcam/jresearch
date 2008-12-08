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
*  - JResearchThesesController
*  - JResearchResearchAreasController
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

global $mainframe;

// Common needed files		
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'activity.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'publication.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'acl.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'cite.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'text.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'factory.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'includes'.DS.'jxtended.php');

//Set ACL
setACL();

$controller = JRequest::getVar('controller', null);
// Verify if view parameter is set (usually for frontend requests and map to a controller
if($controller === null)
	$controller = __mapViewToController();


require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');

//Require media and styles
$document = &JFactory::getDocument();
$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
$document->addStyleSheet($url.'/components/com_jresearch/css/jresearch_styles.css');

$session =& JFactory::getSession();

if($session->get('citedRecords', null, 'jresearch') == null){
	$session->set('citedRecords', array(), 'jresearch');
}

// Make an instance of the controller
$classname  = 'JResearch'.ucfirst($controller).'Controller';
$controller = new $classname( );

// Perform the request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();


/**
 * Maps the view requested to the controller that should process the request.
 * Useful when accessing JResearch from a menu item which include view parameter instead of
 * controller.
 *
 * @return string
 */
function __mapViewToController(){
	$view = JRequest::getVar('view');
	
	switch($view){
		case 'staff': case 'member':
			$value = 'staff';
			break;
		case 'publicationslist': case 'publication':
			$value = 'publications';
			break;
		case 'projectslist': case 'project':
			$value = 'projects';
			break;
		case 'theseslist': case 'thesis':
			$value = 'theses';
			break;
		case 'cooperations': case 'cooperation':
			$value = 'cooperations';
			break;
		case 'facilities': case 'facility':
			$value = 'facilities';
			break;
		default:
			$value = 'researchAreas';			
			break;
	}
	
	JRequest::setVar('controller', $value);
	return $value;
	
}


?>
