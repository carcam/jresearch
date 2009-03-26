<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible of displaying a single research area information.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of research areas information in
 * JResearch Component frontend
 *
 */



class JResearchViewResearchArea extends JView
{
    public function display($tpl = null)
    {
    	global $mainframe;
    	$doc = JFactory::getDocument();
    	
    	// Require css and styles
    	$id = JRequest::getInt('id', 1);
    	$publications_view_all = JRequest::getVar('publications_view_all', 0);
    	$projects_view_all = JRequest::getVar('projects_view_all', 0);    	    	
    	$theses_view_all = JRequest::getVar('theses_view_all', 0);
    	
       	if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return;
    	}
    	
    	if($id == 1){
    		JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
    		return;
    	}

    	    	
        $model =& $this->getModel();
        $area = $model->getItem($id);
        if(empty($area)){
			JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			return;
		}
        
        
        $members = $model->getStaffMembers($id);
        //Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');

    	if(!$area->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			return;
		}
        
		$latestPublications = $params->get('area_number_last_publications', 5);        
        if($publications_view_all == 0)	
    		$publications = $model->getLatestPublications($area->id, $latestPublications);	
    	else
    		$publications = $model->getLatestPublications($area->id);	    		
    	
		$this->assignRef('publications', $publications);
    	$this->assignRef('npublications', $model->countPublications($area->id));    	

    	$latestProjects = $params->get('area_number_last_projects', 5);    	
        if($projects_view_all == 0)
    		$projects = $model->getLatestProjects($area->id, $latestProjects);
		else
    		$projects = $model->getLatestProjects($area->id);    				    	

    	$this->assignRef('projects', $projects);
    	$this->assignRef('nprojects', $model->countProjects($area->id));		

    	$latestTheses = $params->get('area_number_last_theses', 5);    	
    	if($theses_view_all == 0)
    		$theses = $model->getLatestTheses($area->id, $latestTheses);
		else
    		$theses = $model->getLatestTheses($area->id);		
    	
    	$doc->setTitle(JText::_('JRESEARCH_RESEARCH_AREA').' - '.$area->name);	
    		
    	$this->assignRef('theses', $theses);
    	$this->assignRef('ntheses', $model->countTheses($area->id));    	

    	$this->assignRef('publications_view_all', $publications_view_all);
    	$this->assignRef('projects_view_all', $projects_view_all);    	
    	$this->assignRef('theses_view_all', $theses_view_all);    	
		$this->assignRef('members', $members);
        $this->assignRef('area', $area);
        parent::display($tpl);
    }
}

?>
