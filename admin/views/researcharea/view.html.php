<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for publication of research areas information.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML Admin View class for single research area management in JResearch Component
 *
 */

class JResearchAdminViewResearchArea extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
      	JResearchToolbar::editResearchAreaAdminToolbar();
    			
		JHTML::_('jresearchhtml.validation');
        JRequest::setVar( 'hidemainmenu', 1 );
        
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	$model =& $this->getModel();
    	$area = $model->getItem($cid[0]);
    	$arguments = array('researcharea');
    	
    	if($cid)
        	$arguments[] = $area->id; 	
    	else
    		$arguments[] = null;	
    	
    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $area?$area->published:1));
    	
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
		$this->assignRef('editor', $editor);    	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);

       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>
