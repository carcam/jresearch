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
		global $mainframe;
		
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
		JHTML::_('jresearchhtml.validation');		
		$arguments[] = 'publication';
		$doc = JFactory::getDocument();
		
		$cid = JRequest::getVar('cid');
		$isNew = !isset($cid);
		$osteotype = JRequest::getVar('osteotype');
		$authors = null;  
		$publication = JResearchPublication::getById($cid[0]);	
    	
		if(!$isNew)
		{			
			$arguments[] = $publication->id;
			$osteotype = $publication->osteotype;
			$this->assignRef('publication', $publication, JResearchFilter::OBJECT_XHTML_SAFE);
			$authors = $publication->getAuthors(true);
			$publicationTypes = JHTML::_('jresearchhtml.publicationsosteopathictypeslist', 'change_type');
			$this->assignRef('changeType', $publicationTypes);			
		}
		else
		{
			$arguments[] = null;
		}
		
		$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->published:1));
		$internalRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'internal', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->published:1));		
		$recommendedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'recommended', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->recommended:0));
		$statusRadio = JHTML::_('jresearchhtml.publicationsstatuslist', array('name' => 'status', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->status:'in_progress'));
		$languageList = JHTML::_('jresearchhtml.languagelist', 'id_language', 'class="inputbox"', 'id', 'name', !$isNew?$publication->id_language:0);
		$countriesList = JHTML::_('jresearchhtml.countrieslist', 'id_country', 'class="inputbox"', !$isNew?$publication->id_country:0);		
		$institutesList = JHTML::_('jresearchhtml.instituteslist', 'id_institute', 'class="inputbox"', isset($publication)?$publication->id_institute:0);		
		$sourcesList = JHTML::_('jresearchhtml.publicationsourceslist', array('name' => 'source', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->source:'ORW'));
		$params = JComponentHelper::getParams('com_jresearch');
		$authorsControl	= JHTML::_('jresearchhtml.autoSuggest', 'authors', $authors);		
		
		if(!empty($publication->files))
			$uploadedFiles = explode(';', trim($publication->files));
		else
			$uploadedFiles = array();	
		$files = JHTML::_('jresearchhtml.fileUpload', 'url', $params->get('files_root_path', 'files').DS.'publications','size="20" maxlength="255" class="validate-url"', true, $uploadedFiles);

		
		$this->assignRef('statusRadio', $statusRadio);
		$this->assignRef('sourcesList', $sourcesList);
		$this->assignRef('countriesList', $countriesList);
		$this->assignRef('institutesList', $institutesList);		
		$this->assignRef('recommendedRadio', $recommendedRadio);
		$this->assignRef('languageList', $languageList);	
		$this->assignRef('publishedRadio', $publishedRadio);
		$this->assignRef('internalRadio', $internalRadio );
		$this->assignRef('osteotype', $osteotype);
		$this->assignRef('authors', $authorsControl);
		$this->assignRef('files', $files);
	}
	
	/**
	* Binds the variables for the form used to select the type 
	* for a new publication.
	*/
	private function _displayNewPublicationForm(){
	    JResearchToolbar::importPublicationsToolbar();
		$subtypes = JResearchPublication::getPublicationsOsteopathicSubtypes();
		$typesOptions = array();
		
		foreach($subtypes as $type){
			// Inproceedings is the same as conference 
			$typesOptions[] = JHTML::_('select.option', $type, JText::_('JRESEARCH_'.strtoupper($type)));			
		}
		
		$typesList = JHTML::_('select.genericlist', $typesOptions, 'osteotype', 'size="1"');		
		
		$this->assignRef('types', $typesList);
		
	}
}

?>
