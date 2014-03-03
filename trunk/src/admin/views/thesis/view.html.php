<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of a single thesis
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for single thesis management in JResearch Component backend
 *
 */

class JResearchAdminViewThesis extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
        JResearchToolbar::editThesisAdminToolbar();

    	JRequest::setVar( 'hidemainmenu', 1 );
    	JHTML::_('jresearchhtml.validation');
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	$model =& $this->getModel();
    	$thesis = $model->getItem($cid[0]);
    	$directors = null;
    	$students = null;
    	$arguments = array('thesis');
    	
    	if($cid){
        	$arguments[] = $thesis->id;
    	  	$directors = $thesis->getDirectors();
    	  	$students = $thesis->getStudents();
    	}else{
    		$arguments[] = null;
    	}

    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $thesis?$thesis->published:1));
        $researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="1"', 'selected' => $thesis?$thesis->id_research_area:1));
        $statusHTML = JHTML::_('jresearchhtml.statuslist', array('name' => 'status', 'attributes' => 'class="inputbox" size="1"', 'selected' => $thesis?$thesis->status:1));
        $degreeHTML = JHTML::_('jresearchhtml.degreelist', array('name' => 'degree', 'attributes' => 'class="inputbox" size="1"', 'selected' => $thesis?$thesis->degree:'bachelor'));

        $studentsControl = JHTML::_('jresearchhtml.autoSuggest', 'students', $students);
        $directorsControl = JHTML::_('jresearchhtml.autoSuggest', 'directors', $directors);

        $params = JComponentHelper::getParams('com_jresearch');
        if(!empty($thesis->files))
                $uploadedFiles = explode(';', trim($thesis->files));
        else
                $uploadedFiles = array();
        $files = JHTML::_('jresearchhtml.fileUpload', 'attachments', $params->get('files_root_path', 'files').'/'.'theses','size="30" maxlength="255" class="validate-url"', false, $uploadedFiles);
		

    	$this->assignRef('thesis', $thesis, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
        $this->assignRef('editor', $editor);
        $this->assignRef('studentsControl', $studentsControl);
        $this->assignRef('directorsControl', $directorsControl);
        $this->assignRef('status', $statusHTML);
        $this->assignRef('degree', $degreeHTML);
        $this->assignRef('files', $files);

        // Load cited records
        $mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>
