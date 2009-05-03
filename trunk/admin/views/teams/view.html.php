<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Teams
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
class JResearchAdminViewTeams extends JView
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
	
        $eArguments = array('list' => 'teams');
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
    	JResearchToolbar::teamsAdminListToolbar();
    	
    	//Get the model
    	$model =& $this->getModel();
    	$teams = $model->getData(null, false, true);
    	
		// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('teamsfilter_order', 'filter_order', 'name');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('teamsfilter_order_Dir', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('teamsfilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('teamsfilter_search', 'filter_search');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$lists['search'] = $filter_search;
    	
    	$this->assignRef('items', $teams);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('page', $model->getPagination());
    }
}
?>
