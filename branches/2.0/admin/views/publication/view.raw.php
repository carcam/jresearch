<?php
/**
* @package		JResearch
* @subpackage	Views
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* View class for exportation to bibtex
*/

class JResearchAdminViewPublication extends JResearchView{

	function display($tpl = null){
		jresearchimport('helpers.exporters.factory', 'jresearch.admin');
        $session = JFactory::getSession();
        $exportOptions = array();

        $markedRecords = $session->get('markedRecords', null, 'com_jresearch.publications');
        if($markedRecords !== null){
            if($markedRecords === 'all'){
                $model = $this->getModel('Publications', 'JResearchAdminModel');
            }else{
                $model = $this->getModel();
            }    
            
            $publicationsArray = $model->getItems();            
            $strictBibtex = JRequest::getVar('strict_bibtex');
            if($strictBibtex == 'on')
                $exportOptions['strict_bibtex'] = true;

            $format = JRequest::getVar('outformat');
            $exporter = JResearchPublicationExporterFactory::getInstance($format);
            $output = $exporter->parse($publicationsArray, $exportOptions);
            $document = JFactory::getDocument();
            $document->setMimeEncoding($exporter->getMimeEncoding());
            $session->clear('markedRecords', 'com_jresearch.publications');

            if($format == 'bibtex')
                $ext = 'bib';
            else
                $ext = $format;
            
            $tmpfname = "jresearch_output.$ext";
            header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
            echo $output;
        }else{
        	$mainframe = JFactory::getApplication();
            JError::raiseNotice(1, JText::_('JRESEARCH_SELECT_ITEMS_TO_EXPORT'));
            $mainframe->redirect('index.php?option=com_jresearch&controller=publications');
        }       	
	}
	
}