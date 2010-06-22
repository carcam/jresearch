<?php
/**
 * Joomla! Upgrade Helper
 */

// no direct access
defined('_JEXEC') or die('No direct access allowed ;)');


/*
 * Make sure the user is authorized to view this page
 * We are the same security as the installer subsystem
 */
$user = & JFactory::getUser();
if (!$user->authorize('com_installer', 'installer')) {
	$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

// Require the base controller
require_once( JPATH_JRESEARCH_UPDATER.DS.'controller.php' );
require_once (JPATH_JRESEARCH_UPDATER.DS.'admin.jupdateman.html.php');
require_once (JPATH_JRESEARCH_UPDATER.DS.'jupdateman.class.php');

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_JRESEARCH_UPDATER.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

// Create the controller
$classname    = 'JUpdateManController'.$controller;
$controller   = new $classname( );

$lang =& JFactory::getLanguage();
$lang->load('com_installer'); // borrow the installer language files :D

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();

?>