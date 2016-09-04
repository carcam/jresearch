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
        $layout = $this->getLayout();
         
        switch($layout){
            case 'add':
                $this->_displayAddForm();
                break;
            case 'default':
                $this->_displayDefaultList();
                break;
        }
	
        parent::display($tpl);
    }
    
    /**
    * Invoked when the user has selected the default view for staff members
    * which is the admin list of members.
    */
    private function _displayDefaultList(){
        $mainframe = JFactory::getApplication();
    	JResearchToolbar::staffAdminListToolbar();
        jresearchimport('helpers.publications', 'jresearch.admin');

        //Get the model
    	$model = $this->getModel();
    	$areaModel = $this->getModel('researcharea');
    	$members =  $model->getItems();
    	$params = JComponentHelper::getParams('com_jresearch');
    	
        // Filters and pagination
        $lists = array();
        $this->state = $this->get('State');
        $filter_order = $this->state->get('com_jresearch.staff.filter_order');
        $filter_order_Dir = $this->state->get('com_jresearch.staff.filter_order_Dir');
        $filter_state = $this->state->get('com_jresearch.staff.filter_state');
        $filter_search = $this->state->get('com_jresearch.staff.filter_search');
        $filter_former = $this->state->get('com_jresearch.staff.filter_former');
        $filter_area = $this->state->get('com_jresearch.staff.filter_area');        
        $filter_limit = $this->state->get('list.limit');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        // State filter
        $lists['state'] = JHTML::_('grid.state', $filter_state);
        $js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
        $lists['search'] = $filter_search;
        $lists['area'] = JHTML::_('jresearchhtml.researchareas', array('name' => 'filter_area', 'selected' => $filter_area, 'attributes' => 'onchange="document.adminForm.submit();"'), array(array('id' => null, 'name' => JText::_('JRESEARCH_RESEARCH_AREA'))));

        //Former member filter
        $formerHTML[] = JHTML::_('select.option', '0', JText::_('- Former Member -'));
        $formerHTML[] = JHTML::_('select.option', '-1', JText::_('JNO'));
        $formerHTML[] = JHTML::_('select.option', '1', JText::_('JYES'));
        $lists['former'] = JHTML::_('select.genericlist', $formerHTML, 'filter_former', 'class="inputbox" size="1" '.$js, 'value','text', $filter_former);
        $lists['limit'] = JHTML::_('jresearchhtml.limit', array('name' => 'limit', 'selected' => $filter_limit, 'attributes' => $js));

        //Ordering allowed ?
        $ordering = ($lists['order'] == 'ordering');

        $pagination = $model->getPagination();
    	
    	$this->assignRef('items', $members);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('ordering', $ordering);
    	$this->assignRef('page', $pagination);
    	$this->assignRef('params', $params);

    }
	
	 /**
	 * Invoked when the user wants to import staff members from Joomla users
	 * table.
	 */	    
    private function _displayAddForm(){
    	JRequest::setVar( 'hidemainmenu', 1 );  	
    	JResearchToolbar::addMemberToolBar();    	    	
    	$control = JHTML::_('jresearchhtml.staffImporter2', 'importedMembers');
  
    	$this->assignRef('control', $control);    	
    }
}
?>
