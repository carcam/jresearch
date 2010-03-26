<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single publication in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for presentation of a single project 
 * information.
 *
 */

class JResearchViewProject extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	$arguments = array();    	
    	$result = true;
        $layout = $this->getLayout();

        switch($layout){
        	case 'default':
        		$this->_displayProject();
        		break;
        	case 'edit':
        		$arguments[] = 'project';
        		$result = $this->_editProject($arguments);
        		if($result){
	        		$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
			       	parent::display($tpl);	
        		}	        		
        		break;
        }
	
    }
    
    /**
    * Display the information of a project.
    */
    private function _displayProject(){
      	global $mainframe;
    	$id = JRequest::getInt('id');
   		$doc =& JFactory::getDocument();
   		$statusArray = array('not_started'=>JText::_('JRESEARCH_NOT_STARTED'), 'in_progress'=>JText::_('JRESEARCH_IN_PROGRESS'), 'finished'=>JText::_('Finished'));
   		$arguments[] = 'project';

   		if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return false;
    	}
    	//Get the model
    	$model = $this->getModel();
    	$project = $model->getItem($id);
    	
    	if(empty($project)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
    		return false;
    	}
    	
		if(!$project->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			return false;
		}
    	JResearchPluginsHelper::onPrepareJResearchContent('project', $project);		

		$arguments[] = $id;
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($project->id_research_area);
    	$params = $mainframe->getPageParameters('com_jresearch');

    	$doc->setTitle(JText::_('JRESEARCH_PROJECT').' - '.$project->title);
    	// Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));
    	$this->assignRef('project', $project, JResearchFilter::OBJECT_XHTML_SAFE, array('exclude_keys' => array('description')));
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
    	
    	$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);		
		parent::display($tpl);	       	
		$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
    	
    	return true;

    }
    
   private function _editProject(&$arguments)
    {
    	global $mainframe;
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');      	
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor = JFactory::getEditor();
    	$model = $this->getModel('Project');
    	$project = $model->getItem($cid[0]);
    	
    	$areaModel = $this->getModel('researchareaslist');
    	$finModel = $this->getModel('financiers');
    	$researchAreas = $areaModel->getData(null, true, false);
    	$financiers = $finModel->getData(null, true, false);

    	$principalFlags = null;    	
    	$members = null;
    	$arguments = array('project');
    	
    	$doc = JFactory::getDocument();
		$doc->addScriptDeclaration('
		function msubmitform(pressbutton){
			if (pressbutton) {
				document.adminForm.task.value=pressbutton;
			}
			if (typeof document.adminForm.onsubmit == "function") {
				if(!document.adminForm.onsubmit())
				{
					return;
				}
				else
				{
					document.adminForm.submit();
				}
    		}
    		else
    		{
    			document.adminForm.submit();
    		}
    	}');
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	

		//Research areas 
		$researchAreasOptions = array();
    	foreach($researchAreas as $r){
    		$researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}
    	
    	//Status options
    	$statusOptions = array();
    	$statusOptions[] = JHTML::_('select.option', 'not_started', JText::_('JRESEARCH_NOT_STARTED'));
    	$statusOptions[] = JHTML::_('select.option', 'in_progress', JText::_('JRESEARCH_IN_PROGRESS'));
    	$statusOptions[] = JHTML::_('select.option', 'finished', JText::_('Finished'));
    	    	
    	//Currency options
    	$currencyOptions = array();
    	$currencyOptions[] = JHTML::_('select.option', 'EUR', 'Euro');
    	$currencyOptions[] = JHTML::_('select.option', 'USD', 'US Dollar');
    	
    	if($cid){
        	$projectFins = $project->getFinanciers();
        	$arguments[] = $project->id;
    	  	$members = $project->getAuthors();
    		$principalFlags = $project->getPrincipalsFlagsArray();    	  	
    	}else{
    		$arguments[] = null;
    		$projectFins = array();
    	}
    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $project?$project->published:1);   	
    	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox" size="5"', 'value', 'text', $project?$project->id_research_area:1);
    	$statusHTML = JHTML::_('select.genericlist', $statusOptions, 'status', 'class="inputbox" size="5"', 'value', 'text', $project?$project->status:'not_started');
    	$currencyHTML = JHTML::_('select.genericlist', $currencyOptions, 'finance_currency', 'class="inputbox"', 'value', 'text', $project?$project->finance_currency:null);

		$membersControl = JHTML::_('AuthorsSelector.autoSuggest', 'members', $members, true, $principalFlags);
		$fins = array();
		foreach($projectFins as $fin){
			$fins[] = $fin->id;
		}		
		$finHTML = JHTML::_('ProjectsControl.financiersControl', 'id_financier', $fins);

    	$this->assignRef('project', $project, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
    	$this->assignRef('finList', $finHTML);
    	$this->assignRef('currencyList', $currencyHTML);
		$this->assignRef('editor', $editor);    
		$this->assignRef('membersControl', $membersControl);	
		$this->assignRef('status', $statusHTML);
		$this->assignRef('id', $id);
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

       	parent::display($tpl);

       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>
