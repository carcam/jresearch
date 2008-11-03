<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Publications
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of publications
* in the backend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of publications lists in JResearch Component frontend
 *
 */



class JResearchViewPublicationsList extends JView
{
    public function display($tpl = null)
    {
    	// Require css and styles
        $document =& JFactory::getDocument();
        //Add template path explicitly (useful when requesting from the backend)
        $this->addTemplatePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'views'.DS.'publicationslist'.DS.'tmpl');   

		switch($this->getLayout()){
			// Template for making citations from TinyMCE editor
			case 'cite':
				$this->_displayCiteDialog();
				break;
			case 'generatebibliography':
				$this->_displayGenerateBibliographyDialog();
				break;	
			default:
				$this->_displayFrontendList();
				break;	
		}

        parent::display($tpl);
    }
    
    /**
     * Renders the publications frontend list. It uses the configured citation
     * style for the format.
     *
     */
    private function _displayFrontendList(){
    	global $mainframe;
    	
    	$document =& JFactory::getDocument();    	
    	$document->setTitle('Publications');
    	$feed = 'index.php?option=com_jresearch&view=publicationslist&format=feed';
		$rss = array(
			'type' => 'application/rss+xml',
			'title' => JText::_('Publications RSS Feed')
		);
		$document->addHeadLink(JRoute::_($feed.'&type=rss'), 'alternate', 'rel', $rss);
    	
    	$model = $this->getModel();
    	$publications = $model->getData(null, true, true);

    	// Get the current citation style
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$citationStyle = $params->get('citationStyle', 'APA');
    	$style =& JResearchCitationStyleFactory::getInstance($citationStyle);
    	
    	// Get certain variables
    	$filter_order = JRequest::getVar('filter_order', 'year');
    	$filter_order_Dir = JRequest::getVar('filter_order_Dir', 'DESC');
    	
    	// Bind variables used in layout
    	$this->assignRef('items', $publications);
    	$this->assignRef('style', $style);
    	$this->assignRef('page', $model->getPagination());
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
