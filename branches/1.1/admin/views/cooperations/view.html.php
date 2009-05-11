<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of cooperations
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 */

class JResearchAdminViewCooperations extends JView
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
	
        $eArguments = array('cooperations');
        $mainframe->triggerEvent('onBeforeListJresearchEntities', $eArguments);
        
        parent::display($tpl);
        
        $mainframe->triggerEvent('onAfterListJresearchEntities', $eArguments);
    }
    
    /**
    * Invoked when the user has selected the default view
    */
    private function _displayDefaultList()
    {
    	global $mainframe;
    	
    	//Toolbar
    	//JResearchToolbar::staffAdminListToolbar();
    	
    	//Get the model
    	$model =& $this->getModel();
    	$coops = $model->getData(null, false, true);
    	
		// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('coopsfilter_order', 'filter_order', 'ordering');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('coopsfilter_order_Dir', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('coopsfilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('coopsfilter_search', 'filter_search');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		
		//Ordering allowed ?
		$ordering = ($lists['order'] == 'ordering');
		
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		$lists['search'] = $filter_search;
    	
    	$this->assignRef('items', $coops);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('page', $model->getPagination());
    	$this->assignRef('ordering', $ordering);
    }
}
?>
