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

class JResearchAdminViewTeam extends JResearchView
{
	function display($tpl = null)
	{
    	global $mainframe;      	
    	JResearchToolbar::editTeamAdminToolbar();
      			
		JHTML::_('jresearchhtml.validation');
    	JRequest::setVar( 'hidemainmenu', 1 );
    	$arguments = array('team');

    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$team = $model->getItem($cid[0]);
    	$membersModel =& $this->getModel('Staff'); 	
    	$teamsModel =& $this->getModel('Teams');
    	$hierarchy = $teamsModel->getHierarchical(false, false);
    	
    	//Staff options
    	//@todo Add member list to HTML helper class
    	$members = $membersModel->getData();
    	$memberOptions = array();
    	
    	foreach($members as $member)
    	{
    		$memberOptions[] = JHTML::_('select.option', $member->id, $member->firstname.' '.$member->lastname);
    	}
    	
    	$selectedMemberOptions = array();
    	
    	if($cid){
            if(empty($team)){
                JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                return;
            }

            $arguments[] = $team;

            //Leader and members list
            $leaderList = JHTML::_('select.genericlist', $memberOptions ,'id_leader', 'class="inputbox" size="1"' ,'value', 'text' , $team->id_leader);
            $selectedMembers = $team->getMembers();
            foreach($selectedMembers as $member)
            {
                    $selectedMemberOptions[] = $member;
            }
            $memberList = JHTML::_('select.genericlist', $memberOptions ,'members[]', 'class="inputbox" multiple="multiple" size="5"' ,'value', 'text' ,$selectedMemberOptions);

    	}else{
            $arguments[] = null;
                    //Leader and members list
            $leaderList = JHTML::_('select.genericlist', $memberOptions ,'id_leader', 'class="inputbox" size="1"');
            $memberList = JHTML::_('select.genericlist', $memberOptions ,'members[]', 'class="inputbox" multiple="multiple" size="5"');
    	}
    	
    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $team?$team->published:1));
    	
        $editor =& JFactory::getEditor();
        
        $mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

        $this->assignRef('team', $team, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('leaderList', $leaderList);
    	$this->assignRef('memberList', $memberList);
        $this->assignRef('editor', $editor);
        $this->assignRef('hierarchy', $hierarchy);

       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}
?>
