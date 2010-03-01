<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for adding/editing a cooperation.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 */

class JResearchAdminViewCooperation extends JResearchView
{
	function display($tpl = null)
	{
    	global $mainframe;
    	
    	JResearchToolbar::editCooperationAdminToolbar();
    	
		JHTML::_('jresearchhtml.validation');
    	JRequest::setVar( 'hidemainmenu', 1 );

    	$params = JComponentHelper::getParams('com_jresearch');
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$coop = $model->getItem($cid[0]);
    	$arguments = array('cooperation', $coop?$coop->id:null);
    	
		$publishedList = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $coop?$coop->published:1));

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_cooperations WHERE catid='.($coop?$coop->catid:0).' ORDER by ordering ASC');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , $coop?$coop->ordering:0);
    	
    	$categoryList = JHTML::_('list.category', 'catid', 'com_jresearch_cooperations', $coop?$coop->catid:null);
    	
		$editor =& JFactory::getEditor();
    	
    	$this->assignRef('coop', $coop, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('categoryList', $categoryList);
    	$this->assignRef('publishedList', $publishedList);
    	$this->assignRef('orderList', $orderList);
		$this->assignRef('editor', $editor);   
		$this->assignRef('params', $params); 	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}
?>
