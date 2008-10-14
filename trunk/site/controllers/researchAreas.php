<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage		ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research areas (which allow to categorize projects, theses and publications).
*/

jimport('joomla.application.component.controller');

/**
 * Research Areas Component Controller
 *
 * @package		JResearch
 */
class JResearchResearchAreasController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		// When the wants to see more information about a research area.
		$this->registerTask('show', 'show');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'researchareaslist');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'researcharea');		
		
	}

	/**
	 * Default method, it shows the list of research areas with a short description.
	 *
	 * @access public
	 */

	function display(){
		global $mainframe;				
		
		//Set variables for model
		$limitstart = JRequest::getVar('limitstart', null);		
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		$limit = $params->get('area_entries_per_page');				
		JRequest::setVar('limit', $limit);
				
		// Set the view and the model
		$model =& $this->getModel('ResearchAreasList', 'JResearchModel');
		$view =& $this->getView('ResearchAreasList', 'html', 'JResearchView');
		$view->setModel(&$model, true);
		$view->display();
		
	}


	/**
	* Invoked when the visitant has decided to see more information about a research area.
	* @access public
	*/
	function show(){
		$model =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('ResearchArea', 'html', 'JResearchView');
		$view->setModel(&$model, true);
		$view->display();				

	}
}
?>
