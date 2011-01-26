<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of single facility view
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for single project management in JResearch Component backend
 *
 */

class JResearchAdminViewFacility extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
      	JResearchToolbar::editFacilityAdminToolbar();
      	
        JHTML::_('jresearchhtml.validation');
    	JRequest::setVar( 'hidemainmenu', 1 );
    	$params = JComponentHelper::getParams('com_jresearch');
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	
    	$model =& $this->getModel();
    	$fac = $model->getItem($cid[0]);

    	$arguments = array('facility');
 
    	if($cid)
            $arguments[] = $fac;
    	else
            $arguments[] = null;

		$teamsModel = $this->getModel('Teams');
    	$hierarchy = $teamsModel->getHierarchical();
		$teamsList = JHTML::_('jresearchhtml.teamshierarchy', $hierarchy, array('name' => 'id_team', 'selected' => !empty($fac)? $fac->id_team : null));    	
            
    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $fac?$fac->published:1));
        $researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="1"', 'selected' => $fac?$fac->id_research_area:1));
        $mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

    	$this->assignRef('fac', $fac, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
        $this->assignRef('editor', $editor);
        $this->assignRef('params', $params);
        $this->assignRef('teamsList', $teamsList);
    	
       	parent::display($tpl);

       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>
