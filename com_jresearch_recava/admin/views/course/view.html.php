<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Courses
* @copyright	Copyright (C) 2010 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for adding/editing a course.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 */
class JResearchAdminViewCourse extends JResearchView
{
	function display($tpl = null)
	{
    	global $mainframe;
      	
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');
    	JRequest::setVar( 'hidemainmenu', 1 );

    	$members = null;
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
	    $course = $model->getItem($cid[0]);
    	
    	$arguments = $course ? array('course', $course->id) : array('course', null);
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $course?$course->published:0);
    	
    	$participantsOptions = array();
    	$participantsOptions[] = JHTML::_('select.option', '0', JText::_('<50'));    	
    	$participantsOptions[] = JHTML::_('select.option', '1', JText::_('50-100'));
    	$participantsOptions[] = JHTML::_('select.option', '2', JText::_('>100'));     
    	$participantsSelect = JHTML::_('select.genericlist', $participantsOptions, 'participants', 'class="inputbox"', 'value', 'text', $course?$course->participants:0);
    	
		$editor =& JFactory::getEditor();
		
		if($cid)
		{
        	$arguments[] = $course->id;
    	  	$members = $course->getAuthors();   	  	
    	}
    	else
    	{
    		$arguments[] = null;
    	}
		
		$membersControl = JHTML::_('AuthorsSelector.autoSuggest2', 'directors', $members, false);
    	
		//$this->assignRef('groupsControl', $groupsControl);
		$this->assignRef('membersControl', $membersControl);
		$this->assignRef('participantsSelect', $participantsSelect);
    	$this->assignRef('publishedRadio', $publishedRadio);
		$this->assignRef('editor', $editor);
		$this->assignRef('course', $course, JResearchFilter::OBJECT_XHTML_SAFE);
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}
?>
