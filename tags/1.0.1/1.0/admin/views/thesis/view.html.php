<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage		Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of a single thesis
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for single thesis management in JResearch Component backend
 *
 * @package    		JResearch
 */

class JResearchAdminViewThesis extends JView
{
    function display($tpl = null)
    {
    	global $mainframe;
		JResearchToolbar::editThesisAdminToolbar();
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
    	JRequest::setVar( 'hidemainmenu', 1 );
    	JHTML::_('Validator._');
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	$model =& $this->getModel();
    	$areaModel =& $this->getModel('researchareaslist');   	
    	$researchAreas = $areaModel->getData(null, true, false);
    	$members = null;
    	$directors = null;
    	$arguments = array('thesis');
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	

		//Research areas 
		$researchAreasOptions = array();
    	foreach($researchAreas as $r){
    		$researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}
    	
    	//Degree options
    	$degreeOptions = array();
    	$degreeOptions[] = JHTML::_('select.option', 'bachelor', JText::_('JRESEARCH_BACHELOR'));
    	$degreeOptions[] = JHTML::_('select.option', 'master', JText::_('JRESEARCH_MASTER'));
    	$degreeOptions[] = JHTML::_('select.option', 'phd', JText::_('JRESEARCH_PHD'));

    	//Status options
    	$statusOptions = array();
    	$statusOptions[] = JHTML::_('select.option', 'not_started', JText::_('JRESEARCH_NOT_STARTED'));
    	$statusOptions[] = JHTML::_('select.option', 'in_progress', JText::_('JRESEARCH_IN_PROGRESS'));
    	$statusOptions[] = JHTML::_('select.option', 'finished', JText::_('Finished'));
    	
    	if($cid){
        	$thesis = $model->getItem($cid[0]);
        	$arguments[] = $thesis->id;
    	   	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $thesis->published);   	
    	  	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox" size="5"', 'value', 'text', $thesis->id_research_area);
    	  	$statusHTML = JHTML::_('select.genericlist', $statusOptions, 'status', 'class="inputbox" size="5"', 'value', 'text', $thesis->status);
    	  	$degreeHTML = JHTML::_('select.genericlist', $degreeOptions, 'degree', 'class="inputbox" size="5"', 'value', 'text', $thesis->degree);
    	  	$directors = $thesis->getDirectors();
    	  	$students = $thesis->getStudents();
    	}else{
    		$arguments[] = null;
    	   	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , 1);   		
    	 	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox"" size="5"', 'value', 'text', 1); 
    	 	$statusHTML = JHTML::_('select.genericlist', $statusOptions, 'status', 'class="inputbox" size="5"', 'value', 'text', 'not_started');
    	 	$degreeHTML = JHTML::_('select.genericlist', $degreeOptions, 'degree', 'class="inputbox" size="5"', 'value', 'text', 'bachellor');
    	}

		$studentsControl = JHTML::_('AuthorsSelector._', 'students', $students);
		$directorsControl = JHTML::_('AuthorsSelector._', 'directors', $directors);		    	

    	$this->assignRef('thesis', $thesis);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
		$this->assignRef('editor', $editor);    
		$this->assignRef('studentsControl', $studentsControl);
		$this->assignRef('directorsControl', $directorsControl);	
		$this->assignRef('status', $statusHTML);
		$this->assignRef('degree', $degreeHTML);

		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
    	
    }
}

?>
