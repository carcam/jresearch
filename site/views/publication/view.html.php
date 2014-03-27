<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single publication in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for presentation of a publication information.
 *
 */

class JResearchViewPublication extends JResearchView
{
    function display($tpl = null)
    {
    	$mainframe = JFactory::getApplication();    	
        $layout = $this->getLayout();

        switch($layout){
        	case 'new':
        		$result = $this->_displayNewPublicationForm($tpl);        		
        		break;
        	case 'edit':        		
        		$result = $this->_editPublication($tpl);
        		break;        		
        	case 'default': default:
        		if(!$this->_displayPublication($tpl))
        			parent::display($tpl);
        		break;
        		
        }
        		
    }
    
    /**
    * Display the information of a publication.
    */
    private function _displayPublication($tpl = null){
    	jresearchimport('helpers.publications', 'jresearch.admin');
    	
      	$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();      	
      	$user = JFactory::getUser();    	    	
        $doc = JFactory::getDocument();
        $session = JFactory::getSession();
        $id = JRequest::getInt('id', 0);
   		
    	if(empty($id)){
            JError::raiseError(404, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return false;
    	}
    	    	
    	//Get the model
    	$model = $this->getModel();
    	$publication = $model->getItem();
    	
        if($publication === false){
            JError::raiseError(404, JText::_('JRESEARCH_PUBLICATION_NOT_FOUND'));        	
			return false;
        }

    	if(!JResearchAccessHelper::itemAccessAllowed($publication, $user->get('id'))){
    		JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
    		return false;
    	}
        
        $pathway->addItem($publication->alias, 'index.php?option=com_jresearch&view=publication&id='.$publication->id);

        //If the publication was visited in the same session, do not increment the hit counter
        if(!$session->get('visited', false, 'com_jresearch.publication.'.$id)){
             $session->set('visited', true, 'com_jresearch.publication.'.$id);
             $publication->hit();
        }
    	
    	//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    			
    	
    	$arguments = array($publication, 'publication');    	
		$pageTitle = JText::_('JRESEARCH_PUBLICATION').' - '.$publication->title;
        $doc->setTitle($pageTitle);
    	
        // Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement', 'horizontal'));
    	$this->assignRef('publication', $publication, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('showHits', $params->get('show_hits', 1));
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
        $this->assignRef('user', $user, JResearchFilter::OBJECT_XHTML_SAFE);
        $this->assignRef('params', $params);
        $this->assignRef('showBibtex', $params->get('show_export_bibtex', 0));
    	$this->assignRef('showMODS', $params->get('show_export_mods', 0));	
    	$this->assignRef('showRIS', $params->get('show_export_ris', 0));
    	$this->assignRef('format', $this->params->get('staff_format', 'last_first'));	

    	$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);        
       	parent::display($tpl);       	
       	$mainframe->triggerEvent('onAfterDisplayJResearchEntity', $arguments);

       	return true;
    }
    
    private function _editPublication()
    {
        JHtml::_('jresearchhtml.validation');
        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();
        
    	//Get the model
    	$model = $this->getModel();

    	$form = $this->get('Form');
        // get the Data
        $data = &$this->get('Data');
           
        // Bind the Data
        $form->bind($data);

        $this->assignRef('form', $form);
        $this->assignRef('data', $data);

        $mainframe->triggerEvent('onBeforeRenderJResearchEntityForm', array($data, 'publication'));        
        parent::display($tpl);        
        $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', array($data, 'publication'));
    	
    }
    
	/**
	* Binds the variables for the form used to select the type 
	* for a new publication.
	*/
	private function _displayNewPublicationForm($tpl = null){
		jresearchimport('helpers.publications', 'jresearch.admin');
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
