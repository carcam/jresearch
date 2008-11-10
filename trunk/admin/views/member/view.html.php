<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of a single member's profile
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for single member management in JResearch Component backend
 *
 */

class JResearchAdminViewMember extends JView
{
    function display($tpl = null){
    	global $mainframe;
      	JResearchToolbar::editMemberAdminToolbar();
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');
    	JRequest::setVar( 'hidemainmenu', 1 );

    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$member = $model->getItem($cid[0]);
    	$arguments = array('member', $member->id);
    	
		// Research areas
		$areasModel = $this->getModel('researchareaslist');    	
    	$researchAreas = $areasModel->getData(null, true, false);
    	
    	$researchAreasOptions = array();

    	// Retrieve the list of research areas
    	foreach($researchAreas as $r){
    		$researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}    	
    	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox" size="5"', 'value', 'text', $member->id_research_area);

		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $member->published);

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, CONCAT_WS(\' \', firstname, lastname) AS text FROM #__jresearch_member ORDER by ordering ASC');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , ($member)?$member->ordering:0);
    	
		$editor =& JFactory::getEditor();    	
    	
    	
    	$this->assignRef('member', $member);
    	$this->assignRef('areasList', $researchAreasHTML);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('orderList', $orderList);
		$this->assignRef('editor', $editor);    	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
    }
}

?>
