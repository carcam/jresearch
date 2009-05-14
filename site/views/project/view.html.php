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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of a single project 
 * information.
 *
 */

class JResearchViewProject extends JView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	$arguments = array('project');
    	
    	$result = true;
        $layout = &$this->getLayout();

        switch($layout){
        	case 'default':
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
    private function _displayProject(&$arguments){
      	global $mainframe;
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
    	
    	if(empty($project)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
    		return false;
    	}
    	
		if(!$project->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			return false;
		}

		$arguments[] = $id;
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($project->id_research_area);
    	$params = $mainframe->getPageParameters('com_jresearch');

    	$doc->setTitle(JText::_('JRESEARCH_PROJECT').' - '.$project->title);
    	// Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));
    	$this->assignRef('project', $project);
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('area', $area);
    	
    	return true;

    }
}

?>
