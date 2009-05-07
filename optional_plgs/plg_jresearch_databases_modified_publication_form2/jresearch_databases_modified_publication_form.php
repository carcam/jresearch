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
 * Loads the scripts needed to modify the DOM structure of publication form in order to 
 * include a button to retrieve information from public databases.
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
		
	if($mainframe->isAdmin()){
		if($task != 'edit')
			return false;		
	}else{
		if($task != 'add' && $task != 'edit')
			return false;
	}
	
	$pubtype = JRequest::getVar('pubtype');		
	if($pubtype != 'article'){
		return false;	
	}	
	
	$plugin = JPluginHelper::getPlugin('jresearch', 'jresearch_databases_modified_publication_form');
	$params = new JParameter($plugin->params);
	$database = $params->get('service', 'Pubmed');
	
	JHTML::_('behavior.mootools');	
	$doc = JFactory::getDocument();
	$buttonText = JText::_('Apply');
	$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
	$xmlRpcUrl = $url.'xmlrpc';
	$loading = JText::_('JRESEARCH_LOADING');
	$failureMessage = JText::_('JRESEARCH_CALL_FAILURE_MESSAGE');
	$doc->addScript($url.'/plugins/jresearch/plg_jresearch_databases_modified_publication_form_scripts/databases.js');
	$doc->addScriptDeclaration("document.jresearch_plugins_buttonText = '$buttonText';
								document.jresearch_plugins_external_service = 'Pubmed';
								document.jresearch_plugins_xmlrpc_url = '$xmlRpcUrl';
								document.jresearch_loading_text = '$loading';
								document.jresearch_call_failure_message = '$failureMessage';
								window.onload = addDatabasesSearchButton;
								");
									
	
	return false;		
	
}

?>