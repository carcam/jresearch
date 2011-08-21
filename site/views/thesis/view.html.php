<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single publication in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for presentation of a single thesis 
 * information.
 *
 */

class JResearchViewThesis extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	$arguments = array('thesis');
    	
        $layout = &$this->getLayout();
        switch($layout){
        	case 'default':
        		$this->_displayThesis($arguments);
        		break;
        }
	
        $mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
    	parent::display($tpl);
    	
    	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
    }
    
    /**
    * Display the information of a thesis.
    */
    private function _displayThesis(&$arguments){
      	global $mainframe;
      	require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');
      	      	
    	$id = JRequest::getInt('id');
   		$doc =& JFactory::getDocument();
   		$session = JFactory::getSession();
   		$statusArray = array('not_started'=>JText::_('JRESEARCH_NOT_STARTED'), 'in_progress'=>JText::_('JRESEARCH_IN_PROGRESS'), 'finished'=>JText::_('Finished'));
   		$degreeArray = array('bachelor'=>JText::_('JRESEARCH_BACHELOR'), 'master'=>JText::_('JRESEARCH_MASTER'), 'phd'=>JText::_('JRESEARCH_PHD'));

   		if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return;
    	}
    	//Get the model
    	$model =& $this->getModel();
    	$thesis = $model->getItem($id);
    	
		if(!$thesis->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_THESIS_NOT_FOUND'));
			return;
		}

		$this->addPathwayItem($thesis->alias, 'index.php?option=com_jresearch&view=thesis&id='.$thesis->id);
		
		$arguments[] = $id;
		
		//If the thesis was visited in the same session, do not increment the hit counter
		if(!$session->get('visited', false, 'theses'.$id)){
			$session->set('visited', true, 'theses'.$id);
			$thesis->hit();
		}
		
        $doc->setTitle(JText::_('JRESEARCH_THESIS').' - '.$thesis->title);
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($thesis->id_research_area);
    	$params = $mainframe->getPageParameters('com_jresearch');    	
		$showHits = ($params->get('show_hits') == 'yes');
    	$format = $params->get('staff_format') == 'last_first'?1:0;
    	$description = str_replace('<hr id="system-readmore" />', '', trim($thesis->description));
		
    	// Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));    	
    	$this->assignRef('showHits', $showHits);     	
    	$this->assignRef('thesis', $thesis, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('statusArray', $statusArray);
    	$this->assignRef('degreeArray', $degreeArray);
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('format', $format);
    	$this->assignRef('description', $description);

    }
}

?>
