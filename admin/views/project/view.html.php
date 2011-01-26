<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of single project views
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JRESEARCH_COMPONENT_SITE.DS.'includes'.DS.'CurrencyConvertor.php');

/**
 * HTML View class for single project management in JResearch Component backend
 *
 */

class JResearchAdminViewProject extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
      	JResearchToolbar::editProjectAdminToolbar();
    	      	
        JHTML::_('jresearchhtml.validation');
    	JRequest::setVar( 'hidemainmenu', 1 );
    	$params = JComponentHelper::getParams('com_jresearch');
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	$model =& $this->getModel();
	    $teamsModel =& $this->getModel('Teams');            
	    $hierarchy = $teamsModel->getHierarchical(false, false);            
    	    	
    	$project = $model->getItem($cid[0]);
    	$projectFins = array();
    	$cooperations = array();
    	

    	$principalFlags = null;    	
    	$members = null;
    	$arguments = array('project');
    	
    	if($cid){
            $projectFins = $project->getFinanciers();
            $arguments[] = $project;
            $members = $project->getAuthors();
            $principalFlags = $project->getPrincipalsFlagsArray();
            $cooperations = $project->getCooperations();
    	}else{
            $arguments[] = null;
    	}

    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $project?$project->published:1));
        $researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="1"', 'selected' => $project?$project->id_research_area:1));
        $statusHTML = JHTML::_('jresearchhtml.statuslist', array('name' => 'status', 'attributes' => 'class="inputbox" size="1"', 'selected' => $project?$project->status:'not_started'));
        $currencyHTML = JHTML::_('jresearchhtml.currencylist', array('name' => 'finance_currency', 'attributes' => 'class="inputbox"', 'selected' => $project?$project->finance_currency:1));
        $membersControl = JHTML::_('jresearchhtml.autoSuggest', 'members', $members, true, $principalFlags);
        
        //Get selected cooperations
        $coops = array();
        foreach($cooperations as $coop){
        	$coops[] = $coop->id;
        }        
        $coopHTML = JHTML::_('jresearchhtml.cooperations', array('name' => 'id_cooperation[]', 'attributes' => 'class="inputbox" size="5" multiple="multiple"', 'selected' => (count($coops) > 0)? $coops: ''));


        //Get selected fins and add it to replace
        $fins = array();
        foreach($projectFins as $fin)
        {
                $fins[] = $fin->id;
        }
        $finHTML = JHTML::_('jresearchhtml.financiers', array('name' => 'id_financier[]', 'attributes' => 'class="inputbox" size="5" multiple="multiple"', 'selected' => (count($fins) > 0) ? $fins : ''));

        $params = JComponentHelper::getParams('com_jresearch');
        if(!empty($project->files))
            $uploadedFiles = explode(';', trim($project->files));
        else
            $uploadedFiles = array();
        $files = JHTML::_('jresearchhtml.fileUpload', 'attachments', $params->get('files_root_path', 'files').DS.'projects','size="30" maxlength="255" class="validate-url"', false, $uploadedFiles);

        $mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

    	$this->assignRef('project', $project, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
    	$this->assignRef('finList', $finHTML);
    	$this->assignRef('coopList', $coopHTML);
    	$this->assignRef('currencyList', $currencyHTML);
        $this->assignRef('editor', $editor);
        $this->assignRef('membersControl', $membersControl);
        $this->assignRef('status', $statusHTML);
        $this->assignRef('files', $files);
        $this->assignRef('params', $params);
        $this->assignRef('hierarchy', $hierarchy);        
    	
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>