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
      	
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');
    	JRequest::setVar( 'hidemainmenu', 1 );

    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$teamModel =& $this->getModel('teams');
    	
	    $coop = $model->getItem($cid[0]);
	    $teams = $teamModel->getData(null,true);
    	
    	$arguments = $coop ? array('cooperation', $coop->id) : array('cooperation', null);
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $coop?$coop->published:0);

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_cooperations ORDER by ordering ASC');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , $coop?$coop->ordering:0);
    	
    	//Receptor & emisor groups & TOI list
    	$teamOptions = array();
    	foreach($teams as $team)
    	{
    		$teamOptions[] = JHTML::_('select.option', $team->id, $team->name);
    	}
    	$receptorElement = JHTML::_('select.genericlist', $teamOptions ,'receptor', 'class="inputbox"' ,'value', 'text' , $coop?$coop->receptor:0);
    	$emisorElement = JHTML::_('select.genericlist', $teamOptions ,'emisor', 'class="inputbox"' ,'value', 'text' , $coop?$coop->emisor:0);
    	
    	$toiOptions = array();
    	$toiOptions[] = JHTML::_('select.option', '1', JText::_('JRESEARCH_COOPERATION_INTERCHANGE_TYPE_1'));
    	$toiOptions[] = JHTML::_('select.option', '2', JText::_('JRESEARCH_COOPERATION_INTERCHANGE_TYPE_2'));
    	$toiOptions[] = JHTML::_('select.option', '3', JText::_('JRESEARCH_COOPERATION_INTERCHANGE_TYPE_3'));
    	$toiOptions[] = JHTML::_('select.option', '4', JText::_('JRESEARCH_COOPERATION_INTERCHANGE_TYPE_4'));
    	$toiOptions[] = JHTML::_('select.option', '5', JText::_('JRESEARCH_COOPERATION_INTERCHANGE_TYPE_5'));
    	$toiOptions[] = JHTML::_('select.option', '6', JText::_('JRESEARCH_COOPERATION_INTERCHANGE_TYPE_6'));
    	
    	$toiElement = JHTML::_('select.genericlist', $toiOptions ,'type_ic', 'class="inputbox"' ,'value', 'text' , $coop?$coop->type_ic:0);
    	
		$editor =& JFactory::getEditor();    	
    	
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('orderList', $orderList);
    	$this->assignRef('emisorElement', $emisorElement);
    	$this->assignRef('receptorElement', $receptorElement);
    	$this->assignRef('toiElement', $toiElement);
		$this->assignRef('editor', $editor);
		$this->assignRef('coop', $coop, JResearchFilter::OBJECT_XHTML_SAFE);
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}
?>
