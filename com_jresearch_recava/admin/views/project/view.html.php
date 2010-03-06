<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of single project views
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );




/**
 * HTML View class for single project management in JResearch Component backend
 *
 */

class JResearchAdminViewProject extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
      	JResearchToolbar::editProjectAdminToolbar();
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');      	
    	JRequest::setVar( 'hidemainmenu', 1 );
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	$model =& $this->getModel();
    	$project = $model->getItem($cid[0]);
    	
    	$areaModel =& $this->getModel('researchareaslist');
    	$finModel =& $this->getModel('financiers');
    	$researchAreas = $areaModel->getData(null, true, false);
    	$financiers = $finModel->getData(null, true, false);

    	$principalFlags = null;    	
    	$members = null;
    	$arguments = array('project');
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	

		//Research areas 
		$researchAreasOptions = array();
    	foreach($researchAreas as $r){
    		$researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}
    	
    	//Status options
    	$statusOptions = array();
    	$statusOptions[] = JHTML::_('select.option', 'not_started', JText::_('JRESEARCH_NOT_STARTED'));
    	$statusOptions[] = JHTML::_('select.option', 'in_progress', JText::_('JRESEARCH_IN_PROGRESS'));
    	$statusOptions[] = JHTML::_('select.option', 'finished', JText::_('Finished'));
    	    	
    	//Currency options
    	$currencyOptions = array();
    	$currencyOptions[] = JHTML::_('select.option', 'EUR', 'Euro');
    	$currencyOptions[] = JHTML::_('select.option', 'USD', 'US Dollar');
    	
    	if($cid){
        	$projectFins = $project->getFinanciers();
        	$arguments[] = $project->id;
    	  	$members = $project->getAuthors();
    		$principalFlags = $project->getPrincipalsFlagsArray();    	  	
    	}else{
    		$arguments[] = null;
    		$projectFins = array();
    	}
    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $project?$project->published:1);   	
    	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox" size="5"', 'value', 'text', $project?$project->id_research_area:1);
    	$statusHTML = JHTML::_('select.genericlist', $statusOptions, 'status', 'class="inputbox" size="5"', 'value', 'text', $project?$project->status:'not_started');
    	$currencyHTML = JHTML::_('select.genericlist', $currencyOptions, 'finance_currency', 'class="inputbox"', 'value', 'text', $project?$project->finance_currency:null);

		//$membersControl = JHTML::_('AuthorsSelector._', 'members', $members, true, $principalFlags);	
		$membersControl = JHTML::_('AuthorsSelector.autoSuggest', 'members', $members, true, $principalFlags);
		$fins = array();
		foreach($projectFins as $fin){
			$fins[] = $fin->id;
		}		
		$finHTML = JHTML::_('ProjectsControl.financiersControl', 'id_financier', $fins);

    	$this->assignRef('project', $project, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
    	$this->assignRef('finList', $finHTML);
    	$this->assignRef('currencyList', $currencyHTML);
		$this->assignRef('editor', $editor);    
		$this->assignRef('membersControl', $membersControl);	
		$this->assignRef('status', $statusHTML);
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

       	parent::display($tpl);

       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>