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
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'projects');
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'researchareas');
		
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'projectslist');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'project');		
		
		$this->addPathwayItem(JText::_('JRESEARCH_PROJECTS'), 'index.php?option=com_jresearch&view=projectslist');
	}

	/**
	 * Default method, it shows the list of research projects. 
	 *
	 * @access public
	 */

	function display(){
		global $mainframe;
		
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
		$model =& $this->getModel('ProjectsList', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('ProjectsList', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();		
	}

	/**
	* Invoked when an authenticated user decides to create/edit a project he/she is part of
	* 
	* @access public
	*/
	function edit(){
		
		JRequest::setVar('view', 'projects');
		JRequest::serVar('layout', 'edit');
		parent::display();
	}

	/**
	* Invoked when the visitant has decided to see the detailed description of
	* a research project.
	* @access public
	*/
	function show(){
		require_once(JPATH_COMPONENT.DS.'helpers'.DS.'html'.DS.'jresearch.php');
		$model =& $this->getModel('Project', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		
		$view =& $this->getView('Project', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);

		$view->display();
	}

	/**
	* Invoked when an authenticated user sees the list of his/her projects
	* in an administrator form.
	*
	*/
	function administer(){
		JRequest::setVar('view', 'projects');
		JRequest::setVar('layout', 'admin');
		parent::display();
	}
}
?>
