<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of staff list
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 */

class JResearchAdminViewStaff extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
        $layout = &$this->getLayout();
         
        switch($layout){
        		case 'add':
        			$this->_displayAddForm();
        			break;
        		case 'default':
        			$this->_displayDefaultList();
        			break;
        }
	
        $eArguments = array('staff');
		$mainframe->triggerEvent('onBeforeListJresearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListJresearchEntities', $eArguments);
    }
    
    /**
    * Invoked when the user has selected the default view for staff members
    * which is the admin list of members.
    */
    private function _displayDefaultList(){
        global $mainframe;
    	JResearchToolbar::staffAdminListToolbar();

        //Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	$members =  $model->getData(null, false, true);
    	
        // Filters and pagination
        $lists = array();
    	$filter_order = $mainframe->getUserStateFromRequest('stafffilter_order', 'filter_order', 'ordering');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('stafffilter_order_Dir', 'filter_order_Dir', 'ASC');
        $filter_state = $mainframe->getUserStateFromRequest('stafffilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('stafffilter_search', 'filter_search');
    	$filter_former = $mainframe->getUserStateFromRequest('stafffilter_former', 'filter_former');
    	$filter_area = $mainframe->getUserStateFromRequest('stafffilter_area', 'filter_area');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        // State filter
        $lists['state'] = JHTML::_('grid.state', $filter_state);
        $js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
        $lists['search'] = $filter_search;
        $lists['area'] = JHTML::_('jresearchhtml.researchareas', array('name' => 'filter_area', 'selected' => $filter_area, 'attributes' => 'onchange="document.adminForm.submit();"'), array(array('id' => null, 'name' => JText::_('JRESEARCH_RESEARCH_AREA'))));

        //Former member filter
        $formerHTML[] = JHTML::_('select.option', '0', JText::_('- Former Member -'));
        $formerHTML[] = JHTML::_('select.option', '-1', JText::_('No'));
        $formerHTML[] = JHTML::_('select.option', '1', JText::_('Yes'));
        $lists['former'] = JHTML::_('select.genericlist', $formerHTML, 'filter_former', 'class="inputbox" size="1" '.$js, 'value','text', $filter_former);

        //Ordering allowed ?
        $ordering = ($lists['order'] == 'ordering');
    	
    	$this->assignRef('items', $members);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('ordering', $ordering);
    	$this->assignRef('page', $model->getPagination());	

    }
	
	 /**
	 * Invoked when the user wants to import staff members from Joomla users
	 * table.
	 */	    
    private function _displayAddForm(){
    	global $mainframe;
    	
    	JRequest::setVar( 'hidemainmenu', 1 );  	
    	JResearchToolbar::addMemberToolBar();    	    	
    	$control = JHTML::_('jresearchhtml.staffImporter2', 'importedMembers');
  
    	$this->assignRef('control', $control);
    	
    }
}
?>
