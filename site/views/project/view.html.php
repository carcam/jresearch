<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single publication in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for presentation of a single project 
 * information.
 *
 */

class JResearchViewProject extends JResearchView
{
    function display($tpl = null){
       $this->_displayProject($tpl);
    }
    
    /**
    * Display the information of a project.
    */
    private function _displayProject($tpl = null){
      	$mainframe = JFactory::getApplication();
      	$params = $mainframe->getParams();
      	$user = JFactory::getUser();
      	$session = JFactory::getSession();
      	$publications_view_all = JRequest::getVar('publications_view_all', 0);
      	
    	$id = JRequest::getInt('id');
		$doc =& JFactory::getDocument();
		$statusArray = array('not_started'=>JText::_('JRESEARCH_NOT_STARTED'), 'in_progress'=>JText::_('JRESEARCH_IN_PROGRESS'), 'finished'=>JText::_('Finished'));

		if(empty($id)){
			JError::raiseWarning(404, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
			return;
		}
    	//Get the model
    	$model = $this->getModel();
    	$project = $model->getItem();
    	
    	if($project === false){
    		JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
    		return;
    	}
    	
    	if(!JResearchAccessHelper::itemAccessAllowed($project, $user->get('id'))){
    		JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
    		return false;
    	}
    	
    	$this->addPathwayItem($project->alias, 'index.php?option=com_jresearch&view=project&id='.$project->id);
        //If the publication was visited in the same session, do not increment the hit counter
        if(!$session->get('visited', false, 'com_jresearch.project.'.$id)){
             $session->set('visited', true, 'com_jresearch.project.'.$id);
             $project->hit();
        }
    	
        $arguments = array($project, 'project');		
        
        $nPubs = 0;
        if(!$publications_view_all)
        	$nPubs = $params->get('projects_number_publications', 5);
        
        $publications = $model->getPublications($nPubs);
        $this->assignRef('publications', $publications);
        $this->assignRef('npublications', $model->countPublications($project->id));    		    		
        
	    $applyStyle = $params->get('publications_apply_style', 1);
	    $configuredCitationStyle = $params->get('citationStyle', 'APA');	    
	    if($applyStyle){
	    	// Require publications lang package
	        $lang = JFactory::getLanguage();
	        $lang->load('com_jresearch.publications');
	    }        

    	$description = str_replace('<hr id="system-readmore" />', '', trim($project->description));
    	$format = $params->get('staff_format', 'last_first') == 'last_first'?1:0;
        
    	$doc->setTitle(JText::_('JRESEARCH_PROJECT').' - '.$project->title);

    	$this->assignRef('applyStyle', $applyStyle);
    	$this->assignRef('style', $configuredCitationStyle);    
    	$this->assignRef('publications_view_all', $publications_view_all);	

    	// Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));
    	$this->assignRef('project', $project, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);

    	$this->assignRef('description', $description);
    	$this->assignRef('enableThumbnails', $params->get('thumbnail_enable', 1));
        $this->assignRef('showHits', $params->get('show_hits'));
        $this->assignRef('format', $format);
    	
    	$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);        
       	parent::display($tpl);       	
       	$mainframe->triggerEvent('onAfterDisplayJResearchEntity', $arguments);
    }
}

?>
