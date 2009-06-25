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



/**
 * HTML View class for presentation of a member's profile.
 *
 */

class JResearchViewMember extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	
        $layout = &$this->getLayout();
        $arguments = array('member');
        $params = JComponentHelper::getParams('com_jresearch');
        
        switch($layout){
        	case 'edit':
        		$value = $this->_displayEditProfile($arguments);
        		break;
        	default:
        		$value = $this->_displayProfile($arguments);
        		break;
        }
        
        $this->assignRef('params', $params);
	
        if($value)
        {
	        parent::display($tpl);
	        
	        $mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
        }
    }
    
    /**
     * Displays a form where an authenticated user can edit his/her
     * profile.
     *
     */
    private function _displayEditProfile(array &$arguments){
    	global $mainframe;
    	
    	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'toolbar.jresearch.html.php');
    	JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'html');
      	JHTML::addIncludePath(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'html');
		JHTML::_('JResearch.validation');
    	JResearchToolbar::editMemberAdminToolbar();		
		    	
    	$user =& JFactory::getUser();
    	$model = $this->getModel();
    	$member = $model->getByUsername($user->username);
    	$areaModel = $this->getModel('ResearchArea');
    	$doc = JFactory::getDocument();
    
    	// Modify it, so administrators may edit the item.
    	if(empty($member->username)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_PROFILE_USER_NOT_AUTHORIZED'));
    		return false;
    	}
    	
    	if($member->isCheckedOut($user->get('id'))){
			JError::raiseWarning(1, JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
			return false;
		}
		
		$member->checkout($user->get('id'));		
    	
		$researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'selected' => $member->id_research_area));
    	
		//Published options  	
		$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'selected' => $member->published));
    	
    	$editor =& JFactory::getEditor();    	
    	
    	$doc->setTitle(JText::_('JRESEARCH_MEMBER').' - '.$member->__toString());

    	$this->assignRef('member', $member, JResearchFilter::OBJECT_XHTML_SAFE);
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
    private function _displayProfile(array &$arguments){
      	global $mainframe;
      	require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'publications.php');
      	
    	$id = JRequest::getInt('id');
    	$publications_view_all = JRequest::getVar('publications_view_all', 0);
    	$projects_view_all = JRequest::getVar('projects_view_all', 0);    	    	
    	$theses_view_all = JRequest::getVar('theses_view_all', 0);

    	if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		$arguments[] = null;
    		return false;
    	}
    
    	//Get the model
    	$model =& $this->getModel();
    	$member = $model->getItem($id);
    	
    	if(!$member->published){
    		JError::raiseWarning(1, JText::_('JRESEARCH_MEMBER_NOT_FOUND'));
    		$arguments[] = null;
    		return false;
    	}
    	
    	$arguments[] = $id;
    	
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
    	
    	$applyStyle = ($params->get('publications_apply_style') == 'yes');
    	$configuredCitationStyle = $params->get('citationStyle', 'APA');
    	
    	$format = $params->get('staff_format') == 'last_first'?1:0;

    	// Bind variables for layout
    	$this->assignRef('publications_view_all', $publications_view_all);
    	$this->assignRef('projects_view_all', $projects_view_all);    	
    	$this->assignRef('theses_view_all', $theses_view_all);
    	$this->assignRef('params', $params);
    	$this->assignRef('member', $member, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('area', $area);
    	$this->assignRef('applyStyle', $applyStyle);
    	$this->assignRef('style', $configuredCitationStyle);
    	$this->assignRef('format', $format);
    	
    	$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
    	
    	return true;
    }
}

?>