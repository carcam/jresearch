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
            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'member.php');
            global $mainframe;
            $arguments = array();
            
            JResearchToolbar::editPublicationAdminToolbar();
            JHTML::_('jresearchhtml.validation');
            $arguments[] = 'publication';
            $doc = JFactory::getDocument();

            $cid = JRequest::getVar('cid');
            $isNew = !isset($cid);
            $pubtype = JRequest::getVar('pubtype');
            $authors = null;
            $publication = JResearchPublication::getById($cid[0]);
	    	$teamsModel =& $this->getModel('Teams');            
	    	$hierarchy = $teamsModel->getHierarchical(false, false);
				    	            
            if(!$isNew)
            {
                $arguments[] = $publication;
                $mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
                $this->assignRef('publication', $publication, JResearchFilter::OBJECT_XHTML_SAFE);
                $authors = $publication->getAuthors();
                $publicationTypes = JHTML::_('jresearchhtml.publicationstypeslist', 'change_type');
                $this->assignRef('changeType', $publicationTypes);
            }
            else
            {
                $arguments[] = null;
            }

            $publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->published:1));
            $researchAreasHTML = JHTML::_('jresearchhtml.researchareas', array('name' => 'id_research_area', 'attributes' => 'class="inputbox" size="1"', 'selected' => $publication?$publication->id_research_area:null));
            $internalRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'internal', 'attributes' => 'class="inputbox"', 'selected' => $publication?$publication->published:1));

			$months = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');            
			$monthsOptions = array();
			$monthsOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_MONTH'));
			foreach($months as $month){
				$monthsOptions[] = JHTML::_('select.option', $month, JText::_('JRESEARCH_'.strtoupper($month)));
			}
            $monthsList = JHTML::_('select.genericlist', $monthsOptions, 'month', 'class="inputbox" size="1"' ,'value', 'text' , isset($publication)?$publication->month : '0');

            $params = JComponentHelper::getParams('com_jresearch');
            $authorsControl = JHTML::_('jresearchhtml.autoSuggest', 'authors', $authors);

            if(!empty($publication->files))
                $uploadedFiles = explode(';', trim($publication->files));
            else
                $uploadedFiles = array();

            $files = JHTML::_('jresearchhtml.fileUpload', 'url', $params->get('files_root_path', 'files').DS.'publications','size="20" maxlength="255" class="validate-url"', true, $uploadedFiles);

            $this->assignRef('areasList', $researchAreasHTML);
            $this->assignRef('publishedRadio', $publishedRadio);
            $this->assignRef('internalRadio', $internalRadio );
            $this->assignRef('pubtype', $pubtype);
            $this->assignRef('authors', $authorsControl);
            $this->assignRef('files', $files);
            $this->assignRef('hierarchy', $hierarchy);
            $this->assignRef('monthsList', $monthsList);            
            parent::display($tpl);

            $mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);

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
            parent::display($tpl);
	}
}

?>
