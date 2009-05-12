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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for single publication management in JResearch Component backend
 *
 */

class JResearchAdminViewPublication extends JView
{
	function display($tpl = null){
 		$layout = $this->getLayout();
 		JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'html');
 		
 		switch($layout){
 			case 'new':
 				$this->_displayNewPublicationForm();
 				break;
 			case 'default':
 				$this->_displayPublicationForm();
 				break;	
 		}
 	  	
		parent::display($tpl);
	}
	
	/**
	* Binds the variables useful for displaying the form for editing/creating
	* publications.
	*/
	private function _displayPublicationForm(){
		global $mainframe;
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
		
		JResearchToolbar::editPublicationAdminToolbar();
		JHTML::_('jresearchhtml.validation');		
		
		$cid = JRequest::getVar('cid');
		$isNew = !isset($cid);
		$arguments = array('publication');
		$pubtype = JRequest::getVar('pubtype');
		$authors = null;  
		$publication = JResearchPublication::getById($cid[0]);	
    	
		if(!$isNew)
		{			
			$arguments[] = $publication->id;
			$this->assignRef('publication', $publication);
			$authors = $publication->getAuthors();
		}
		else
		{
			$arguments[] = null;
		}
		
		//Lists
		$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->published:1));
   	 	$researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="5"', 'selected' => $publication?$publication->id_research_area:null)); 
		$internalRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'internal', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->published:1));
		
		$params = JComponentHelper::getParams('com_jresearch');
		$authorsControl = JHTML::_('jresearchhtml.authorsSelector', 'authors' ,$authors);		

		if(!empty($publication->files))
			$uploadedFiles = explode(';', trim($publication->files));
		else
			$uploadedFiles = array();	
		$files = JHTML::_('jresearchhtml.fileUpload', 'url', $params->get('files_root_path', 'files').DS.'publications','size="30" maxlength="255" class="validate-url"', true, $uploadedFiles);
		
		$this->assignRef('areasList', $researchAreasHTML);
		$this->assignRef('publishedRadio', $publishedRadio);
		$this->assignRef('internalRadio', $internalRadio );
		$this->assignRef('pubtype', $pubtype);
		$this->assignRef('authors', $authorsControl);
		$this->assignRef('files', $files);
		
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
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
