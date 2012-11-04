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
        $layout = $this->getLayout();
        
        switch($layout){
        	case 'edit':
        		$this->_displayEditProfile($tpl);
        		break;
        	case 'default':
        		$this->_displayProfile($tpl);
        		break;
        	default:
        		parent::display($tpl);
        		break;	
        }
    }
    
    /**
     * Displays a form where an authenticated user can edit his/her
     * profile.
     *
     */
    private function _displayEditProfile($tpl = null){
        JHtml::_('jresearchhtml.validation');
        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();
        
    	//Get the model
    	$model = $this->getModel();
    	
    	$form = $this->get('Form');
        // get the Data
        $data = &$this->get('Data');

        if(empty($data)){
        	JError::raiseWarning(1, JText::_('JRESEARCH_NOT_EXISTING_PROFILE'));
        	return;
        }
        
        // Bind the Data
        $form->bind($data);

        $this->assignRef('form', $form);
        $this->assignRef('data', $data);

        $mainframe->triggerEvent('onBeforeRenderJResearchEntityForm', array($data, 'member'));
        
        parent::display($tpl);
        
        $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', array($data, 'member'));
    }
    
    /**
    * Display the information of a published member.
    * 
    * @return boolean True if the information of the member was correctly bind to
    * the template file.
    */
    private function _displayProfile($tpl = null){
      	$mainframe = JFactory::getApplication();
        $doc = JFactory::getDocument();      	
        $pathway = $mainframe->getPathway();
      	jresearchimport('helpers.publications', 'jresearch.admin');
      	 	
    	$id = JRequest::getInt('id', null);
    	$publications_view_all = JRequest::getVar('publications_view_all', 0);
    	$projects_view_all = JRequest::getVar('projects_view_all', 0);    	    	
    	$theses_view_all = JRequest::getVar('theses_view_all', 0);


    	if(empty($id)){
    		JError::raiseError(404, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return false;
    	}
    
    	//Get the model
    	$model  = $this->getModel();
    	$member =  $model->getItem();
    	//$teams  =  $model->getTeams($id);
    	
    	if(!$member->published){
    		JError::raiseError(1, JText::_('JRESEARCH_MEMBER_NOT_FOUND'));
    		return false;
    	}
    	
    	$pathway->addItem(JFilterOutput::stringURLSafe($member->__toString()), 'index.php?option=com_jresearch&view=member&id='.$id);
    	$arguments[] = array('member', $member);
    	    	
    	//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    	
    	if($params->get('staff_show_publications', 1) == 1){
    		if($publications_view_all == 0){
    			$latestPublications = $params->get('staff_number_last_publications', 5);
    			$publications = $model->getLatestPublications($latestPublications);
    		}else{
    			$publications = $model->getLatestPublications();
    		}
    		
    		$this->assignRef('publications', $publications);
    		$this->assignRef('npublications', $model->countPublications($member->id));    		    		
    	}
    	
    	if($params->get('staff_show_projects', 1) == 1){
    		if($projects_view_all == 0){
	    		$latestProjects = $params->get('staff_number_last_projects', 5);
    			$projects = $model->getLatestProjects($latestProjects);
    		}else{
    			$projects = $model->getLatestProjects();
    		}
    		
    		$this->assignRef('projects', $projects);
    		$this->assignRef('nprojects', $model->countProjects($member->id));    		    		
    	}
    	
    	if($params->get('staff_show_theses', 1) == 1){
    		if($theses_view_all == 0){
	    		$latestTheses = $params->get('staff_number_last_theses', 5);
    			$theses = $model->getLatestTheses($latestTheses);
    		}else{
    			$theses = $member->getLatestTheses();
    		}
    		$this->assignRef('theses', $theses);
    		$this->assignRef('ntheses', $model->countTheses($member->id));
    	}
    	
    	$applyStyle = $params->get('publications_apply_style', 1);
    	$configuredCitationStyle = $params->get('citationStyle', 'APA');
    	if($applyStyle){
    		// Require publications lang package
            $lang = JFactory::getLanguage();
            $lang->load('com_jresearch.publications');
    	}    	
    	
    	$format = $params->get('staff_format', 'last_first');
    	$description = str_replace('<hr id="system-readmore" />', '', $member->description);
        $doc->setTitle(JResearchPublicationsHelper::formatAuthor($member->__toString(), $format));
    	$this->assignRef('params', $params);
    	
        // Bind variables for layout
    	$this->assignRef('publications_view_all', $publications_view_all);
    	$this->assignRef('projects_view_all', $projects_view_all);    	
    	$this->assignRef('theses_view_all', $theses_view_all);
    	$this->assignRef('params', $params);
    	$this->assignRef('member', $member, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('area', $area);
    	$this->assignRef('applyStyle', $applyStyle);
    	$this->assignRef('style', $configuredCitationStyle);
    	$this->assignRef('description', $description);
    	$this->assignRef('teams', $teams, JResearchFilter::ARRAY_OBJECT_XHTML_SAFE);
    	

    	$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);    	
    	parent::display($tpl);	        
	    $mainframe->triggerEvent('onAfterDisplayJResearchEntity', $arguments);   	
    	
    	return true;
    }
}

?>