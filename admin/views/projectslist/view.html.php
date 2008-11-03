<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of projects
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of projects lists in JResearch Component backend
 *
 */

class JResearchAdminViewProjectsList extends JView
{
    function display($tpl = null)
    {
     	global $mainframe;	
 	JResearchToolbar::projectsListToolbar();
       	$model = &$this->getModel();
      	$items = $model->getData(null, false, true);
      	$areaModel =& $this->getModel('researcharea');
     

      	// Filters and pagination
		$lists = array();    	
    	$filter_order = $mainframe->getUserStateFromRequest('projectsfilter_order', 'filter_order', 'title');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('projectsfilter_order', 'filter_order_Dir', 'ASC');
	$filter_state = $mainframe->getUserStateFromRequest('projectsfilter_state', 'filter_state');
    	$filter_search = $mainframe->getUserStateFromRequest('projectsfilter_search', 'filter_search');
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
     	$this->assignRef('lists', $lists );
     	$this->assignRef('page', $model->getPagination());
     	$this->assignRef('area', $areaModel);
     	parent::display($tpl);

    }
}

?>
