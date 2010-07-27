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
            case 'filtered':
                $this->_displayTabularList();
                break;
            default:
                $this->_displayFrontendList();
                break;
        }

        $eArguments = array('publications', $layout);

        $mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);

        parent::display($tpl);

        $mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
    
	/**
     * Invoked when the user has selected the option to show lists of publications
     * filtered by team groups and showing the journal acceptance rate per each publication
     * and calculating the average per group.
     */
    private function _displayTabularList(){
    	global $mainframe;    	
    	
    	$doc = JFactory::getDocument();
    	$doc->addStyleDeclaration(".title{text-align:center;}");
    	
    	$teamId = JRequest::getVar('teamid');
    	$model = $this->getModel();
    	$lists = array();    	

    	$items = $model->getData(null, true, true);
    	$page = $model->getPagination();
    	$params = $mainframe->getParams('com_jresearch'); 
		$field = $params->get('field_for_average');    	
    	
    	if($params->get('show_average') == 'yes'){
            $average = $model->getAverage($field);
            $this->assignRef('average', $average);
    	}
    	
    	
    	$format = $params->get('staff_format') == 'last_first'?1:0;
    	$showScore = $params->get('show_score');
    	
    	$doc->setTitle(JText::_('JRESEARCH_PUBLICATIONS'));
    
    	$this->_setFilter();
    	
    	$this->assignRef('items', $items);
    	$this->assignRef('page', $page);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('showScore', $showScore);
    	$this->assignRef('punctuationField', $field);
    	$this->assignRef('format', $format);
    }
    
    /**
     * Renders the publications frontend list. It uses the configured citation
     * style for the format.
     *
     */
    private function _displayFrontendList(){
    	global $mainframe;
    	
    	$doc = JFactory::getDocument();
    	$params = $mainframe->getParams('com_jresearch');    	
    	$filter_pubtype = $params->get('filter_pubtype', '0');
    	
    	if($filter_pubtype != '0')
    	{
    		//Only in this case, force the model (ignore the filters)
    		JRequest::setVar('filter_pubtype', $filter_pubtype);
    	}
    	
    	$user = JFactory::getUser();
    	$filter_show = $params->get('filter_show', '0');
    	
    	//My publications
    	$id_member = -1;
    	
    	if($filter_show == "my")
    	{
    		//Only in this case, force the model (ignore the filters)	    	
    		$member = JTable::getInstance('Member', 'JResearch');
    		$member->bindFromUsername($user->username);
    		$id_member = $member->id;    	
   		    JRequest::setVar('filter_author', $id_member); 			
    	}
    	
    	$document =& JFactory::getDocument();    	
    	//$document->setTitle('Publications');
    	$feed = 'index.php?option=com_jresearch&amp;view=publicationslist&amp;format=feed';
		$rss = array(
			'type' => 'application/rss+xml',
			'title' => JText::_('Publications RSS Feed')
		);
		$document->addHeadLink(JRoute::_($feed.'&type=rss'), 'alternate', 'rel', $rss);
    	
    	$model = $this->getModel();
    	$publications = $model->getData(null, true, true);
    	
    	// Get certain variables
    	$filter_order = JRequest::getVar('filter_order', 'year');
    	$filter_order_Dir = JRequest::getVar('filter_order_Dir', 'DESC');
    	$style = $params->get('citationStyle', 'APA');
    	
    	//Now time to sort the data for presentation
    	$sortedItems = $this->_sort($publications, $style, $filter_order);
    	    	
    	$showmore = ($params->get('show_more', 'yes') == 'yes');
    	$showdigital = ($params->get('show_digital') == 'yes');
    	$layout = $params->get('publications_default_sorting', 'year');
    	$exportAll = ($params->get('show_export_all') == 'yes');
    	$showAllFormat = $params->get('show_export_all_format', 'bibtex');
    	$showBibtex = ($params->get('show_export_bibtex') == 'yes');
    	$showMODS = ($params->get('show_export_mods') == 'yes');    		
    	$showRIS = ($params->get('show_export_ris') == 'yes');    	
    	
    	$this->_setFilter();
    	
    	$doc->setTitle(JText::_('JRESEARCH_PUBLICATIONS'));
    	    	
    	// Bind variables used in layout
    	$this->assignRef('items', $sortedItems);
    	$this->assignRef('page', $model->getPagination());
    	$this->assignRef('user', $user);
    	$this->assignRef('showmore', $showmore);
    	$this->assignRef('showdigital', $showdigital);
    	$this->assignRef('style', $style);
    	$this->assignRef('layout', $layout);
    	$this->assignRef('exportAll', $exportAll);
    	$this->assignRef('showAllFormat', $showAllFormat);
    	$this->assignRef('showBibtex', $showBibtex);
    	$this->assignRef('showMODS', $showMODS);	
    	$this->assignRef('showRIS', $showRIS);    	
        	
    }
    
    private function _setFilter()
    {
    	global $mainframe;
    	
    	$params = $mainframe->getParams('com_jresearch');  
    	$layout = $this->getLayout();
    	
    	$filter = JHTML::_('jresearch.publicationfilter',
    		$layout,
    		($params->get('filter_teams', 'yes') == 'yes'),
    		($params->get('filter_areas', 'yes') == 'yes'),
    		($params->get('filter_year', 'yes') == 'yes'),
    		($params->get('filter_search', 'yes') == 'yes'),
    		($params->get('filter_type', 'yes') == 'yes'),
    		($params->get('filter_authors', 'yes') == 'yes')
    	);
    	
    	$this->assignRef('filter', $filter);
    }
    
    /**
     * Performs records grouping before pushing items into layout according to
     * configuration. It assumes records array is sorted by $filter_order criteria.
     *
     * @param array $recordsArray Publications array
     * @param string $style Citation style that defines sorting rules
     * @param string $filter_order
     * @return array If $filter_order is 'year' or 'type' It returns an associative array
     * where the key is the label used to group the publications, otherwise it just returns
     * a conventional array of sorted publications.
     *
     */
    private function _sort($recordsArray, $style = 'APA', $filter_order = 'year'){
        $styleObj = JResearchCitationStyleFactory::getInstance($style);
    	$result = array();
    	
    	// Do the grouping
        switch($filter_order){
                case 'year':
                        $previousYear = null;
                        $yearHeader = null;
                        foreach($recordsArray as $pub){
                                if($previousYear != $pub->year){
                                        if($yearHeader != null)
                                                $result[$yearHeader] = $styleObj->sort($result[$yearHeader]);

                                        if($pub->year == '0000' || $pub->year == null )
                                                $yearHeader = JText::_('JRESEARCH_NO_YEAR');
                                        else
                                                $yearHeader = JText::_('JRESEARCH_YEAR').': '.$pub->year;

                                        $result[$yearHeader] = array();
                                }
                                $result[$yearHeader][] = $pub;
                                $previousYear = $pub->year;
                        }
                        if(isset($result[$yearHeader]))
                                $result[$yearHeader] = $styleObj->sort($result[$yearHeader]);
                break;
                case 'type':
                        $previousType = null;
                        $header = null;
                        foreach($recordsArray as $pub){
                                if($previousType != $pub->pubtype){
                                        if($header != null)
                                                $result[$header] = $styleObj->sort($result[$header]);

                                        $header = JText::_('JRESEARCH_PUBLICATION_TYPE').': '.$pub->pubtype;
                                        $result[$header] = array();
                                }
                                $result[$header][] = $pub;
                                $previousType = $pub->pubtype;
                        }
                        if($result[$header])
                                $result[$header] = $styleObj->sort($result[$header]);
                        break;
                default:
                        $result = $recordsArray;
        }

        return $result;
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