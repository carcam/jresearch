<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage		Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of single project views
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');


/**
 * HTML View class for single project management in JResearch Component backend
 *
 * @package    		JResearch
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
    	
    	$model =& $this->getModel();
    	$areaModel =& $this->getModel('researchareaslist');   	
    	$researchAreas = $areaModel->getData(null, true, false);

    	$arguments = array('facility');
    	
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
    	
    	if($cid)
    	{
        	$fac = $model->getItem($cid[0]);
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

    	$this->assignRef('fac', $fac);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('areasList', $researchAreasHTML);
		$this->assignRef('editor', $editor);    
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

       	parent::display($tpl);

    }
}

?>
