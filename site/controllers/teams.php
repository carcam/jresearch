<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Teams
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of teams.
*/



class JResearchTeamsController extends JResearchFrontendController
{
	public function __construct($config = array())
	{
		parent::__construct ($config);
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.teams');
		$lang->load('com_jresearch.staff');
		
		// Task for edition of profile
		$this->registerDefaultTask('display');
		$this->registerTask('show', 'show');

		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'teams');
		
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'teams');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'team');
		
		$this->addPathwayItem(JText::_('JRESEARCH_TEAMS'), 'index.php?option=com_jresearch&view=teams');
	}
	
	/**
	 * Default method, it shows the list of published staff members.
	 *
	 * @access public
	 */
	public function display()
	{
		global $mainframe;
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$limit = $params->get('team_entries_per_page');
    	
		JRequest::setVar('limit', $limit);
		$limitstart = JRequest::getVar('limitstart', null);	
			
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);
			
		$model =& $this->getModel('Teams', 'JResearchModel');
		$view =& $this->getView('Teams', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	* Invoked when the visitant has decided to see a team
	*/
	public function show()
	{
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'staff');
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'publications');
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'projects');
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'theses');
		
		$model =& $this->getModel('Team', 'JResearchModel');
		$memberModel =& $this->getModel('Member', 'JResearchModel');
		$pubsModel =& $this->getModel('Publicationslist', 'JResearchModel');
		$projectsModel =& $this->getModel('Projectslist', 'JResearchModel');
		$thesesModel =& $this->getModel('Theseslist', 'JResearchModel');
		
		$view =& $this->getView('Team', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($memberModel);
		$view->setModel($pubsModel);
		$view->setModel($projectsModel);
		$view->setModel($thesesModel);
		$view->display();				
	}
}

?>