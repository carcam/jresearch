<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of facilities
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of facilities lists in JResearch Component backend
 *
 */

class JResearchAdminViewFacilities extends JView
{
    function display($tpl = null)
    {
    	global $mainframe;
        $layout = &$this->getLayout();
        
        switch($layout)
        {
        		case 'default':
        			$this->_displayDefaultList();
        			break;
        }
	
        $eArguments = array('facilities');
        $mainframe->triggerEvent('onBeforeListJResearchEntities', $eArguments);
        
        parent::display($tpl);
        
        $mainframe->triggerEvent('onAfterListJResearchEntities', $eArguments);
    }
    
    /**
    * Invoked when the user has selected the default view
    */
    private function _displayDefaultList()
    {
    	global $mainframe;
    	
    	//Toolbar
    	JResearchToolbar::facilitiesAdminListToolbar();
    	
    	//Get the model
    	$model =& $this->getModel();
    	$facs = $model->getData(null, false, true);
    	$areaModel =& $this->getModel('researcharea');
    	
		// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('facsfilter_order', 'filter_order', 'ordering');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('facsfilter_order', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('facsfilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('facsfilter_search', 'filter_search');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		
		//Ordering allowed ?
		$ordering = ($lists['order'] == 'ordering');
		
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		$lists['search'] = $filter_search;
    	
    	$this->assignRef('items', $facs);
    	$this->assignRef('area', $areaModel);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('page', $model->getPagination());
    	$this->assignRef('ordering', $ordering);
    }
}
?>
