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

class JResearchViewProjectsList extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
        $layout = &$this->getLayout();
        switch($layout){
        		case 'default':
        			$this->_displayDefaultList();
        			break;
        }
	
        $eArguments = array('projects', $layout);
		
		$mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
    
    /**
    * Display the list of published projects.
    */
    private function _displayDefaultList(){
      	global $mainframe;
    	
      	$doc = JFactory::getDocument();
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	
    	$params = $mainframe->getParams();
        $ids = explode(',',$params->get('project_id'));
        $filterByArea = $params->get('projects_filterby_researcharea', '0');
        if($filterByArea == '1'){
            JRequest::setVar('filter_area', $params->get('projects_filter_area', '1'));
        }
        
        $filterByStatus = $params->get('projects_filterby_status', '0');
        if($filterByStatus == '1'){
            JRequest::setVar('filter_status', $params->get('projects_status_filter', 'not_started'));
        }

        $defaultSorting = $params->get('projects_default_sorting', 'start_date');
        JRequest::setVar('filter_order', $defaultSorting);

        $ordering = $params->get('projects_order', 'asc');
        JRequest::setVar('filter_order_Dir', $ordering);

	$model->setIds($ids);
    	$projects =  $model->getData(null, true, true);   
    	
    	$doc->setTitle(JText::_('JRESEARCH_PROJECTS'));
    	$this->assignRef('params', $params);
    	$this->assignRef('items', $projects);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('page', $model->getPagination());	
    }
}

?>