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
    function display($tpl = null)
    {
	    global $mainframe;
    	$arguments = array('project');    	
    	$result = true;
        $layout = $this->getLayout();

        switch($layout){
        	case 'default':
        	default:
        		$result = $this->_displayProject($arguments);
        		break;
        }
	
        if($result)
        {
 	    	$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
	       	parent::display($tpl);
	       	
	       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
        }
    }
    
    /**
    * Display the information of a project.
    */
    private function _displayProject(array &$arguments){
      	global $mainframe;
      	require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'publications.php');
      	
    	$id = JRequest::getInt('id');
	$doc =& JFactory::getDocument();
	$statusArray = array('not_started'=>JText::_('JRESEARCH_NOT_STARTED'), 'in_progress'=>JText::_('JRESEARCH_IN_PROGRESS'), 'finished'=>JText::_('Finished'));

		if(empty($id)){
			JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
			return false;
		}
    	//Get the model
    	$model =& $this->getModel();
    	$project = $model->getItem($id);
    	
    	if(empty($project) || !$project->published){
    		JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
    		return false;
    	}
    	
    	$this->addPathwayItem($project->alias, 'index.php?option=com_jresearch&view=project&id='.$project->id);
    	$arguments[] = $id;
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($project->id_research_area);
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$coops = $project->getCooperations;
    	$description = str_replace('<hr id="system-readmore" />', '', trim($project->description));

    	$doc->setTitle(JText::_('JRESEARCH_PROJECT').' - '.$project->title);
    	// Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));
    	$this->assignRef('project', $project, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('coops', $coops);
    	$this->assignRef('description', $description);
    	$this->assignRef('enableThumbnails', $params->get('thumbnail_enable', 1));
    	
    	return true;    	

    }
}

?>
