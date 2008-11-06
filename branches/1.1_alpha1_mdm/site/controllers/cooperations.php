<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of staff members.
*/

jimport('joomla.application.component.controller');

/**
 * JResearch Cooperations Component Controller
 *
 */
class JResearchCooperationsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 */
	function __construct()
	{
		parent::__construct();
		
		// Task for edition of profile
		$this->registerTask('show', 'show');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'cooperations');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'staff');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'member');

	}

	/**
	 * Default method, it shows the list of published staff members.
	 *
	 * @access public
	 */

	function display()
	{
		global $mainframe;
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$limit = $params->get('cooperation_entries_per_page');
    	
		JRequest::setVar('limit', $limit);
		$limitstart = JRequest::getVar('limitstart', null);	
			
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);
			
		
		$model =& $this->getModel('Cooperations', 'JResearchModel');
		$view =& $this->getView('Cooperations', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	* Invoked when the visitant has decided to see a member's profile
	*/
	function show()
	{
		$model =& $this->getModel('Cooperation', 'JResearchModel');
		$view =& $this->getView('Cooperation', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();				
	}
}
?>