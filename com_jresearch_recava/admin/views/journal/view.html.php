<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Financiers
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for publication of financiers information.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML Admin View class for single research area management in JResearch Component
 *
 */

class JResearchAdminViewJournal extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	
      	JResearchToolbar::editJournalAdminToolbar();
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');
        JRequest::setVar( 'hidemainmenu', 1 );
        
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model = $this->getModel();
    	$journal = $model->getItem($cid[0]);
    	$arguments = array('journal');
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	

    	
    	if($journal){
        	$arguments[] = $journal->id;
    	}else{
    		$arguments[] = null;
    	}
    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , !empty($journal)? $journal->published: 1);       	
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('journal', $journal, JResearchFilter::OBJECT_XHTML_SAFE);
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);

       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}
?>