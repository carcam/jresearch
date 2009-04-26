<?php
/**
 * @version		$Id$
* @package		JResearch
* @subpackage	Plugins
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent('onBeforeExecuteJResearchTask', 'plgJResearchDatabasesModifiedPublicationForm');

/**
 * Loads a modified publication form that allows the retrieval of publications from
 * public databases.
 * @return boolean True, if this plugin has decided to handle request, false otherwise.
 *
 */
function plgJResearchDatabasesModifiedPublicationForm(){
	$controller = JRequest::getVar('controller');
	$task = JRequest::getVar('task');
	global $mainframe;

	// If publications controller has not been requested, just return
	if($controller != 'publications')
		return false;

	// Load language
	$lang = JFactory::getLanguage();
	$lang->load('com_jresearch.publications');		
	//Load the view and the models manually :S	
	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications'.DS.'publication.php');
	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas'.DS.'researchareaslist.php');	
	$pubModel = new JResearchModelPublication();	
	$model = new JResearchModelResearchAreasList();
	
	$layoutPath = JPATH_PLUGINS.DS.'jresearch'.DS.'plg_jresearch_databases_modified_publication_form'.DS.'tmpl';
	
	if($mainframe->isAdmin()){
		if($task != 'edit')
			return false;	
		
		$cid = JRequest::getVar('cid');	
		$id = $cid[0];
					
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'publication'.DS.'view.html.php');
		$view = new JResearchAdminViewPublication();
	 	$layout = 'default';			
	}else{
		if($task != 'add' && $task != 'edit')
			return false;

		$id = JRequest::getVar('id');	
		
		require_once(JPATH_COMPONENT_SITE.DS.'views'.DS.'publication'.DS.'view.html.php');
		$view = new JResearchViewPublication();			
		$layout = 'edit';
	}

	$view->addTemplatePath($layoutPath);
	$view->setLayout($layout);

	if(!empty($id)){
		$publication = $pubModel->getItem($id);
		if(!empty($publication)){
			$user =& JFactory::getUser();
			// Verify if it is checked out
			if($publication->isCheckedOut($user->get('id'))){
				$mainframe->redirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
			}else{
				$publication->checkout($user->get('id'));
				$view->setModel($model);
				$view->display();	
			}
		}else{
			JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			$mainframe->redirect('index.php?option=com_jresearch&controller=publications');
		}				
	}else{			
		$view->setModel($model);
		$view->display();	
	}
	
	return true;

}

?>