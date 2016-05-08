<?php
/**
* @package		JResearch
* @subpackage	Views
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* View class for exportation to bibtex
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class JResearchAdminViewPublications extends JResearchView{

    function display($tpl = null){
        jresearchimport('helpers.exporters.factory', 'jresearch.admin');
        $session = JFactory::getSession();
        $exportOptions = array();
        $jinput = JFactory::getApplication()->input;
        $markedRecords = $session->get('markedRecords', null, 'com_jresearch.publications');
        if($markedRecords !== null){
            if($markedRecords === 'all'){
                $model = $this->getModel();            	
            }else{
                $jinput->set('cid', $markedRecords);            	
                $model = $this->getModel('Publication', 'JResearchAdminModel');            	
            }    

            $publicationsArray = $model->getItems();            
            $strictBibtex = $jinput->get('strict_bibtex');
            if($strictBibtex == 'on')
                $exportOptions['strict_bibtex'] = true;

            $format = $jinput->get('outformat');
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