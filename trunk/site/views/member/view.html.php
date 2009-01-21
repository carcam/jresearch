<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single member's profile in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of a member's profile.
 *
 */

class JResearchViewMember extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        switch($layout){
        	case 'edit':
        		$value = $this->_displayEditProfile();
        		break;
        	default:
        		$value = $this->_displayProfile();
        		break;
        }
	
        if($value)
	        parent::display($tpl);
    }
    
    /**
     * Displays a form where an authenticated user can edit his/her
     * profile.
     *
     */
    private function _displayEditProfile(){
    	global $mainframe;
    	
    	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'toolbar.jresearch.html.php');
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');
    	JResearchToolbar::editMemberAdminToolbar();		
		    	
    	$user =& JFactory::getUser();
    	$model = $this->getModel();
    	$member = $model->getByUsername($user->username);
    	$areaModel = $this->getModel('ResearchArea');
    	$doc = JFactory::getDocument();
    
    	// Modify it, so administrators may edit the item.
    	if(empty($member->username)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_PROFILE_USER_NOT_AUTHORIZED'));
    		return;
    	}
    	
    	if($member->isCheckedOut($user->get('id'))){
			JError::raiseWarning(1, JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
			return;
		}
		
		$member->checkout($user->get('id'));		
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
		$editor =& JFactory::getEditor();    	
    	
    	$doc->setTitle(JText::_('JRESEARCH_MEMBER').' - '.$member);
    	$this->assignRef('member', $member);
    	$this->assignRef('areasList', $researchAreasHTML);
    	$this->assignRef('publishedRadio', $publishedRadio);
		$this->assignRef('editor', $editor);    	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments); 	
		return true;
    	
    }
    
    /**
    * Display the information of a published member.
    * 
    * @return boolean True if the information of the member was correctly bind to
    * the template file.
    */
    private function _displayProfile(){
      	global $mainframe;
    	$id = JRequest::getInt('id');
    	$publications_view_all = JRequest::getVar('publications_view_all', 0);
    	$projects_view_all = JRequest::getVar('projects_view_all', 0);    	    	
    	$theses_view_all = JRequest::getVar('theses_view_all', 0);

    	if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return false;
    	}
    
    	//Get the model
    	$model =& $this->getModel();
    	$member = $model->getItem($id);
    	
    	if(!$member->published){
    		JError::raiseWarning(1, JText::_('JRESEARCH_MEMBER_NOT_FOUND'));
    		return false;
    	}
    	
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($member->id_research_area);
    	
    	//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    	
    	if($params->get('staff_show_publications', 'yes') == 'yes'){
    		if($publications_view_all == 0){
    			$latestPublications = $params->get('staff_number_last_publications', 5);
    			$publications = $model->getLatestPublications($member->id, $latestPublications);
    		}else{
    			$publications = $model->getLatestPublications($member->id);
    		}
    		$this->assignRef('publications', $publications);
    		$this->assignRef('npublications', $model->countPublications($member->id));    		    		
    	}
    	
    	if($params->get('staff_show_projects', 'yes') == 'yes'){
    		if($projects_view_all == 0){
	    		$latestProjects = $params->get('staff_number_last_projects', 5);
    			$projects = $model->getLatestProjects($member->id, $latestProjects);
    		}else{
    			$projects = $model->getLatestProjects($member->id);
    		}
    		$this->assignRef('projects', $projects);
    		$this->assignRef('nprojects', $model->countProjects($member->id));    		    		
    	}
    	
    	if($params->get('staff_show_theses', 'yes') == 'yes'){
    		if($theses_view_all == 0){
	    		$latestTheses = $params->get('staff_number_last_theses', 5);
    			$theses = $model->getLatestTheses($member->id, $latestTheses);
    		}else{
    			$theses = $member->getLatestTheses($member->id);
    		}
    		$this->assignRef('theses', $theses);
    		$this->assignRef('ntheses', $model->countTheses($member->id));
    	}
    	
    	// Bind variables for layout
    	$this->assignRef('publications_view_all', $publications_view_all);
    	$this->assignRef('projects_view_all', $projects_view_all);    	
    	$this->assignRef('theses_view_all', $theses_view_all);
    	$this->assignRef('params', $params);
    	$this->assignRef('member', $member);
    	$this->assignRef('area', $area);
    	return true;
    }
}

?>