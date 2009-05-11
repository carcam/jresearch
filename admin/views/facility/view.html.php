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
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');      	
    	JRequest::setVar( 'hidemainmenu', 1 );
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	$fac = $model->getItem($cid[0]);
    	
    	$model =& $this->getModel();
    	$areaModel =& $this->getModel('researchareaslist');   	
    	$researchAreas = $areaModel->getData(null, true, false);
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
    	
		//Research areas 
		$researchAreasOptions = array();
    	foreach($researchAreas as $r)
    	{
    		$researchAreasOptions[] = JHTML::_('select.option', $r->id, $r->name);
    	}
    	
    	if($fac)
    	{
        	$arguments[] = $fac->id;
    	   	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $fac->published);   	
    	  	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox" size="5"', 'value', 'text', $fac->id_research_area);
    	}
    	else
    	{
    		$arguments[] = null;
    	   	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , 1);   		
    	 	$researchAreasHTML = JHTML::_('select.genericlist',  $researchAreasOptions, 'id_research_area', 'class="inputbox"" size="5"', 'value', 'text', 1); 
    	}
    	
    	//Order options
    	$orderOptions = array();
    	
    	/*
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_facilities ORDER by ordering ASC');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , $fac->ordering);*/

    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
    	//$this->assignRef('orderList', $orderList);
		$this->assignRef('editor', $editor);
		$this->assignRef('fac', $fac);
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterEditJResearchEntity', $arguments);
    }
}

?>
