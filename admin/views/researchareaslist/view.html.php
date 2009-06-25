<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of projects
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for management of projects lists in JResearch Component backend
 *
 */

class JResearchAdminViewResearchAreasList extends JResearchView
{
    function display($tpl = null)
    {
		global $mainframe;	
		JResearchToolbar::researchAreasListToolbar();
		$model = &$this->getModel();
		$items = $model->getData(null, false, true);
	     
		// Filters and pagination
		$lists = array();    	
	  	$filter_order = $mainframe->getUserStateFromRequest('researchAreasfilter_order', 'filter_order', 'name');
	  	$filter_order_Dir = $mainframe->getUserStateFromRequest('researchAreasfilter_order', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('researchAreasfilter_state', 'filter_state');
	  	$filter_search = $mainframe->getUserStateFromRequest('researchAreasfilter_search', 'filter_search');
	  	
	  	$lists['order_Dir'] = $filter_order_Dir;
	  	$lists['order'] = $filter_order;
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$lists['search'] = $filter_search;
	
	   	$this->assignRef('items', $items);
	   	$this->assignRef('lists', $lists );
	   	$this->assignRef('page', $model->getPagination());
	   	
	   	$eArguments = array('researchareas');
        $mainframe->triggerEvent('onBeforeListJResearchEntities', $eArguments);
        
        parent::display($tpl);
        
        $mainframe->triggerEvent('onAfterListJResearchEntities', $eArguments);
    }
}
?>
