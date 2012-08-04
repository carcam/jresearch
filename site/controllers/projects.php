<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research projects.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* JResearch Component Projects Controller
*
*/
class JResearchProjectsController extends JResearchFrontendController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.projects');
		
		$this->registerTask('show', 'show');
		// Add models paths
		$this->addModelPath(JPATH_COMPONENT.DS.'models'.DS.'projects');		
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'projects');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'project');		
		
		$this->addPathwayItem(JText::_('JRESEARCH_PROJECTS'), 'index.php?option=com_jresearch&view=projectslist');
	}

	/**
	 * Default method, it shows the list of research projects. 
	 *
	 * @access public
	 */

	function display(){
		$mainframe = JFactory::getApplication();
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		
		$limit = $params->get('projects_entries_per_page');			
		$limitstart = JRequest::getVar('limitstart', null);		
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);		

		JRequest::setVar('limit', $limit);	
		
		$order = $params->get('order');
		$order_Dir = $params->get('order_Dir');
		
		JRequest::setVar('filter_order', $order);
		JRequest::setVar('filter_order_Dir', $order_Dir);
		
		// Set the view and the model
		$model =& $this->getModel('Projects', 'JResearchModel');
		$view =& $this->getView('Projects', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();		
	}
	
	/**
	* Invoked when the visitant has decided to see the detailed description of
	* a publication.
	* @access public
	*/
	function show(){
		$model = $this->getModel('Project', 'JResearchModel');
		$view = $this->getView('Project', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();				
	}
	
}
?>
