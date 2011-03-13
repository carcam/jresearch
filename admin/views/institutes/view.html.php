<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Institutes
* @copyright	Copyright (C) 2009 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of institutes
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 */

class JResearchAdminViewInstitutes extends JResearchView 
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
	
        $eArguments = array('institutes');
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
    	JResearchToolbar::institutesAdminListToolbar();
    	
    	//Get the model
    	$model =& $this->getModel();
    	$institutes = $model->getData(null, false, true);
    	
		// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('institutesfilter_order', 'filter_order', 'ordering');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('institutesfilter_order_Dir', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('institutesfilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('institutesfilter_search', 'filter_search');
        
    	$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		
		//Ordering allowed ?
		$ordering = ($lists['order'] == 'ordering');
		
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$lists['search'] = $filter_search;
		
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit();"';
    	
    	$this->assignRef('items', $institutes);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('page', $model->getPagination());
    	$this->assignRef('ordering', $ordering);
    }
}
?>