<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of publications
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for management of publications lists in JResearch Component backend
 *
 */

class JResearchAdminViewPublicationsList extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	// Invoke the correct function according to the layout
    	$layout = $this->getLayout();

    	switch($layout){
    		case 'import':
    			$this->_displayImportForm();	
    			break;
    		case 'export':
    			$this->_displayExportForm();
    			break;
    		default:
    			$this->_displayDefaultList();
    			break;		
    	}
    	
	$eArguments = array('publications');
	if($layout == 'default')
	     $mainframe->triggerEvent('onBeforeListJResearchEntities', $eArguments);
        
        parent::display($tpl);
        
        if($layout == 'default')
        	$mainframe->triggerEvent('onAfterListJResearchEntities', $eArguments);
    }
    
    
    
    /**
    * Invoked when the user has selected the option Publications from the
    * Control Panel. It binds the variables needed by the default view for
    * publications.
    */
    private function _displayDefaultList(){
    	global $mainframe, $option;
    	
    	require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'publications.php');
    	
    	JResearchToolbar::publicationsAdminListToolbar();
    	JHTML::_('behavior.tooltip');
		
		// Get the default model    	
    	$model = $this->getModel();
    	$researchAreaModel = $this->getModel('researcharea');
    	$items = $model->getData(null, null, true);
    	
    	// Ordering variables
    	$lists = array();    	
    	// Get the user state of the order and direction 
    	$filter_order = $mainframe->getUserStateFromRequest('publicationsfilter_order', 'filter_order', 'published');
    	$filter_order_Dir = $mainframe->getUserStateFromRequest('publicationsfilter_order', 'filter_order_Dir',  'ASC');
		$filter_state = $mainframe->getUserStateFromRequest('publicationsfilter_state', 'filter_state');
		$filter_year = $mainframe->getUserStateFromRequest('publicationsfilter_year', 'filter_year');
		$filter_pubtype = $mainframe->getUserStateFromRequest('publicationsfilter_pubtype', 'filter_pubtype');
		$filter_area = $mainframe->getUserStateFromRequest('publicationsfilter_area', 'filter_area');		
    	$filter_search = $mainframe->getUserStateFromRequest('publicationsfilter_search', 'filter_search');
		$filter_author = $mainframe->getUserStateFromRequest('publicationsfilter_author', 'filter_author');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		// State filter
		$lists['state'] = JHTML::_('grid.state', $filter_state);
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		$lists['search'] = $filter_search;
		
		// Year filter
		$db = &JFactory::getDBO();
		$db->setQuery('SELECT DISTINCT year FROM '.$db->nameQuote('#__jresearch_publication').' ORDER BY '.$db->nameQuote('year').' DESC ');
		$years = $db->loadResultArray();
		$yearsHTML = array();
		$yearsHTML[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_YEAR'));
		foreach($years as $y)
			$yearsHTML[] = JHTML::_('select.option', $y, $y);
			
		$lists['year'] = JHTML::_('select.genericlist', $yearsHTML, 'filter_year', 'class="inputbox" size="1" '.$js, 'value','text', $filter_year);

		// Publication type filter
		$types = JResearchPublication::getPublicationsSubtypes();
		$typesHTML = array();
		$typesHTML[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_PUBLICATION_TYPE'));
		foreach($types as $type){
			$typesHTML[] = JHTML::_('select.option', $type, JText::_('JRESEARCH_'.strtoupper($type)));
		}
		$lists['pubtype'] = JHTML::_('select.genericlist', $typesHTML, 'filter_pubtype', 'class="inputbox" size="1" '.$js, 'value','text', $filter_pubtype);
		
		// Research Area filter
		$areas = JResearchArea::getAllItems();
		$areasHTML = array();
		$areasHTML[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_RESEARCH_AREA'));
		foreach($areas as $area){
			$areasHTML[] = JHTML::_('select.option', $area->id, $area->name); 
		}
		$lists['area'] = JHTML::_('select.genericlist', $areasHTML, 'filter_area', 'class="inputbox" size="1" '.$js, 'value','text', $filter_area);
		
		$this->assignRef('lists', $lists);

		$authors = $model->getAllAuthors();
		$authorsHTML = array();
		$authorsHTML[] = JHTML::_('select.option', 0, JText::_('JRESEARCH_AUTHORS'));	
		foreach($authors as $auth){
			$authorsHTML[] = JHTML::_('select.option', $auth['id'], $auth['name']); 
		}
		$lists['authors'] = JHTML::_('select.genericlist', $authorsHTML, 'filter_author', 'class="inputbox" size="1" '.$js, 'value','text', $filter_author);

	
    	$this->assignRef('items', $items);
    	$this->assignRef('page', $model->getPagination());
    	$this->assignRef('area', $researchAreaModel);
    }
    
    /**
     * Binds the variables necessary to display the form for
     * importing sets of bibliography references.
     *
     */
    private function _displayImportForm(){
    	JResearchToolbar::importPublicationsToolbar();
    	
    	$model = $this->getModel('researchareaslist');
    	
    	$researchAreas = $model->getData(null, true, false);
    	$researchAreasOptions = array();
    	$formatsOptions = array();
    	
    	// Retrieve the list of research areas
    	foreach($researchAreas as $r){
    		$researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}    	
    	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'researchAreas', 'class="inputbox" id="researchAreas" size="5"');
    	
    	// Supported input formats
    	$formatsOptions[] = JHTML::_('select.option', 'bibtex', 'Bibtex');
    	$formatsOptions[] = JHTML::_('select.option', 'mods', 'MODS');
    	$formatsOptions[] = JHTML::_('select.option', 'ris', 'RIS');
    	$formatsOptions[] = JHTML::_('select.option', 'medline', 'XML MEDLINE');
    	$formatsHTML = JHTML::_('select.genericlist', $formatsOptions, 'formats', 'id="formats" size="1"');
    	
    	$this->assignRef('categoryList', $researchAreasHTML);
    	$this->assignRef('formatsList', $formatsHTML);
    }
    
    /**
    * Invoked when the user exports a set of bibliographical references.
    */	
    private function _displayExportForm(){
      JResearchToolbar::importPublicationsToolbar();
		$session =& JFactory::getSession();
		$task = JRequest::getVar('task');
		
		if($task == 'export'){
			$cid = JRequest::getVar('cid', null);
			$exportCompleteDatabase = false;
			$session->set('markedRecords', $cid, 'jresearch');
		}elseif($task == 'exportAll'){
			$exportCompleteDatabase = true;
			$session->set('markedRecords', 'all', 'jresearch'); 
		}
		      	
      
    	$formatsOptions = array();
    	$formatsOptions[] = JHTML::_('select.option', 'bibtex', 'Bibtex');
    	$formatsOptions[] = JHTML::_('select.option', 'mods', 'MODS');
    	$formatsOptions[] = JHTML::_('select.option', 'ris', 'RIS');
    	$formatsHTML = JHTML::_('select.genericlist', $formatsOptions, 'outformat', 'size="1"');
		
		$this->assignRef('formatsList', $formatsHTML);		
		$this->assignRef('exportAll', $exportCompleteDatabase);
    	
    }
}

?>
