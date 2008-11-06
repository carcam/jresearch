<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright		Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research projects.
*/

jimport('joomla.application.component.controller');

/**
* JResearch Component Facilities Controller
*
*/
class JResearchFacilitiesController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
		parent::__construct();
		$this->registerTask('show', 'show');
		// Add models paths
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'facilities');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'facilities');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'facility');		
	}

	/**
	 * Default method, it shows the list of research facilities. 
	 *
	 * @access public
	 */

	function display()
	{
		global $mainframe;
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		
    	//Set limit
		$limit = $params->get('facility_entries_per_page');			
		$limitstart = JRequest::getVar('limitstart', null);		
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);		

		JRequest::setVar('limit', $limit);		
		JRequest::setVar('filter_order', 'ordering');
		JRequest::setVar('filter_order_Dir', 'DESC');
		
		// Set the view and the model
		$model =& $this->getModel('Facilities', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		
		//View
		$view =& $this->getView('Facilities', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();		
	}

	/**
	* Invoked when the visitant has decided to see the detailed description of
	* a research facility.
	* @access public
	*/
	function show()
	{
		$model =& $this->getModel('Facility', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		
		$view =& $this->getView('Facility', 'html', 'JResearchView');
		
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();
	}
}
?>
