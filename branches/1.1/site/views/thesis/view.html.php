<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single publication in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for presentation of a single thesis 
 * information.
 *
 */

class JResearchViewThesis extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	$arguments = array('thesis');
    	
    	$result = true;
        $layout = &$this->getLayout();

        switch($layout){
        	case 'default':
        		$result = $this->_displayThesis($arguments);
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
    * Display the information of a thesis.
    */
    private function _displayThesis(&$arguments){
      	global $mainframe;
    	$id = JRequest::getInt('id');
   		$doc =& JFactory::getDocument();
   		$statusArray = array('not_started'=>JText::_('JRESEARCH_NOT_STARTED'), 'in_progress'=>JText::_('JRESEARCH_IN_PROGRESS'), 'finished'=>JText::_('Finished'));
   		$degreeArray = array('bachelor'=>JText::_('JRESEARCH_BACHELOR'), 'master'=>JText::_('JRESEARCH_MASTER'), 'phd'=>JText::_('JRESEARCH_PHD'));

   		if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return false;
    	}
    	//Get the model
    	$model =& $this->getModel();
    	$thesis = $model->getItem($id);
    	
    	if(empty($thesis)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
    		return false;
    	}
    	
		if(!$thesis->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_THESIS_NOT_FOUND'));
			return false;
		}
        
		JResearchPluginsHelper::onPrepareJResearchContent('thesis', $thesis);
		$arguments[] = $id;
		
		$doc->setTitle(JText::_('JRESEARCH_THESIS').' - '.$thesis->title);
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($thesis->id_research_area);
    	$params = $mainframe->getPageParameters('com_jresearch');    	

    	// Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));    	
    	$this->assignRef('thesis', $thesis, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('degreeArray', $degreeArray);
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
    	
    	return true;

    }
}

?>
