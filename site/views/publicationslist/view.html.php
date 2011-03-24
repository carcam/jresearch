<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of publications
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for management of publications lists in JResearch Component frontend
 *
 */

class JResearchViewPublicationsList extends JResearchView
{
    public function display($tpl = null)
    {
    	global $mainframe;
    	
    	// Require css and styles
        $document =& JFactory::getDocument();
        $layout = $this->getLayout();
        
        //Add template path explicitly (useful when requesting from the backend)
        $this->addTemplatePath(JPATH_COMPONENT_SITE.DS.'views'.DS.'publicationslist'.DS.'tmpl');  

		switch($layout){
			// Template for making citations from TinyMCE editor
			case 'cite':
				$this->_displayCiteDialog();
				break;
			case 'generatebibliography':
				$this->_displayGenerateBibliographyDialog();
				break;		
		}
		
	    $eArguments = array('publications', $layout);
		
		
		parent::display($tpl);
    }
        
    /**
     * Binds the variables used by the layout. 
     *
     */
    private function _displayCiteDialog(){    	
    	global $mainframe;
    	
    	$citedRecordsOptionsHTML = array();
    	$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
    	
    	// Prepare the HTML document
    	$document =& JFactory::getDocument();
		$document->setTitle(JText::_('JRESEARCH_CITE_DIALOG'));
		$document->addScriptDeclaration("window.onDomReady(
			function(){
				var searchRequest = new XHR({method: 'get', onSuccess: addSearchResults, onFailure: onSearchFailure});
				searchRequest.send('index.php?option=com_jresearch&controller=publications&task=searchByPrefix&format=xml&key=%%&criteria=all', null);	
			 }
		);");				
		$citedRecordsListHTML = JHTML::_('select.genericlist',  $citedRecordsOptionsHTML, 'citedRecords', 'class="inputbox" id="citedRecords" size="10" style="width:200px;" ');
		JHTML::_('behavior.mootools');
		
		// Remove button
		$removeButton = '<button onclick="javascript:removeSelectedRecord()">'.JText::_('JRESEARCH_REMOVE').'</button>';
		$citeButton = '<button onclick="javascript:makeCitation(\'cite\')">'.JText::_('JRESEARCH_CITE').'</button>';
		$citeParentheticalButton = '<button onclick="javascript:makeCitation(\'citep\')">'.JText::_('JRESEARCH_CITE_PARENTHETICAL').'</button>';
		$citeYearButton = '<button onclick="javascript:makeCitation(\'citeyear\')">'.JText::_('JRESEARCH_CITE_YEAR').'</button>';
		$noCiteButton = '<button onclick="javascript:makeCitation(\'nocite\')">'.JText::_('JRESEARCH_NO_CITE').'</button>';
		$closeButton = '<button onclick="window.parent.document.getElementById(\'sbox-window\').close()">'.JText::_('JRESEARCH_CLOSE').'</button>';
		
		
		// Put the variables into the template
		$this->assignRef('citedRecords', $citedRecordsListHTML);
		$this->assignRef('removeButton', $removeButton);
		$this->assignRef('citeButton', $citeButton);
		$this->assignRef('citeParentheticalButton', $citeParentheticalButton);
		$this->assignRef('closeButton', $closeButton);
		$this->assignRef('citeYearButton', $citeYearButton);
		$this->assignRef('noCiteButton', $noCiteButton);
		$this->assignRef('url', $url);
		
    }
    
    private function _displayGenerateBibliographyDialog(){
    	$session = &JSession::getInstance(null,null);
    	$citedRecords = $session->get('citedRecords', array(), 'jresearch') ;
    	$citedRecordsOptionsHTML = array();
    	$document =& JFactory::getDocument();
		$document->setTitle(JText::_('JRESEARCH_GENERATE_BIBLIOGRAPHY'));
    	$model = &$this->getModel('Publication');
		JHTML::_('behavior.mootools');	
    	
		foreach($citedRecords as $pub){
    		$pubTitle = $pub;
    		if($model != null){    			
    			$pubRecord = $model->getItemByCitekey($pub);
    			if($pubRecord != null)
    			    	$pubTitle = $pubRecord->title;
    		}
			$citedRecordsOptionsHTML[] = JHTML::_('select.option', $pub, $pub.': '.$pubTitle);
		}
		$citedRecordsListHTML = JHTML::_('select.genericlist',  $citedRecordsOptionsHTML, 'citedRecords', 'class="inputbox" id="citedRecords" size="10" style="width:250px;" ');
		
		
		// Remove button
		$removeButton = '<button onclick="javascript:startSelectedRecordRemoval()">'.JText::_('JRESEARCH_REMOVE').'</button>';
		$removeAllButton = '<button onclick="javascript:startAllRemoval()">'.JText::_('JRESEARCH_REMOVE_ALL').'</button>';
		$generateBibButton = '<button onclick="javascript:requestBibliographyGeneration()">'.JText::_('JRESEARCH_GENERATE_BIBLIOGRAPHY').'</button>';
		$closeButton = '<button onclick="window.parent.document.getElementById(\'sbox-window\').close()">'.JText::_('JRESEARCH_CLOSE').'</button>';
		
		$this->assignRef('citedRecordsListHTML', $citedRecordsListHTML);
		$this->assignRef('removeButton', $removeButton);
		$this->assignRef('removeAllButton', $removeAllButton);
		$this->assignRef('closeButton', $closeButton);
		$this->assignRef('generateBibButton', $generateBibButton);			
    	
    }
}

?>