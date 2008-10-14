<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage		Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of theses
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of theses lists in JResearch Component backend
 *
 * @package    		JResearch
 */

class JResearchAdminViewThesesList extends JView
{
    function display($tpl = null)
    {
        global $mainframe;	
 	JResearchToolbar::thesesAdminListToolbar();
       	$model = &$this->getModel();
      	$items = $model->getData(null, false, true);
      	$areaModel =& $this->getModel('researcharea');
     

      	// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('thesesfilter_order', 'filter_order', 'filter_order', 'title');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('thesesfilter_order', 'filter_order_Dir', 'filter_order_Dir', 'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('thesesfilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('thesesfilter_search', 'filter_search');
	$filter_author = $mainframe->getUserStateFromRequest('projectsfilter_author', 'filter_author');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
	$lists['order'] = $filter_order;
	// State filter
	$lists['state'] = JHTML::_('grid.state', $filter_state);
	$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
	$lists['search'] = $filter_search;

	// Authors filter
	$authors = $model->getAllAuthors();
	$authorsHTML = array();
	$authorsHTML[] = JHTML::_('select.option', 0, JText::_('JRESEARCH_MEMBERS'));	
	foreach($authors as $auth){
		$authorsHTML[] = JHTML::_('select.option', $auth['id'], $auth['name']); 
	}
	$lists['authors'] = JHTML::_('select.genericlist', $authorsHTML, 'filter_author', 'class="inputbox" size="1" '.$js, 'value','text', $filter_author);

     	$this->assignRef('items', $items);
     	$this->assignRef('lists', $lists);
     	$this->assignRef('page', $model->getPagination());
     	$this->assignRef('area', $areaModel);
     	parent::display($tpl);
    }
}

?>
