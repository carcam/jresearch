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

jimport('joomla.application.component.controller');

/**
* JResearch Component Projects Controller
*
*/
class JResearchProjectsController extends JController
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
		$this->registerTask('executeExport', 'executeExport');
		
		// Add models paths
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'projects');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'projectslist');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'project');		
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
		JRequest::serVar('layout', 'admin');
		parent::display();
	}
	
	/**
	 * Triggered when the user clicks the submit button in the export projects
	 * form.
	 *
	 */
	function executeExport(){
		$session = &JFactory::getSession();
		JRequest::setVar('format', 'raw');
		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'projects');
		require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'projectexporters'.DS.'factory.php');
		$markedRecords = $session->get('markedRecords', null, 'jresearch');
		if($markedRecords !== null){
			if($markedRecords !== 'all'){
				$model = &$this->getModel('Project', 'JResearchModel');
				$projectsArray = array();
				foreach($markedRecords as $id){
					$projectsArray[] = $model->getItem($id);
				}
			}
			
			$format = 'doc';
			
			$exporter =& JResearchProjectExporterFactory::getInstance($format);
			$output = $exporter->parse($projectsArray);
			$document =& JFactory::getDocument();
			$document->setMimeEncoding($exporter->getMimeEncoding());
			$session->clear('markedRecords', 'jresearch');

			$tmpfname = "jresearch_output.$format";
			header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
			echo $output;
			
		}
	}
}
?>
