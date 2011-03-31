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
        		$result = $this->_displayNewPublicationForm();
        		
        		$mainframe->triggerEvent('onBeforeNewJResearchPublication', $arguments);
        		break;
        	case 'edit':
        		$result = $this->_editPublication();
        		
        		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
        		break;
        		
        	case 'default': default:
        		$this->_displayPublication();
        		break;
        		
        }
        		
    }
    
    /**
    * Display the information of a publication.
    */
    private function _displayPublication(){
    	jresearchimport('helpers.publications', 'jresearch.admin');
    	
      	$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();      	
      	$user = JFactory::getUser();    	    	
        $doc = JFactory::getDocument();
        $session = JFactory::getSession();
        $id = JRequest::getInt('id', 0);
   		
    	if(empty($id)){
            JError::raiseError(404, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return;
    	}
    	
    	//Get the model
    	$model = $this->getModel();
    	$publication = $model->getItem();
    	
        if($publication === false){
            JError::raiseError(404, JText::_('JRESEARCH_PUBLICATION_NOT_FOUND'));        	
			return;
        }

        $pathway->addItem($publication->alias, 'index.php?option=com_jresearch&view=publication&id='.$publication->id);

        //If the publication was visited in the same session, do not increment the hit counter
        if(!$session->get('visited', false, 'com_jresearch.publication.'.$id)){
             $session->set('visited', true, 'com_jresearch.publication.'.$id);
             $publication->hit();
        }
    	
    	//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    			
        $showHits = ($params->get('show_hits', 'yes') == 'yes');
    	$format = $params->get('staff_format', 'last_first') == 'last_first'?1:0;		
    	$showBibtex = ($params->get('show_export_bibtex', 'no') == 'yes');
    	$showMODS = ($params->get('show_export_mods', 'no') == 'yes');    		
    	$showRIS = ($params->get('show_export_ris', 'no') == 'yes');    	
    	
    	$arguments = array('publication', $publication);
    	
		$pageTitle = $params->get('page_title', JText::_('JRESEARCH_PUBLICATION').' - '.$publication->title);
        $doc->setTitle($pageTitle);
    	
        // Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));
    	$this->assignRef('publication', $publication, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('showHits', $showHits);
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('showComments', $showComments);
    	$this->assignRef('captcha', $captchaInformation);
        $this->assignRef('user', $user, JResearchFilter::OBJECT_XHTML_SAFE);
        $this->assignRef('params', $params);
        $this->assignRef('format', $format);
        $this->assignRef('showBibtex', $showBibtex);
    	$this->assignRef('showMODS', $showMODS);	
    	$this->assignRef('showRIS', $showRIS);			

    	$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);        
       	parent::display($tpl);       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
    }
    
    private function _editPublication()
    {
    	JHTML::addIncludePath(JRESEARCH_COMPONENT_SITE.DS.'helpers'.DS.'html');
	require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'member.php');
	JHTML::_('jresearchhtml.validation');		
	$user = JFactory::getUser();
	$cid = JRequest::getVar('id', 0);	
	$this->assignRef('id', $cid);
	$doc = JFactory::getDocument();
	$isNew = ($cid == 0); 
	$doc->addScriptDeclaration('
		function msubmitform(pressbutton){
			if (pressbutton) {
				document.adminForm.task.value=pressbutton;
			}
			if (typeof document.adminForm.onsubmit == "function") {
				if(!document.adminForm.onsubmit())
				{
					return;
				}
				else
				{
					document.adminForm.submit();
				}
		}
		else
		{
			document.adminForm.submit();
		}
	}');
			
	if($isNew){
		$this->addPathwayItem(JText::_('Add'));
		$pubtype = JRequest::getVar('pubtype');					
	}else{
            $publication = JResearchPublication::getById($cid);
            $pubtype = $publication->pubtype;
            $this->addPathwayItem($publication->alias, 'index.php?option=com_jresearch&view=publication&id='.$publication->id);
            $this->addPathwayItem(JText::_('Edit'));
            $publicationTypes = JHTML::_('jresearchhtml.publicationstypeslist', 'change_type');
            $this->assignRef('publication', $publication, JResearchFilter::OBJECT_XHTML_SAFE);
            $this->assignRef('changeType', $publicationTypes, JResearchFilter::OBJECT_XHTML_SAFE);
	}

	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => !$isNew?$publication->published:1));
 	$researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="1"', 'selected' => !$isNew?$publication->id_research_area:null)); 
	$internalRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'internal', 'attributes' => 'class="inputbox"', 'selected' => !$isNew?$publication->published:1));			
	$authorsControl = JHTML::_('jresearchhtml.autoSuggest', 'authors' , !$isNew?$publication->getAuthors():array());
						
	$params = $this->getParams();
	if(!empty($publication->files))
		$uploadedFiles = explode(';', trim($publication->files));
	else
		$uploadedFiles = array();	
	$files = JHTML::_('JResearchhtml.fileUpload', 'url', $params->get('files_root_path', 'files').DS.'publications','size="30" maxlength="255" class="validate-url"', true, $uploadedFiles);
	
	$this->assignRef('user', $user, JResearchFilter::OBJECT_XHTML_SAFE);
	$this->assignRef('pubtype', $pubtype);
	$this->assignRef('areasList', $researchAreasHTML);
	$this->assignRef('publishedRadio', $publishedRadio);
	$this->assignRef('internalRadio', $internalRadio );
	$this->assignRef('authors', $authorsControl);
	$this->assignRef('files', $files);
	
	return true;
    }
    
	/**
	* Binds the variables for the form used to select the type 
	* for a new publication.
	*/
	private function _displayNewPublicationForm(){
		JHTML::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'html');
		JHTML::addIncludePath(JRESEARCH_COMPONENT_SITE.DS.'helpers'.DS.'html');
		$subtypes = JResearchPublication::getPublicationsSubtypes();
		$typesOptions = array();
		
		foreach($subtypes as $type){
			// Inproceedings is the same as conference 
			if($type != 'inproceedings')
				$typesOptions[] = JHTML::_('select.option', $type, $type.': '.JText::_('JRESEARCH_'.strtoupper($type)));			
		}
		
		$typesList = JHTML::_('select.genericlist', $typesOptions, 'pubtype', 'size="1"');		
		
		$this->assignRef('types', $typesList);
		return true;
	}
}

?>
