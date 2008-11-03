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
      	
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');
    	JRequest::setVar( 'hidemainmenu', 1 );

    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$coop = $model->getItem($cid[0]);
    	$arguments = array('coop', $coop->id);
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $coop->published);

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_cooperations');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , $coop->ordering);
    	
		$editor =& JFactory::getEditor();    	
    	
    	$this->assignRef('coop', $coop);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('orderList', $orderList);
		$this->assignRef('editor', $editor);    	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
    }
}
?>
