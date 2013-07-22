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

/**
 * HTML View class for management of research areas information in
 * JResearch Component frontend
 *
 */
class JResearchViewResearchArea extends JResearchView
{
    public function display($tpl = null)
    {
     	$arguments = array('researcharea');
    	$doc = JFactory::getDocument();
        $mainframe = JFactory::getApplication('site');
        $id = JRequest::getVar('id');
        $pathway = $mainframe->getPathway();
    	$params = $mainframe->getPageParameters('com_jresearch');
    	
    	$publications_view_all = JRequest::getVar('publications_view_all', 0);
    	$projects_view_all = JRequest::getVar('projects_view_all', 0);    	    	
    	$theses_view_all = JRequest::getVar('theses_view_all', 0);
    	$showMembers = $params->get('area_show_members', 1);
    	$showPublications = $params->get('area_show_publications', 1);
    	
        // The uncategorized view is not retrieved
    	if($id == 1 || empty($id)){
            JError::raiseError(404, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
            return;
    	}

        $model = $this->getModel();
        $area = $model->getItem();
        if($area === false){
            JError::raiseWarning(1, JText::_('JRESEARCH_AREA_NOT_FOUND'));
            return;
        }
        
        if($showMembers == 1){
	        $members = $model->getStaffMembers('all');
	        $this->assignRef('members', $members);
        }    
               
        $pathway->addItem($area->alias, 'index.php?option=com_jresearch&view=researcharea&id='.$area->id);
        $arguments[] = $area;
        $latestPublications = $params->get('area_number_last_publications', 5);
        if($publications_view_all == 0)	
            $publications = $model->getLatestPublications($latestPublications);
    	else
            $publications = $model->getLatestPublications();
    	
        $this->assignRef('publications', $publications);
    	$this->assignRef('npublications', $model->countPublications());    	

    	$latestProjects = $params->get('area_number_last_projects', 5);    	
        if($projects_view_all == 0)
            $projects = $model->getLatestProjects($latestProjects);
        else
            $projects = $model->getLatestProjects();

    	$this->assignRef('projects', $projects);
    	$this->assignRef('nprojects', $model->countProjects($area->id));		

    	$latestTheses = $params->get('area_number_last_theses', 5);    	
    	if($theses_view_all == 0)
            $theses = $model->getLatestTheses($area->id, $latestTheses);
        else
            $theses = $model->getLatestTheses($area->id);

    	$facilities = $model->getFacilities($area->id);
    		
    	$description = str_replace('<hr id="system-readmore" />', '', $area->description);	
    		
    	$applyStyle = $params->get('publications_apply_style');
    	$configuredCitationStyle = $params->get('citationStyle', 'APA');
    	$format = $params->get('staff_format', 'last_first');
    	
    	if($applyStyle){
            // Require publications lang package
            $lang = JFactory::getLanguage();
            $lang->load('com_jresearch.publications');
    	}

    	$this->assignRef('showMembers', $showMembers);
    	$this->assignRef('showPublications', $showPublications);
    	$this->assignRef('theses', $theses);
    	$this->assignRef('ntheses', $model->countTheses());    	
    	$this->assignRef('facilities', $facilities);
    	$this->assignRef('publications_view_all', $publications_view_all);
    	$this->assignRef('projects_view_all', $projects_view_all);    	
    	$this->assignRef('theses_view_all', $theses_view_all);
        $this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
        $this->assignRef('description', $description);
        $this->assignRef('applyStyle', $applyStyle);        
    	$this->assignRef('style', $configuredCitationStyle);
    	$this->assignRef('format', $format); 
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement', 'horizontal'));    	
        
        $mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterDisplayJResearchEntity', $arguments);
    }
}

?>