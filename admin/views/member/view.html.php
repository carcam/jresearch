<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of a single member's profile
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for single member management in JResearch Component backend
 *
 */

class JResearchAdminViewMember extends JResearchView 
{
    function display($tpl = null){
    	JRequest::setVar( 'hidemainmenu', 1 );    	
      	JResearchToolbar::editMemberAdminToolbar();
        JHtml::_('jresearchhtml.validation');
        $mainframe = JFactory::getApplication();
        
        $form = $this->get('Form');
        // get the Data
        $data = &$this->get('Data');
        // Bind the Data
        $form->bind($data);

        $this->assignRef('form', $form);
        $this->assignRef('data', $data);

        $mainframe->triggerEvent('onBeforeRenderJResearchEntityForm', array('member'));
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', array('member'));    	
    }
}

?>
