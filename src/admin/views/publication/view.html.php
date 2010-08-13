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

    	function display($tpl = null)
	{
            $mainframe = JFactory::getApplication();
            $layout = $this->getLayout();
            $arguments = array();

            switch($layout){
                    case 'new':
                            $this->_displayNewPublicationForm();
                            break;
                    case 'default':
                            $this->_displayPublicationForm($arguments);
                            break;
            }

            parent::display($tpl);

            if($layout == 'default')
                $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);

	}
	
	/**
	* Binds the variables useful for displaying the form for editing/creating
	* publications.
	*/
	private function _displayPublicationForm(&$arguments){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
		
		JResearchToolbar::editPublicationAdminToolbar();
                $form = &$this->get('Form');
                // get the Data
                $data = &$this->get('Data');
                // Bind the Data
                var_dump($form);
				
                $this->assignRef('form', $form);
                $this->assignRef('data', $data);
	}
	
	/**
	* Binds the variables for the form used to select the type 
	* for a new publication.
	*/
	private function _displayNewPublicationForm(){
	   JResearchToolbar::importPublicationsToolbar();
		$subtypes = JResearchPublication::getPublicationsSubtypes();
		$typesOptions = array();
		
		foreach($subtypes as $type){
			// Inproceedings is the same as conference 
			if($type != 'inproceedings')
				$typesOptions[] = JHTML::_('select.option', $type, $type.': '.JText::_('JRESEARCH_'.strtoupper($type)));			
		}
		
		$typesList = JHTML::_('select.genericlist', $typesOptions, 'pubtype', 'size="1"');		
		
		$this->assignRef('types', $typesList);
		
	}
}

?>
