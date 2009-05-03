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

jimport( 'joomla.application.component.view');


/**
 * HTML View class for single project management in JResearch Component backend
 *
 */

class JResearchAdminViewFacility extends JView
{
    function display($tpl = null)
    {
    	global $mainframe;
      	JResearchToolbar::editFacilityAdminToolbar();
      	
		JHTML::_('JResearch.validation');      	
    	JRequest::setVar( 'hidemainmenu', 1 );
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	
    	$model =& $this->getModel();
    	$fac = $model->getItem($cid[0]);

    	$arguments = array('facility');
 
    	if($cid)
        	$arguments[] = $fac->id;
    	else
    		$arguments[] = null;

    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $fac?$fac->published:1));
   	 	$researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="5"', 'selected' => $fac?$fac->id_research_area:1));
    	
    	$this->assignRef('fac', $fac);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
		$this->assignRef('editor', $editor);    
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

       	parent::display($tpl);

       	$mainframe->triggerEvent('onAfterEditJResearchEntity', $arguments);
    }
}

?>
