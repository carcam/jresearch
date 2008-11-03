<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	MtM
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of single mdm views
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for single mdm management in JResearch Component backend
 *
 */
class JResearchAdminViewMdm extends JView
{
    function display($tpl = null)
    {
    	global $mainframe;
      	JResearchToolbar::editMdmAdminToolbar();
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');      	
    	JRequest::setVar('hidemainmenu', 1);
    	
    	// Information about the mdm
    	$cid = JRequest::getVar('cid');
    	$editor =& JFactory::getEditor();
    	
    	$model =& $this->getModel();
    	$mModel =& $this->getModel('Staff');
    	$mdm = null;
    	$arguments = array('mdm');
    	
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));  

    	//Members options
    	$memberOptions = array();
    	$staff = $mModel->getData(); //get staff from model
    	
    	//Make options from staff
    	foreach($staff as $member)
    	{
    		$memberOptions[] = JHTML::_('select.option', $member->id, $member->firstname.' '.$member->lastname);
    	}
    	
    	if($cid)
    	{
        	$mdm = $model->getItem($cid[0]);
        	$arguments[] = $mdm->id;
        	
    	   	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $mdm->published);
    	   	$memberList = JHTML::_('select.genericlist', $memberOptions, 'id_member', 'class="inputbox"', 'value', 'text', $mdm->id_member);
    	}
    	else
    	{
    		$arguments[] = null;
    	   	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , 1);
    	   	$memberList = JHTML::_('select.genericlist', $memberOptions, 'id_member', 'class="inputbox"', 'value', 'text');
    	}

    	$this->assignRef('mdm', $mdm);
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('memberList', $memberList);
		$this->assignRef('editor', $editor);    
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

       	parent::display($tpl);
    }
}

?>
