<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
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

class JResearchAdminViewMember_positions extends JResearchView
{
    function display($tpl = null)
    {
     	$mainframe = JFactory::getApplication();	
 		JResearchToolbar::member_positionListToolbar();
 	
       	$model = $this->getModel();
      	$items = $model->getItems();

      	// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('com_jresearch.member_positions.filter_order', 'filter_order', 'ordering');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('com_jresearch.member_positions.filter_order_Dir', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('com_jresearch.member_positions.filter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('com_jresearch.member_positions.filter_search', 'filter_search');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		$lists['search'] = $filter_search;

        //Ordering allowed ?
        $ordering = ($lists['order'] == 'ordering');
		
     	$this->assignRef('items', $items);
     	$this->assignRef('lists', $lists );
     	$this->assignRef('page', $model->getPagination());

     	$eArguments = array('member_positions');
		
     	$mainframe->triggerEvent('onBeforeListJresearchEntities', $eArguments);				
		parent::display($tpl);		
		$mainframe->triggerEvent('onAfterListJresearchEntities', $eArguments);

    }
}

?>
