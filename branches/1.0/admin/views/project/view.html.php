<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage		Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of single project views
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');


/**
 * HTML View class for single project management in JResearch Component backend
 *
 * @package    		JResearch
 */

class JResearchAdminViewProject extends JView
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
    	$areaModel =& $this->getModel('researchareaslist');   	
    	$researchAreas = $areaModel->getData(null, true, false);
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
    	
    	if($cid){
        	$project = $model->getItem($cid[0]);
        	$arguments[] = $project->id;
    	   	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $project->published);   	
    	  	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox" size="5"', 'value', 'text', $project->id_research_area);
    	  	$statusHTML = JHTML::_('select.genericlist', $statusOptions, 'status', 'class="inputbox" size="5"', 'value', 'text', $project->status);
    	  	$members = $project->getAuthors();
    	}else{
    		$arguments[] = null;
    	   	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , 1);   		
    	 	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox"" size="5"', 'value', 'text', 1); 
    	 	$statusHTML = JHTML::_('select.genericlist', $statusOptions, 'status', 'class="inputbox" size="5"', 'value', 'text', 'not_started');
    	}

		$membersControl = JHTML::_('AuthorsSelector._', 'members', $members);		    	

    	$this->assignRef('project', $project);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
		$this->assignRef('editor', $editor);    
		$this->assignRef('membersControl', $membersControl);	
		$this->assignRef('status', $statusHTML);
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

       	parent::display($tpl);

    }
}

?>
