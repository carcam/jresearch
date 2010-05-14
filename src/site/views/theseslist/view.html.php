<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of presentation of
* theses list in frontend
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for presentation of theses list in frontend.
 *
 */

class JResearchViewThesesList extends JResearchView
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
        
        $eArguments = array('theses', $layout);
		
		$mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
    
    /**
    * Display the list of published theses.
    */
    private function _displayDefaultList(){
      	global $mainframe;
    	
      	$doc = JFactory::getDocument();
      	$params = $this->getParams();
      	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');

        $filterByArea = $params->get('theses_filterby_researcharea', '0');
        if($filterByArea == '1'){
            JRequest::setVar('filter_area', $params->get('theses_filter_area', '1'));
        }

        $filterByDegree = $params->get('theses_filterby_degree', '0');
        if($filterByArea == '1'){
            JRequest::setVar('filter_degree', $params->get('theses_filter_degree', 'bachelor'));
        }


        $filterByStatus = $params->get('theses_filterby_status', '0');
        if($filterByStatus == '1'){
            JRequest::setVar('filter_status', $params->get('theses_status_filter', 'not_started'));
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