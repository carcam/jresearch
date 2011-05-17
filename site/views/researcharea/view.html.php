<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible of displaying a single research area information.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for management of research areas information in
 * JResearch Component frontend
 *
 */
class JResearchViewResearchArea extends JResearchView
{
    public function display($tpl = null)
    {
    	global $mainframe;
    	
    	$arguments = array('researcharea');
    	$doc = JFactory::getDocument();
    	
    	// Require css and styles
    	$id = JRequest::getInt('id', 1);
    	
    	if($id == 1){
            JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
            return;
    	}

        $model =& $this->getModel();
        $area = $model->getItem($id);
        //Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$format = $params->get('staff_format', 'last_first') == 'last_first'? 1 : 0;		    	
        if(empty($area) || !$area->published){
            JError::raiseWarning(1, JText::_('JRESEARCH_AREA_NOT_FOUND'));
            return;
        }

        $this->addPathwayItem($area->alias, 'index.php?option=com_jresearch&view=researcharea&id='.$area->id);

    	$teams = $model->getTeams($area->id);
    		
    	$description = str_replace('<hr id="system-readmore" />', '', $area->description);	
    		
    	$applyStyle = ($params->get('publications_apply_style') == 'yes');
    	$configuredCitationStyle = $params->get('citationStyle', 'APA');
    	if($applyStyle){
            // Require publications lang package
            $lang = JFactory::getLanguage();
            $lang->load('com_jresearch.publications');
    	}
    		    	
    	$doc->setTitle(JText::_('JRESEARCH_RESEARCH_AREA').' - '.$area->name);
        $arguments = array('researcharea', $area);
        $mainframe->triggerEvent('onPrepareJResearchContent', $arguments);
    		
    	$this->assignRef('teams', $teams);    	
    	$this->assignRef('publications_view_all', $publications_view_all);
    	$this->assignRef('projects_view_all', $projects_view_all);    	
    	$this->assignRef('theses_view_all', $theses_view_all);    	
        $this->assignRef('members', $members);
        $this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
        $this->assignRef('description', $description);
        $this->assignRef('applyStyle', $applyStyle);        
    	$this->assignRef('style', $configuredCitationStyle);
    	$this->assignRef('format', $format); 
        		
       	parent::display($tpl);
        $mainframe->triggerEvent('onAfterDisplayJResearchEntity', $arguments);
    }
}

?>