<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Institutes
* @copyright	Copyright (C) 2009 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for adding/editing a institute.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 */

class JResearchAdminViewInstitute extends JResearchView
{
	function display($tpl = null)
	{
    	global $mainframe;
    	
    	JResearchToolbar::editInstituteAdminToolbar();
    	
		JHTML::_('jresearchhtml.validation');
    	JRequest::setVar( 'hidemainmenu', 1 );

    	$params = JComponentHelper::getParams('com_jresearch');
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$institute = $model->getItem($cid[0]);
    	$arguments = array('institute', $institute?$institute->id:null);
    	
		$publishedList = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $institute?$institute->published:1));

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_cooperations WHERE catid='.($institute?$institute->catid:0).' ORDER by ordering ASC');
    	
		$editor =& JFactory::getEditor();
    	
    	$this->assignRef('institute', $institute, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedList', $publishedList);
		$this->assignRef('editor', $editor);   
		$this->assignRef('params', $params); 	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}
?>
