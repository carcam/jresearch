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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of theses list in frontend.
 *
 */

class JResearchViewThesesList extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        switch($layout){
        		case 'default':
        			$this->_displayDefaultList();
        			break;
        }
	
        parent::display($tpl);
    }
    
    /**
    * Display the list of published theses.
    */
    private function _displayDefaultList(){
      	global $mainframe;
    	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	$theses =  $model->getData(null, true, true);   
    	
    	$this->assignRef('params', $params);
    	$this->assignRef('items', $theses);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('page', $model->getPagination());	

    }
}

?>