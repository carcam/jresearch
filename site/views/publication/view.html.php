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
    	global $mainframe;
    	$id = JRequest::getVar('id', 0);
    	
    	$arguments = array('publication', $id);
    	
        $layout = &$this->getLayout();
        $result = true;

        switch($layout){
        	case 'default':
        		$result = $this->_displayPublication();
        		
        		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
        		break;
        	case 'new':
        		$result = $this->_displayNewPublicationForm();
        		
        		$mainframe->triggerEvent('onBeforeNewJResearchPublication', $arguments);
        		break;
        	case 'edit':
        		$result = $this->_editPublication();
        		
        		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
        		break;
        }
        
    	if($result)
		{
       		parent::display($tpl);
       	
       		$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
		}
    }
    
    /**
    * Display the information of a publication.
    */
    private function _displayPublication(){
      	global $mainframe;
      	require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');      	
    	
      	$id = JRequest::getInt('id');
    	$user = JFactory::getUser();    	    	
    	$commentsAllowed = false;
   		$showComments = JRequest::getInt('showcomm', 0);
   		$doc =& JFactory::getDocument();
   		//Verify if the visit is done in the same session
		$session = JFactory::getSession();
   		 		
   		JHTML::_('jresearchhtml.validation');   
   		$config = array('filePath'=>JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'views'.DS.'publication'.DS.'captcha');   			
   		$doc->addScript(JURI::base().'components/com_jresearch/views/publication/comments.js');
   		
    	if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return false;
    	}
    	//Get the model
    	$model =& $this->getModel();
    	$publication = $model->getItem($id);
    	
		if(!$publication->internal || !$publication->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_FOUND'));
			return false;
		}		    	
		
		$this->addPathwayItem(JText::_('New'), 'index.php?option=com_jresearch&view=publication&task=new');
		
		//If the publication was visited in the same session, do not increment the hit counter
		if(!$session->get('visited', false, 'publications'.$id)){
			$session->set('visited', true, 'publications'.$id);
			$publication->hit();
		}
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($publication->id_research_area);
    	
    	//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		if($params->get('publications_allow_comentaries') == 'yes'){
			$user =& JFactory::getUser();
		 	$from = $params->get('publications_allow_comentaries_from');	
			if($from == 'everyone' || (!$user->guest && $from == 'users')){
				$commentsAllowed = true;
			}
			
			jximport('jxtended.captcha.captcha');
 		 	$captcha = &JXCaptcha::getInstance('image', $config);
 		 	if(!$captcha->initialize())
 		 		JError::raiseWarning(1, JText::_('JRESEARCH_CAPTCHA_NOT_INITIALIZED'));
 	
    		if (!is_array($captchaInformation = $captcha->create())) {
	 			JError::raiseWarning(1, JText::_('JRESEARCH_CAPTCHA_NOT_INITIALIZED'));
	    	}
	    	
	    	// Get the comments
	    	$limit = JRequest::getVar('limit', 5);
	    	$limitStart = JRequest::getVar('limitstart', 0);
	    	$comments = $model->getComments($publication->id, $limit, $limitStart);			
	    	$total = $model->countComments($publication->id);
	    		    	
	    	$this->assignRef('comments', $comments);
	    	$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitStart);	    	
			$this->assignRef('total', $total);
			
		}
    	    	
    	
    	// Cross referencing
		$missingFields = $publication->getReferencedFields();
		if(!empty($missingFields)){
			$count = 0;
			$crossrefData = "<tr>";
			foreach($missingFields as $key=>$value){
				if($count % 2 == 0 && $count > 0){
					$crossrefData .= "<tr>";
				}		
				$crossrefData .= "<th scope=\"row\">".JResearchText::_($key).": </th><td>".trim($value)."</td>";
				$count++;	
				if($count % 2 == 0 && $count > 0){
					$crossrefData .= "</tr>";
				}
		
			} 
			if($count % 2 != 0)
				$crossrefData .= "<td></td><td></td></tr>";
			
			$this->assignRef('reference', $crossrefData);	
		}
		
		$showHits = ($params->get('show_hits') == 'yes');
    	$format = $params->get('staff_format') == 'last_first'?1:0;		
    	$showBibtex = ($params->get('show_export_bibtex') == 'yes');
    	$showMODS = ($params->get('show_export_mods') == 'yes');    		
    	$showRIS = ($params->get('show_export_ris') == 'yes');    	
    	
		
		$doc->setTitle(JText::_('JRESEARCH_PUBLICATION').' - '.$publication->title);
    	// Bind variables for layout
    	$this->assignRef('staff_list_arrangement', $params->get('staff_list_arrangement'));
    	$this->assignRef('publication', $publication, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('showHits', $showHits);
    	$this->assignRef('area', $area, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('commentsAllowed', $commentsAllowed);
    	$this->assignRef('showComments', $showComments);
    	$this->assignRef('captcha', $captchaInformation);
		$this->assignRef('user', $user, JResearchFilter::OBJECT_XHTML_SAFE);
		$this->assignRef('params', $params);
		$this->assignRef('format', $format);
		$this->assignRef('showBibtex', $showBibtex);
    	$this->assignRef('showMODS', $showMODS);	
    	$this->assignRef('showRIS', $showRIS);			
		
		
		return true;

    }
    
    private function _editPublication()
    {
    	JHTML::addIncludePath(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'html');
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
		JHTML::_('jresearchhtml.validation');		
		$user =& JFactory::getUser();
		$cid = JRequest::getVar('id', 0);
		
		$pubtype = JRequest::getVar('pubtype');
		
		$this->assignRef('id', $cid);
		$doc = JFactory::getDocument();
		$isNew = ($cid > 0); 
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
				
		if($isNew)
		{    		
			$publication = JResearchPublication::getById($cid);
			$pubtype = $publication->pubtype;
			
			$this->addPathwayItem($publication->alias, 'index.php?option=com_jresearch&view=publication&id='.$publication->id);
			$this->addPathwayItem(JText::_('Edit'));					
			$this->assignRef('publication', $publication, JResearchFilter::OBJECT_XHTML_SAFE);	
			$publicationTypes = JHTML::_('jresearchhtml.publicationstypeslist', 'change_type');
			$this->assignRef('changeType', $publicationTypes, JResearchFilter::OBJECT_XHTML_SAFE);						
		}
		else 
		{
			$this->addPathwayItem(JText::_('Add'));			
		}

		$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => !$isNew?$publication->published:1));
   	 	$researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="1"', 'selected' => !$isNew?$publication->id_research_area:null)); 
		$internalRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'internal', 'attributes' => 'class="inputbox"', 'selected' => !$isNew?$publication->published:1));			
		$authorsControl = JHTML::_('jresearchhtml.autoSuggest', 'authors' , !$isNew?$publication->getAuthors():array());
						
		$recommendedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'recommended', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->recommended:1));
		$statusRadio = JHTML::_('jresearchhtml.publicationstatuslist', array('name' => 'status', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->status:'in_progress'));
		$languageList = JHTML::_('jresearchhtml.languagelist', 'id_language', 'class="inputbox"', 'id', 'name', $publication?$publication->id_language:0);
		
		$params = $this->getParams();
		if(!empty($publication->files))
			$uploadedFiles = explode(';', trim($publication->files));
		else
			$uploadedFiles = array();	
		$files = JHTML::_('JResearchhtml.fileUpload', 'url', $params->get('files_root_path', 'files').DS.'publications','size="30" maxlength="255" class="validate-url"', true, $uploadedFiles);
		
		$this->assignRef('statusRadio', $statusRadio);
		$this->assignRef('recommendedRadio', $recommendedRadio);
		$this->assignRef('languageList', $languageList);
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
		JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'html');
		JHTML::addIncludePath(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'html');
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