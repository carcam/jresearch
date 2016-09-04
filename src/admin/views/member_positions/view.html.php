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
        JHtml::_('behavior.multiselect');
        JHtml::_('formbehavior.chosen', 'select');
 	
       	$model = $this->getModel();
      	$items = $model->getItems();

      	// Filters and pagination
        $lists = array();    	
    	$filter_order = $model->getState('com_jresearch.member_positions.filter_order');
    	$filter_order_Dir = $model->getState('com_jresearch.member_positions.filter_order_Dir');
        $filter_state = $model->getState('com_jresearch.member_positions.filter_state');
    	$filter_search = $model->getState('com_jresearch.member_positions.filter_search');
    	$filter_limit = $model->getState('list.limit');    	
    	
    	$lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        // State filter
        $lists['state'] = JHTML::_('grid.state', $filter_state);
        $js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
        $lists['search'] = $filter_search;

        //Ordering allowed ?
        $ordering = ($lists['order'] == 'ordering');
        $lists['limit'] = JHTML::_('jresearchhtml.limit', array('name' => 'limit', 'selected' => $filter_limit, 'attributes' => $js));
		
     	$this->assignRef('items', $items);
     	$this->assignRef('lists', $lists );
        $page = $model->getPagination();
     	$this->assignRef('page', $page);
    	$this->assignRef('ordering', $ordering);

     	$eArguments = array('member_positions');
		
     	$mainframe->triggerEvent('onBeforeListJresearchEntities', $eArguments);				
        parent::display($tpl);		
        $mainframe->triggerEvent('onAfterListJresearchEntities', $eArguments);
    }
}

?>
