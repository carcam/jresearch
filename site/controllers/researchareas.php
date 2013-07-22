<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research areas (which allow to categorize projects, theses and publications).
*/

/**
 * Research Areas Component Controller
 *
 */
class JResearchResearchareasController extends JResearchFrontendController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.researchareas');
		
		// When the wants to see more information about a research area.
		$this->registerDefaultTask('display');
		$this->registerTask('show', 'show');
		$this->addModelPath(JPATH_COMPONENT.DS.'models'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'researcharea');
		
		$this->addPathwayItem(JText::_('JRESEARCH_RESEARCH_AREAS'), 'index.php?option=com_jresearch&view=researchareas');
	}

	/**
	 * Default method, it shows the list of research areas with a short description.
	 *
	 * @access public
	 */

	function display(){
            // Set the view and the model
		$model = $this->getModel('Researchareas', 'JResearchModel');
        $view = $this->getView('Researchareas', 'html', 'JResearchView');
        $view->setModel($model, true);
        $view->display();		
	}


	/**
	* Invoked when the visitant has decided to see more information about a research area.
	* @access public
	*/
	function show(){
       $model = $this->getModel('ResearchArea', 'JResearchModel');
       $view = $this->getView('ResearchArea', 'html', 'JResearchView');
       $view->setModel($model, true);
       $view->display();
	}
}
?>
