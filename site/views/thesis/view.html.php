<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Theses
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single publication in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of a single thesis 
 * information.
 *
 */

class JResearchViewThesis extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        switch($layout){
        	case 'default':
        		$this->_displayThesis();
        		break;
        }
	
        parent::display($tpl);
    }
    
    /**
    * Display the information of a thesis.
    */
    private function _displayThesis(){
      	global $mainframe;
    	$id = JRequest::getInt('id');
   		$doc =& JFactory::getDocument();
   		$statusArray = array('not_started'=>JText::_('JRESEARCH_NOT_STARTED'), 'in_progress'=>JText::_('JRESEARCH_IN_PROGRESS'), 'finished'=>JText::_('Finished'));
   		$degreeArray = array('bachelor'=>JText::_('JRESEARCH_BACHELOR'), 'master'=>JText::_('JRESEARCH_MASTER'), 'phd'=>JText::_('JRESEARCH_PHD'));

   		if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return;
    	}
    	//Get the model
    	$model =& $this->getModel();
    	$thesis = $model->getItem($id);
    	
		if(!$thesis->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_THESIS_NOT_FOUND'));
			return;
		}		    	
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($thesis->id_research_area);
    			
    	// Bind variables for layout
    	$this->assignRef('thesis', $thesis);
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('degreeArray', $degreeArray);
    	$this->assignRef('area', $area);

    }
}

?>
