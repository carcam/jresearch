<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Transfers
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of courses
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 */

class JResearchAdminViewTransfers extends JResearchView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        global $mainframe;
        
        switch($layout)
        {
        		case 'default':
        			$this->_displayDefaultList();
        			break;
        }
	
        $eArguments = array('transfers');
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
    	
    	//Get the model
    	$model =& $this->getModel();
    	$transfers = $model->getData(null, false, true);
    	
		// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('transferfilter_order', 'filter_order', 'ordering');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('transferfilter_order_Dir', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('transferfilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('transferfilter_search', 'filter_search');
    	
    	$lists = array(
    		'order_Dir' => $filter_order_Dir,
    		'order' => $filter_order,
    		'state' => JHTML::_('grid.state', $filter_state),
    		'search' => $filter_search
    	);
    	
    	$this->assignRef('items', $transfers);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('page', $model->getPagination());
    }
}
?>
