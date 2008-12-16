<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single facility in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of a single facility 
 * information.
 *
 */

class JResearchViewFacility extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        
        switch($layout)
        {
        	case 'default':
        		$this->_displayFacility();
        		break;
        }
	
        parent::display($tpl);
    }
    
    /**
    * Display the information of a facility.
    */
    private function _displayFacility(){
      	global $mainframe;
      	
    	$id = JRequest::getInt('id');
   		$doc =& JFactory::getDocument();

   		if(empty($id))
   		{
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return;
    	}
    	
    	//Get the model
    	$model =& $this->getModel();
    	$fac = $model->getItem($id);
    	
		if(!$fac->published)
		{
			JError::raiseWarning(1, JText::_('JRESEARCH_PROJECT_NOT_FOUND'));
			return;
		}		    	
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($fac->id_research_area);
    	
    	$doc->setTitle('Facility - '.$area->name.' - '.$fac->name);
    			
    	// Bind variables for layout
    	$this->assignRef('fac', $fac);
    	$this->assignRef('area', $area);
    }
}

?>