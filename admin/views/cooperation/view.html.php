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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of members lists in JResearch Component backend
 *
 */

class JResearchAdminViewCooperation extends JView
{
	function display($tpl = null)
	{
    	global $mainframe;
      	
    	JResearchToolbar::editCooperationAdminToolbar();
    	
		JHTML::_('JResearch.validation');
    	JRequest::setVar( 'hidemainmenu', 1 );

    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$coop = $model->getItem($cid[0]);
    	$arguments = array('coop', $coop->id);
    	
		$publishedList = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $coop?$coop->published:1));

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_cooperations WHERE catid='.($coop->catid?$coop->catid:0).' ORDER by ordering ASC');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , $coop->ordering);
    	
    	$categoryList = JHTML::_('list.category', 'catid', 'com_jresearch_cooperations', $coop->catid);
    	
		$editor =& JFactory::getEditor();
    	
    	$this->assignRef('coop', $coop);
    	$this->assignRef('categoryList', $categoryList);
    	$this->assignRef('publishedList', $publishedList);
    	$this->assignRef('orderList', $orderList);
		$this->assignRef('editor', $editor);    	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
    }
}
?>
