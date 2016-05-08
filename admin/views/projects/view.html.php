<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of projects
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for management of projects lists in JResearch Component backend
 *
 */

class JResearchAdminViewProjects extends JResearchView
{
    function display($tpl = null) {
    	$mainframe = JFactory::getApplication();
        $jinput = JFactory::getApplication()->input;        
        $option = $jinput->get('option');
        $params = JComponentHelper::getParams('com_jresearch');        
        jresearchimport('helpers.projects', 'jresearch.admin');
        jresearchimport('helpers.publications', 'jresearch.admin');        
        JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'html');
		
    	JResearchToolbar::projectsAdminListToolbar();
    	
        $db = JFactory::getDBO();
    	JHTML::_('behavior.tooltip');
		
        // Get the default model
    	$model = $this->getModel();
    	$items = $model->getItems();
    	
    	// Ordering variables
    	$lists = array();    	

    	// Get the user state of the order and direction 
    	$filter_order = $model->getState('com_jresearch.projects.filter_order', 'published');
    	$filter_order_Dir = $model->getState('com_jresearch.projects.filter_order_Dir', 'ASC');
        $filter_state = $model->getState('com_jresearch.projects.filter_state');
        $filter_status = $model->getState('com_jresearch.projects.filter_status');        
        $filter_start_date = $model->getState('com_jresearch.projects.filter_start_date');
        $filter_area = $model->getState('com_jresearch.projects.filter_area');
    	$filter_search = $model->getState('com_jresearch.projects.filter_search');
        $filter_author = $model->getState('com_jresearch.projects.filter_author');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        $js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
        
        // State filter
        $lists['state'] = JHTML::_('jresearchhtml.publishedlist', array('name' => 'filter_state', 'selected' => $filter_state, 'attributes' => $js));
        $lists['search'] = $filter_search;
		
        // Year filter
        $years = JResearchProjectsHelper::getYears();
        $lists['year'] = JHTML::_('jresearchhtml.years', $years, array('name' => 'filter_start_date', 'selected' => $filter_start_date, 'attributes' => $js));

        // Research Area filter
        $lists['area'] = JHTML::_('jresearchhtml.researchareas', array('name' => 'filter_area', 'selected' => $filter_area, 'attributes' => $js), array(array('id' => '-1', 'name' => JText::_('JRESEARCH_RESEARCH_AREA'))));
        
        $authors = JResearchProjectsHelper::getAllAuthors();
        $lists['authors'] = JHTML::_('jresearchhtml.authors', $authors, array('name' => 'filter_author', 'selected' => $filter_author, 'attributes' => $js));

        $lists['status'] = JHTML::_('jresearchhtml.statuslist', array('name' => 'filter_status', 'selected' => $filter_status, 'attributes' => $js) );

        $this->assignRef('lists', $lists);        
    	$this->assignRef('items', $items);
        $page = $model->getPagination();
    	$this->assignRef('page', $page);
        $this->assignRef('params', $params);        
    	parent::display($tpl);
    }
}

?>
