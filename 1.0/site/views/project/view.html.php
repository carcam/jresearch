<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage	JResearch
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
 * @package   JResearch
 */

class JResearchViewProject extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        switch($layout){
        	case 'default':
        		$value = $this->_displayProject();
        		break;
        }
	
        if(!empty($value))
        	parent::display($tpl);
    }
    
    /**
    * Display the information of a project.
    */
    private function _displayProject(){
      	global $mainframe;
    	$id = JRequest::getInt('id');
   		$doc =& JFactory::getDocument();
   		$statusArray = array('not_started'=>JText::_('JRESEARCH_NOT_STARTED'), 'in_progress'=>JText::_('JRESEARCH_IN_PROGRESS'), 'finished'=>JText::_('Finished'));

   		if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return;
    	}
    	//Get the model
    	$model =& $this->getModel();
    	$project = $model->getItem($id);
    	
		if(!$project->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_PROJECT_NOT_FOUND'));
			return;
		}		    	
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($project->id_research_area);
    			
    	// Bind variables for layout
    	$this->assignRef('project', $project);
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('area', $area);
    	return true;

    }
}

?>