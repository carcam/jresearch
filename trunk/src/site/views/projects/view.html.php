<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of presentation of
* staff member list in frontend
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for presentation of projects list in frontend.
 *
 */

class JResearchViewProjects extends JResearchView
{
    function display($tpl = null)
    {
        //Add template path explicitly (useful when requesting from the backend)
        $this->addTemplatePath(JRESEARCH_COMPONENT_SITE.DS.'views'.DS.'projects'.DS.'tmpl');    	
        $this->_displayDefaultList($tpl);	
    }
    
    /**
    * Display the list of published projects.
    */
    private function _displayDefaultList($tpl = null){
      	$mainframe = JFactory::getApplication();    	
      	$doc = JFactory::getDocument();
    	//Get the model
    	$model = $this->getModel();    	
    	$params = $mainframe->getParams();
    	
        $filterByArea = $params->get('projects_filterby_area', 0);
        if($filterByArea == 1){
            JRequest::setVar('filter_area', $params->get('projects_filter_area', 0));
        }
        $showFilterByArea = $params->get('projects_show_filterby_area', 0);
        
        
        $filterByStatus = $params->get('projects_filterby_status', 0);
        if($filterByStatus == 1){
            JRequest::setVar('filter_status', $params->get('projects_status_filter', 'not_started'));
        }
        
        $filterByYear = $params->get('projects_filterby_year', 0);
        if($filterByYear == 1){
            JRequest::setVar('filter_year', $params->get('projects_year_filter', date('Y')));
        }
        
        $defaultSorting = $params->get('projects_default_sorting', 'start_date');
        JRequest::setVar('filter_order', $defaultSorting);

        $ordering = $params->get('projects_order', 'asc');
        JRequest::setVar('filter_order_Dir', $ordering);

    	$projects =  $model->getItems();   
    	
    	$this->_setFilters();    	
    	$pageHeader = $params->get('page_heading', JText::_('JRESEARCH_PUBLICATIONS'));
    	$showHeader = $params->get('show_page_heading', 1);
    	
    	$this->assignRef('params', $params);
    	$this->assignRef('items', $projects);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('page', $model->getPagination());	
    	$this->assignRef('header', $pageHeader);
    	$this->assignRef('showHeader', $showHeader);
    	   	
    	$eArguments = array('projects', $this->getLayout());
		$mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
    
    private function _setFilters(){
    	$mainframe = JFactory::getApplication();    	
    	$params = $mainframe->getParams('com_jresearch');  
    	$layout = $this->getLayout();
    	
        $showFilterByTeam = $params->get('projects_show_filterby_team', 0);
        $showFilterByArea = $params->get('projects_show_filterby_area', 0);        
        $showFilterByStatus = $params->get('projects_show_filterby_status', 0);        
        $showFilterByYear = $params->get('projects_show_filterby_year', 0);        
        $showFilterBySearch = $params->get('projects_show_filterby_search', 0);
        $showFilterByAuthor = $params->get('projects_show_filterby_author', 0);        
    	
    	$filter = $this->_projectsFilter($layout,
    		$showFilterByTeam,
    		$showFilterByArea,
    		$showFilterByYear,
    		$showFilterBySearch,
    		$showFilterByStatus,
    		$showFilterByAuthor
    	);
    	
    	$this->assignRef('filter', $filter);
    }

	/**
	 * Returns div-container with project filters, can be activated with given parameter switches
	 *
	 * @param string $layout
	 * @param bool $bTeams
	 * @param bool $bAreas
	 * @param bool $bYear
	 * @param bool $bSearch
	 * @param bool $bType
	 * @param bool $bAuthors
	 * @return string
	 */
	private function _projectsFilter($layout, $bTeams = 0, $bAreas = 0, $bYear = 0, $bSearch = 0, $bStatus = 0, $bAuthors = 0)
	{
		jresearchimport('helpers.projects', 'jresearch.admin');
						
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();		
		
		$lists = array();
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		$this->state = $this->get('State');
		
		if($bSearch == 1)
        {
    		$filter_search = $this->state->get('com_jresearch.projects.filter_search');
     		$lists['search'] = JText::_('JRESEARCH_FILTER').': <input type="text" name="filter_search" id="filter_search" value="'.htmlentities($filter_search).'" class="text_area" onchange="document.adminForm.submit();" />
								<button onclick="document.adminForm.submit();">'.JText::_('JRESEARCH_GO').'</button> <button onclick="document.adminForm.filter_search.value=\'\';document.adminForm.submit();">'
								.JText::_('JRESEARCH_RESET').'</button>';
    	}
    	
		if($bStatus == 1)
    	{
    		// Publication type filter    		
			$filter_status =  $this->state->get('com_jresearch.projects.filter_status');
			$lists['status'] = JHTML::_('jresearchhtml.statuslist', array('name' => 'filter_status', 'selected' => $filter_status, 'attributes' => $js) );
    	}
    	
		if($bYear == 1){
			$filter_year =  $this->state->get('com_jresearch.projects.filter_year');
        	$years = JResearchProjectsHelper::getYears();
        	$lists['year'] = JHTML::_('jresearchhtml.years', $years, array('name' => 'filter_year', 'selected' => $filter_year, 'attributes' => $js));
    	}
    	
    	if($bAuthors == 1)
    	{
    		$filter_author = $this->state->get('com_jresearch.projects.filter_author');
        	$authors = JResearchProjectsHelper::getAllAuthors();
        	$lists['authors'] = JHTML::_('jresearchhtml.authors', $authors, array('name' => 'filter_author', 'selected' => $filter_author, 'attributes' => $js));
    	}
		
/*		if($bTeams == 1)
		{
			//Team filter
			$teamsOptions = array();  
	    	$filter_team = $this->state->get('com_jresearch.projects.filter_team');;    		
    		$teams = JResearchTeamsHelper::getTeams();
        	      
	        $teamsOptions[] = JHTML::_('select.option', -1 ,JText::_('JRESEARCH_ALL_TEAMS'));
	        foreach($teams as $t)
	        {
	    		$teamsOptions[] = JHTML::_('select.option', $t->id, $t->name);
	    	}    		
	    	$lists['teams'] = JHTML::_('select.genericlist',  $teamsOptions, 'filter_team', 'class="inputbox" size="1" '.$js, 'value', 'text', $filter_team );
    	}*/
    	
    	if($bAreas == 1)
    	{
        	$filter_area = $this->state->get('com_jresearch.projects.filter_area');    		
        	$lists['area'] = JHTML::_('jresearchhtml.researchareas', array('name' => 'filter_area', 'selected' => $filter_area, 'attributes' => $js), array(array('id' => '-1', 'name' => JText::_('JRESEARCH_RESEARCH_AREA'))));    		
    	}
    	
    	return '<div style="float: left">'.implode('</div><div style="float: left;">', $lists).'</div>';
	}    
    
}
?>