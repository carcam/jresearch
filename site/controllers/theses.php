<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of theses.
*/



/**
 * JResearch Theses Frontend Controller
 *
 */
class JResearchThesesController extends JResearchFrontendController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.theses');
		
		// When the user sees the detailed information of a thesis
		$this->registerTask('show', 'show');
		// Add models paths
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'theses');
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'theseslist');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'thesis');

		$this->addPathwayItem(JText::_('JRESEARCH_THESES'), 'index.php?option=com_jresearch&view=theseslist');
	}

	/**
	 * Default method, it shows the list of theses.
	 *
	 * @access public
	 */

	function display(){
		global $mainframe;
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		
		$limit = $params->get('theses_entries_per_page');			
		$limitstart = JRequest::getVar('limitstart', null);		
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);		

		JRequest::setVar('limit', $limit);		
		JRequest::setVar('filter_order', 'start_date');
		JRequest::setVar('filter_order_Dir', 'DESC');
		
		// Set the view and the model
		$model =& $this->getModel('ThesesList', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('ThesesList', 'html', 'JResearchView');
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
		
		JRequest::setVar('view', 'theses');
		JRequest::serVar('layout', 'edit');
		parent::display();
	}

	/**
	* Invoked when the visitant has decided to see the detailed description of
	* a research project.
	* @access public
	*/
	function show(){
		$model =& $this->getModel('Thesis', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('Thesis', 'html', 'JResearchView');
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
		JRequest::setVar('view', 'theses');
		JRequest::serVar('layout', 'admin');
		parent::display();
	}
}
?>
