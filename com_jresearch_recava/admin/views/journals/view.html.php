<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Journals
* @copyright	Copyright (C) 2010 Luis Galárraga
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of journals
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for management of projects lists in JResearch Component backend
 *
 */

class JResearchAdminViewJournals extends JResearchView
{
    function display($tpl = null)
    {
		global $mainframe;	
		JResearchToolbar::financiersAdminListToolbar();
		$model = $this->getModel();
		$items = $model->getData(null, false, true);
		   
		// Filters and pagination
		$lists = array();    	
		$filter_order = $mainframe->getUserStateFromRequest('journalsfilter_order', 'filter_order', 'name');
		$filter_order_Dir = $mainframe->getUserStateFromRequest('journalsfilter_order', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('journalsfilter_state', 'filter_state');
		$filter_search = $mainframe->getUserStateFromRequest('journalsfilter_search', 'filter_search');
		  	
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$lists['search'] = $filter_search;
		
		$this->assignRef('items', $items);
		$this->assignRef('lists', $lists );
		$this->assignRef('page', $model->getPagination());
		
		$eArguments = array('journals');
        $mainframe->triggerEvent('onBeforeListJResearchEntities', $eArguments);
        
        parent::display($tpl);
        
        $mainframe->triggerEvent('onAfterListJResearchEntities', $eArguments);
    }
}
?>
