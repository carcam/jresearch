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

/**
 * HTML View class for single project management in JResearch Component backend
 *
 */

class JResearchAdminViewMember_position extends JResearchView 
{
    function display($tpl = null)
    {
    	global $mainframe;
      	JResearchToolbar::editMember_positionAdminToolbar();
      	
		JHTML::_('jresearchhtml.validation');   	
    	JRequest::setVar( 'hidemainmenu', 1 );
    	
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	
    	$model =& $this->getModel();
    	$position = $model->getItem($cid[0]); 

    	$arguments = array('member_position');
    	
    	if($cid)
    		$arguments[] = $position->id;
    	else 
    		$arguments[] = null;

    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $position?$position->published:1));
		
    	$this->assignRef('item', $position, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio);
 
       	$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>