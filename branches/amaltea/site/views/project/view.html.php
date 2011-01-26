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
    	$result = true;
        $layout = $this->getLayout();

        switch($layout){
            case 'default':
            default:
                $this->_displayProject();
                break;
        }

    }
    
    /**
    * Display the information of a project.
    */
    private function _displayProject(){
      	global $mainframe;
      	require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');
        $arguments = array('project');
      	
    	$id = JRequest::getInt('id');
		$doc =& JFactory::getDocument();
		$statusArray = array('not_started'=>JText::_('JRESEARCH_NOT_STARTED'), 'in_progress'=>JText::_('JRESEARCH_IN_PROGRESS'), 'finished'=>JText::_('Finished'));

        if(empty($id)){
            JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
            return;
        }
    	//Get the model
    	$model =& $this->getModel();
    	$project = $model->getItem($id);
    	
    	if(empty($project) || !$project->published){
            JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
            return;
    	}
    	
    	$this->addPathwayItem($project->alias, 'index.php?option=com_jresearch&view=project&id='.$project->id);
    	$arguments[] = $project;
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($project->id_research_area);
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$coops = $project->getCooperations();
    	$description = str_replace('<hr id="system-readmore" />', '', trim($project->description));
    	$format = $params->get('staff_format', 'last_first') == 'last_first'? 1 : 0;
        
    	$doc->setTitle(JText::_('JRESEARCH_PROJECT').' - '.$project->title);
        $mainframe->triggerEvent('onPrepareJResearchContent', $arguments);

        // Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));
    	$this->assignRef('project', $project, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('coops', $coops);
    	$this->assignRef('description', $description);
    	$this->assignRef('enableThumbnails', $params->get('thumbnail_enable', 1));
        $this->assignRef('showHits', $params->get('show_hits'));
        $this->assignRef('format', $format);
        $this->assignRef('categorizedBy', $params->get('categorized_by', 'teams'));
    	
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterDisplayJResearchEntity', $arguments);

    }
}

?>
