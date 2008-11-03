<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	MtM
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of projects
* in the backend.
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for management of mdm lists in JResearch Component backend
 *
 */
class JResearchAdminViewMdmList extends JView
{
    function display($tpl = null)
    {
     	global $mainframe;	
 	   	JResearchToolbar::mdmListToolbar();
 	   	
 	   	//Get mdm
       	$model = &$this->getModel();
      	$items = $model->getData(null, false, true);
      	
      	//User model
      	$uModel = &$this->getModel('Member');

      	// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('mdmfilter_order', 'filter_order', 'month');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('mdmfilter_order_Dir', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('mdmfilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('mdmfilter_search', 'filter_search');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		$lists['search'] = $filter_search;

     	$this->assignRef('items', $items);
     	$this->assignRef('user', $uModel);
     	$this->assignRef('lists', $lists);
     	$this->assignRef('page', $model->getPagination());
     	
     	parent::display($tpl);
    }
}
?>
