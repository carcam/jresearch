<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright	Copyright (C) 2008 Luis Galarraga.
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

class JResearchAdminViewResearchAreas extends JResearchView
{
    

    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        JResearchToolbar::researchAreasListToolbar();

        $model = $this->getModel();
        $items = $model->getItems();
        

        // Filters and pagination
        $lists = array();
        $this->state = $this->get('State');
        $filter_order = $this->state->get('com_jresearch.researchareas.filter_order');
        $filter_order_Dir = $this->state->get('com_jresearch.researchareas.filter_order_Dir');
        $filter_state = $this->state->get('com_jresearch.researchareas.filter_state');
        $filter_search = $this->state->get('com_jresearch.researchareas.filter_search');

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        // State filter
        $lists['state'] = JHTML::_('grid.state', $filter_state);
        $js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
        $lists['search'] = $filter_search;
        
        $this->assignRef('items', $items);
        $this->assignRef('lists', $lists );
        $this->assignRef('page', $model->getPagination());
        parent::display($tpl);
    }
}
?>