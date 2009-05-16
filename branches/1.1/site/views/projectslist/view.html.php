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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of projects list in frontend.
 *
 */

class JResearchViewProjectsList extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        switch($layout){
        		case 'default':
        			$this->_displayDefaultList();
        			break;
        }
	
        $eArguments = array('projects', $layout);
		
		$mainframe->triggerEvent('onBeforeListJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListJResearchEntities', $eArguments);
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