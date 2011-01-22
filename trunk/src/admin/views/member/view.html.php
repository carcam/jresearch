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

/**
 * HTML View class for single member management in JResearch Component backend
 *
 */

class JResearchAdminViewMember extends JResearchView 
{
    function display($tpl = null){
    	$mainframe = JFactory::getApplication();
      	JResearchToolbar::editMemberAdminToolbar();
       	
		JHTML::_('jresearchhtml.validation');
    	JRequest::setVar( 'hidemainmenu', 1 );

    	$params = JComponentHelper::getParams('com_jresearch');
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	
    	$member = $model->getItem($cid[0]);
    	$arguments = array('member', $member?$member->id:null);
    	
    	//Lists
    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $member->published));
   	 	$researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="1"', 'selected' => $member->id_research_area)); 

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, CONCAT_WS(\' \', firstname, lastname) AS text FROM #__jresearch_member ORDER by former_member,ordering ASC');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , ($member)?$member->ordering:0);
    	
    	$positionList = JHTML::_('jresearchhtml.memberpositions', array('name' => 'position', 'attributes' => 'class="inputbox" size="1"', 'selected' => $member->position));
    	
		$editor = JFactory::getEditor();    	
    	
    	$this->assignRef('member', $member, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('areasList', $researchAreasHTML);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('orderList', $orderList);
    	$this->assignRef('positionList', $positionList);
		$this->assignRef('editor', $editor);
		$this->assignRef('params', $params);  	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>
