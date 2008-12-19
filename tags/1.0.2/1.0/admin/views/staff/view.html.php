<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage	JResearch
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of staff list
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 * @package   JResearch
 */

class JResearchAdminViewStaff extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
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
      global $mainframe;
    	JResearchToolbar::staffAdminListToolbar();
    	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	$members =  $model->getData(null, false, true);
    	
		// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('stafffilter_order', 'filter_order', 'lastname');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('stafffilter_order', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('stafffilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('stafffilter_search', 'filter_search');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		$lists['search'] = $filter_search;
    	
    	
    	$this->assignRef('items', $members);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('page', $model->getPagination());	

    }
	
	 /**
	 * Invoked when the user wants to import staff members from Joomla users
	 * table.
	 */	    
    private function _displayAddForm(){
    	global $mainframe;
    	
    	JRequest::setVar( 'hidemainmenu', 1 );
    	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
    	JResearchToolbar::addMemberToolBar();
    	
    	$doc =& JFactory::getDocument();
    	$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
    	$doc->addScript($url.'components/com_jresearch/helpers/html/staffimporter.js');
    	
    	$control = JHTML::_('StaffImporter._', 'importedMembers');
    	
    	$this->assignRef('control', $control);
    	
    }
}
?>
