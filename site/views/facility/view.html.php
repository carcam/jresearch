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



/**
 * HTML View class for presentation of a single facility 
 * information.
 *
 */

class JResearchViewFacility extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	
    	$arguments = array('facility');
    	
        $layout = &$this->getLayout();
        $result = true;
        
        switch($layout)
        {
        	case 'default':
        		$result = $this->_displayFacility($arguments);
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
    * Display the information of a facility.
    */
    private function _displayFacility(&$arguments){
      	
    	$id = JRequest::getInt('id');
   		$doc =& JFactory::getDocument();

   		if(empty($id))
   		{
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return false;
    	}
    	
    	//Get the model
    	$model =& $this->getModel();
    	$fac = $model->getItem($id);
    	
		if(!$fac->published)
		{
			JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			return false;
		}
        JResearchPluginsHelper::onPrepareJResearchContent('facility', $result);		

		$arguments[] = $id;
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($fac->id_research_area);
    	
    	$doc->setTitle(JText::_('JRESEARCH_FACILITY').' - '.$area->name.' - '.$fac->name);
    			
    	// Bind variables for layout
    	$this->assignRef('fac', $fac, JResearchFilter::OBJECT_XHTML_SAFE, array('exclude_keys' => array('description')));
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);

    	return true;
    }
}

?>
