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
      	$params = $this->getParams();
      	$Itemid = JRequest::getVar('Itemid');
      	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');

        $filterByArea = $params->get('theses_filterby_researcharea', '0');
        if($filterByArea == '1'){
            JRequest::setVar('filter_area', $params->get('theses_area_filter', '1'));
        }else{
            $mainframe->setUserState('thesesfilter_area'.$Itemid, null);
        }

        $filterByDegree = $params->get('theses_filterby_degree', '0');
        if($filterByDegree == '1'){
            JRequest::setVar('filter_degree', $params->get('theses_degree_filter', 'bachelor'));
        }else{
            $mainframe->setUserState('thesesfilter_degree'.$Itemid, null);
        }

        $filterByStatus = $params->get('theses_filterby_status', '0');
        if($filterByStatus == '1'){
            JRequest::setVar('filter_status', $params->get('theses_status_filter', 'not_started'));
        }else{
            $mainframe->setUserState('thesesfilter_status'.$Itemid, null);
        }

        $defaultSorting = $params->get('theses_default_sorting', 'start_date');
        JRequest::setVar('filter_order', $defaultSorting);

        $ordering = $params->get('theses_order', 'asc');
        JRequest::setVar('filter_order_Dir', $ordering);

    	$theses =  $model->getData(null, true, true); 
    	$doc->setTitle(JText::_('JRESEARCH_THESES'));  
    	
    	$this->assignRef('params', $params);
    	$this->assignRef('items', $theses);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('page', $model->getPagination());
    	
    }
}

?>