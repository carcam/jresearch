<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright		Copyright (C) 2008 Luis Galarraga.
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
                    $this->_displayNewPublicationForm();
                        break;
                    case 'default':
                        $this->_displayPublicationForm();
                        break;
            }
	}
	
	/**
	* Binds the variables useful for displaying the form for editing/creating
	* publications.
	*/
	private function _displayPublicationForm(){
        $mainframe = JFactory::getApplication();
    	JRequest::setVar( 'hidemainmenu', 1 );            
		JResearchToolbar::editPublicationAdminToolbar();
		JHtml::_('jresearchhtml.validation');
			
	    $form = $this->get('Form');
	    // get the Data
	    $data = &$this->get('Data');
        // Bind the Data
    	$form->bind($data);
			
        $pubtype = JRequest::getVar('pubtype', $data['pubtype'], 'jform');
        
        $changeType = JHTML::_('jresearchhtml.publicationstypeslist', 'change_type');
            
        $this->assignRef('form', $form);
        $this->assignRef('data', $data);
        $this->assignRef('pubtype', $pubtype);
        $this->assignRef('changeType', $changeType);    
        
        $mainframe->triggerEvent('onBeforeRenderJResearchEntityForm', array($data, 'publication'));
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', array($data, 'publication'));            
	}
	
	/**
	* Binds the variables for the form used to select the type 
	* for a new publication.
	*/
	private function _displayNewPublicationForm(){
	   	JResearchToolbar::importPublicationsToolbar();
	   	$this->loadHelper('publications');
		$subtypes = JResearchPublicationsHelper::getPublicationsSubtypes();
		$typesOptions = array();
		
		foreach($subtypes as $type){
			// Inproceedings is the same as conference 
			if($type != 'inproceedings')
				$typesOptions[] = JHTML::_('select.option', $type, $type.': '.JText::_('JRESEARCH_'.strtoupper($type)));			
		}
		
		$typesList = JHTML::_('select.genericlist', $typesOptions, 'pubtype', 'size="1"');		
		
		$this->assignRef('types', $typesList);		
        parent::display($tpl);		
	}
}

?>