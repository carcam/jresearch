<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of a single publication
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for single publication management in JResearch Component backend
 *
 */

class JResearchAdminViewPublication extends JResearchView
{
	function display($tpl = null){
		global $mainframe;
		
 		$layout = $this->getLayout();
 		$arguments = array();
 		
 		switch($layout){
 			case 'new':
 				$this->_displayNewPublicationForm();
 				break;
 			case 'default':
 				$this->_displayPublicationForm($arguments); 				
 				break;	
 		}
 		 		
		parent::display($tpl);
		if($layout == 'default')
       		$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);		
	}
	
	/**
	* Binds the variables useful for displaying the form for editing/creating
	* publications.
	*/
	private function _displayPublicationForm(&$arguments){
		global $mainframe;
		JResearchToolbar::editPublicationAdminToolbar();
		$arguments[] = 'publication';
		$db = JFactory::getDBO();
		$journalValue = null;
		$selectFromList = true;

		JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
		JHTML::_('Validator._');		
		
		$cid = JRequest::getVar('cid');
		$isNew = !isset($cid);
		$pubtype = JRequest::getVar('pubtype');
    	$model = $this->getModel('researchareaslist');
		$authors = null;

    	// Retrieve the list of research areas   	
    	$researchAreas = $model->getData(null, true, false);

    	$researchAreasOptions = array();
    	foreach($researchAreas as $r){
    		$researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}
    	
    	//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	

		if(!$isNew){
			$arguments[] = $cid[0];		
			$publication = JResearchPublication::getById($cid[0]);
			$this->assignRef('publication', $publication, JResearchFilter::OBJECT_XHTML_SAFE);			
	    	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox" size="5"', 'value', 'text', $publication->id_research_area);
			//Published radio
			$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $publication->published);
			$internalRadio = JHTML::_('select.genericlist', $publishedOptions, 'internal', 'class="inputbox"', 'value', 'text', $publication->internal  );
			$authors = $publication->getAuthors();
			if(!empty($publication->id_journal)){
				$journalValue = $publication->id_journal;
				$selectFromList = true;
			}else{
				$journalValue = $publication->journal;
				$selectFromList = false;				
			}
		}else{
			$arguments[] = null;
			$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox" size="5"');
			//Published radio
			$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , 1);			
			$internalRadio = JHTML::_('select.genericlist', $publishedOptions, 'internal', 'class="inputbox"', 'value', 'text', 1);
		}
		
		$authorsControl = JHTML::_('AuthorsSelector.autoSuggest2', 'authors' ,$authors);
		$journalsControl = JHTML::_('JournalsControl.journalscontrol', 'journal', $journalValue, $selectFromList);
		
		$this->assignRef('areasList', $researchAreasHTML);
		$this->assignRef('publishedRadio', $publishedRadio);
		$this->assignRef('internalRadio', $internalRadio );
		$this->assignRef('pubtype', $pubtype);
		$this->assignRef('authors', $authorsControl);
		$this->assignRef('journals', $journalsControl);
		
 	  	// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);		
	}
	
	/**
	* Binds the variables for the form used to select the type 
	* for a new publication.
	*/
	private function _displayNewPublicationForm(){
	   JResearchToolbar::importPublicationsToolbar();
		$subtypes = JResearchPublication::getPublicationsSubtypes();
		$typesOptions = array();
		
		foreach($subtypes as $type){
			// Inproceedings is the same as conference 
			if($type != 'inproceedings')
				$typesOptions[] = JHTML::_('select.option', $type, $type.': '.JText::_('JRESEARCH_'.strtoupper($type)));			
		}
		
		$typesList = JHTML::_('select.genericlist', $typesOptions, 'pubtype', 'size="1"');		
		
		$this->assignRef('types', $typesList);
		
	}
}

?>
