<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of presentation of
* facility list in frontend
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of facilities list in frontend.
 *
 */

class JResearchViewFacilities extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        
        switch($layout)
        {
       		case 'default':
       			$this->_displayDefaultList();
       			break;
        }
	
        parent::display($tpl);
    }
    
    /**
    * Display the list of published projects.
    */
    private function _displayDefaultList()
    {
      	global $mainframe;
    	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	
    	$params = $mainframe->getParams();    
		
    	$facs =  $model->getData(null, true, true);   
    	
    	$this->assignRef('params', $params);
    	$this->assignRef('items', $facs);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('page', $model->getPagination());	

    }
}

?>