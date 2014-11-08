<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for publication of research areas information.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML Admin View class for single research area management in JResearch Component
 *
 */

class JResearchAdminViewResearchArea extends JResearchView
{

    function display($tpl = null)
    {
    	JRequest::setVar( 'hidemainmenu', 1 );    	
      	JResearchToolbar::editResearchAreaAdminToolbar();
        JHtml::_('jresearchhtml.validation');
        $mainframe = JFactory::getApplication();
        
        $form = $this->get('Form');
        // get the Data
        $data = $this->get('Data');
        // Bind the Data
        $form->bind($data);

        $this->assignRef('form', $form);
        $this->assignRef('data', $data);

        $mainframe->triggerEvent('onBeforeRenderJResearchEntityForm', array($data, 'researcharea'));
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', array($data, 'researcharea'));
     }
}

?>
