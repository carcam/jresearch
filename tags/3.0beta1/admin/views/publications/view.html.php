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

class JResearchAdminViewPublications extends JResearchView
{
    function display($tpl = null)
    {
    	$mainframe = JFactory::getApplication();
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
    	
        parent::display($tpl);
    }
    
    /**
    * Invoked when the user has selected the option Publications from the
    * Control Panel. It binds the variables needed by the default view for
    * publications.
    */
    private function _displayDefaultList(){
    	$mainframe = JFactory::getApplication();
        $option = JRequest::getVar('option');
        $params = JComponentHelper::getParams('com_jresearch');
        jresearchimport('helpers.publications', 'jresearch.admin');
        JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'html');		
    	
    	JResearchToolbar::publicationsAdminListToolbar();
        $db = JFactory::getDBO();
    	JHTML::_('behavior.tooltip');
		
        // Get the default model
    	$model = $this->getModel();
    	$items = $model->getItems();
    	
    	// Ordering variables
    	$lists = array();
        $this->state = $this->get('State');
    	// Get the user state of the order and direction 
    	$filter_order = $this->state->get('com_jresearch.publications.filter_order');
    	$filter_order_Dir = $this->state->get('com_jresearch.publications.filter_order_Dir');
        $filter_state = $this->state->get('com_jresearch.publications.filter_state');
        $filter_year = $this->state->get('com_jresearch.publications.filter_year');
        $filter_pubtype = $this->state->get('com_jresearch.publications.filter_pubtype');
        $filter_area = $this->state->get('com_jresearch.publications.filter_area');
    	$filter_search = $this->state->get('com_jresearch.publications.filter_search');
        $filter_author = $this->state->get('com_jresearch.publications.filter_author');
    	
    	$lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        $js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
         
        // State filter
        $lists['state'] =  JHTML::_('grid.state', $filter_state);
        $lists['search'] = $filter_search;
		
        // Year filter
        $years = JResearchPublicationsHelper::getYears();
        $lists['year'] = JHTML::_('jresearchhtml.years', $years, array('name' => 'filter_year', 'selected' => $filter_year, 'attributes' => $js));

        $lists['pubtype'] = JHTML::_('jresearchhtml.publicationstypeslist', 'filter_pubtype', 'class="inputbox" size="1" '.$js, $filter_pubtype);
        
        $lists['area'] = JHTML::_('jresearchhtml.researchareas', array('name' => 'filter_area', 'selected' => $filter_area, 'attributes' => $js), array(array('id' => '-1', 'name' => JText::_('JRESEARCH_RESEARCH_AREA'))));

        $authors = JResearchPublicationsHelper::getAllAuthors();
        $lists['authors'] = JHTML::_('jresearchhtml.authors', $authors, array('name' => 'filter_author', 'selected' => $filter_author, 'attributes' => $js));

        $pagination = $model->getPagination();
    	$this->assignRef('items', $items);
    	$this->assignRef('page', $pagination);
        $this->assignRef('lists', $lists);
        $this->assignRef('params', $params);            	
    }
    
    /**
     * Binds the variables necessary to display the form for
     * importing sets of bibliography references.
     *
     */
    private function _displayImportForm(){
    	JResearchToolbar::importPublicationsToolbar();
        $this->loadHelper('researchareas');
    	
    	$researchAreas = JResearchResearchareasHelper::getResearchAreas();
    	$researchAreasOptions = array();
    	$formatsOptions = array();
    	
    	// Retrieve the list of research areas
    	foreach($researchAreas as $r){
            $researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}    	
    	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'researchAreas[]', 'class="inputbox" multiple="multiple" id="researchAreas" size="5"');
    	
    	// Supported input formats
    	$formatsOptions[] = JHTML::_('select.option', 'bibtex', 'Bibtex');
    	$formatsOptions[] = JHTML::_('select.option', 'mods', 'MODS');
    	$formatsOptions[] = JHTML::_('select.option', 'ris', 'RIS');
    	$formatsHTML = JHTML::_('select.genericlist', $formatsOptions, 'formats', 'id="formats" size="1"');
    	
    	$this->assignRef('categoryList', $researchAreasHTML);
    	$this->assignRef('formatsList', $formatsHTML);
    }
    
    /**
    * Invoked when the user exports a set of bibliographical references.
    */	
    private function _displayExportForm(){
        JResearchToolbar::importPublicationsToolbar();
        $session = JFactory::getSession();
        $task = JRequest::getVar('task');

        if($task == 'export'){
            $cid = JRequest::getVar('cid', null);
            $exportCompleteDatabase = false;
            $session->set('markedRecords', $cid, 'com_jresearch.publications');
        }elseif($task == 'exportAll'){
            $exportCompleteDatabase = true;
            $session->set('markedRecords', 'all', 'com_jresearch.publications');
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