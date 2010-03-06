<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Transfers
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
class JResearchAdminViewTransfer extends JResearchView
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
	    $transfer = $model->getItem($cid[0]);
    	
    	$arguments = $transfer ? array('transfer', $transfer->id) : array('transfer', null);
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $transfer?$transfer->published:0);
    	
		$editor =& JFactory::getEditor();
		
		if($cid)
		{
        	$arguments[] = $transfer->id;  	
    	}
    	else
    	{
    		$arguments[] = null;
    	}
		
    	$this->assignRef('publishedRadio', $publishedRadio);
		$this->assignRef('editor', $editor);
		$this->assignRef('transfer', $transfer, JResearchFilter::OBJECT_XHTML_SAFE);
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}
?>
