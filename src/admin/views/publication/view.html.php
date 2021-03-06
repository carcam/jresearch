<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of a single publication
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for single publication management in JResearch Component backend
 *
 */
class JResearchAdminViewPublication extends JResearchView
{

    function display($tpl = null){
        $mainframe = JFactory::getApplication();
        $layout = $this->getLayout();

        switch($layout){
            case 'new':
                $this->_displayNewPublicationForm($tpl);
                break;
            case 'default':
                $this->_displayPublicationForm($tpl);
                break;
        }
    }
	
    /**
    * Binds the variables useful for displaying the form for editing
    * or creating publications.
    */
    private function _displayPublicationForm($tpl){
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;        
        JRequest::setVar( 'hidemainmenu', 1 );            
        JResearchToolbar::editPublicationAdminToolbar();        
        JHtml::_('jquery.framework', false);
        JHtml::_('jresearchhtml.tagit');
        JHtml::_('formbehavior.chosen', 'select');
        JHtml::_('jresearchhtml.validation');

        $form = $this->get('Form');
        // get the Data
        $data = $this->get('Data');
        // Bind the Data
        $form->bind($data);

        $pubtype = $jinput->get('pubtype', 
                isset($data['pubtype']) ? $data['pubtype'] : null, 'jform');

        $changeType = JHTML::_('jresearchhtml.publicationstypeslist', 
                'change_type', '', 
                isset($data['pubtype']) ? $data['pubtype'] : null);

        $this->assignRef('form', $form);
        $this->assignRef('data', $data);
        $this->assignRef('pubtype', $pubtype);
        $this->assignRef('changeType', $changeType);    

        $mainframe->triggerEvent('onBeforeRenderJResearchEntityForm', 
                array($data, 'publication'));
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', 
                array($data, 'publication'));            
    }

    /**
    * Binds the variables for the form used to select the type 
    * for a new publication.
    */
    private function _displayNewPublicationForm($tpl){
        JResearchToolbar::importPublicationsToolbar();
        $this->loadHelper('publications');
        $subtypes = JResearchPublicationsHelper::getPublicationsSubtypes();
        $typesOptions = array();

        foreach($subtypes as $type){
            // Inproceedings is the same as conference 
            if($type != 'inproceedings') {
                $typesOptions[] = JHTML::_('select.option', 
                        $type, 
                        $type.': '.JText::_('JRESEARCH_'.strtoupper($type)));
            }       
        }

        $typesList = JHTML::_('select.genericlist', 
                $typesOptions, 'pubtype', 'size="1"');		

        $this->assignRef('types', $typesList);		
        parent::display($tpl);		
    }
}

?>