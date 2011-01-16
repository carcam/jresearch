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
    	$model = $this->getModel();
    	$team = $model->getItem($cid[0]);
    	$membersModel = $this->getModel('Staff'); 	
    	$teamsModel = $this->getModel('Teams');
    	$hierarchy = $teamsModel->getHierarchical(false, false);
    	$params = JComponentHelper::getParams('com_jresearch');
    	    	
    	$selectedMemberOptions = array();
    	
    	if($cid){
            if(empty($team)){
                JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                return;
            }
            $arguments[] = $team;
            //Leader and members list
            $leaderList = JHTML::_('jresearchhtml.staffmemberslist', array('name' => 'id_leader', 'attributes' => 'class="inputbox"', 'selected' => $team->id_leader));
            $selectedMembers = $team->getMembers();
            foreach($selectedMembers as $member)
            {
               $selectedMemberOptions[] = $member['id_member'];
            }
            $memberList = JHTML::_('jresearchhtml.staffmemberslist', array('name' => 'members[]', 'attributes' => 'class="inputbox" multiple="multiple" size="5"', 'selected' => $selectedMemberOptions));
    	}else{
            $arguments[] = null;
            //Leader and members list
            $memberList = JHTML::_('jresearchhtml.staffmemberslist', array('name' => 'members[]', 'attributes' => 'class="inputbox" multiple="multiple" size="5"', 'selected' => array()));
    	}
    	    	
        $leaderList = JHTML::_('jresearchhtml.staffmemberslist', array('name' => 'id_leader', 'attributes' => 'class="inputbox"', 'selected' => !empty($team)? $team->id_leader : ''));    	
    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => !empty($team)? $team->published : 1));
    	
        $editor = JFactory::getEditor();
        
        $mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

        $this->assignRef('team', $team, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('leaderList', $leaderList);
    	$this->assignRef('memberList', $memberList);
        $this->assignRef('editor', $editor);
        $this->assignRef('hierarchy', $hierarchy);
        $this->assignRef('params', $params);

       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}
?>
