<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
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

class JResearchAdminViewProject extends JResearchView
{
    function display($tpl = null){
		$mainframe = JFactory::getApplication();
    	JRequest::setVar( 'hidemainmenu', 1 );            
		JResearchToolbar::editProjectAdminToolbar();
		JHtml::_('jresearchhtml.validation');
			
	    $form = $this->get('Form');
	    // get the Data
	    $data = &$this->get('Data');
        // Bind the Data
    	$form->bind($data);
			            
        $this->assignRef('form', $form);
        $this->assignRef('data', $data);
        $this->assignRef('pubtype', $pubtype);
        
        $mainframe->triggerEvent('onBeforeRenderJResearchEntityForm', array($data, 'project'));
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', array($data, 'project'));
    	
    }
}

?>