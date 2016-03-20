<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
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

class JResearchViewPublications extends JResearchView
{
    public function display($tpl = null)
    {
        $layout = $this->getLayout();        
        //Add template path explicitly (useful when requesting from the backend)
        $this->addTemplatePath(JRESEARCH_COMPONENT_SITE.DS.'views'.DS.'publications'.DS.'tmpl');  
        
        switch($layout){
            // Template for making citations from TinyMCE editor
            case 'cite':
               $this->_displayCiteDialog();
               break;
            case 'cite2':
               $this->_displayCiteFromFormDialog();
               break;		        
            case 'generatebibliography':
                $this->_displayGenerateBibliographyDialog();
                break;
            case 'tabular':
                $this->_displayTabularList($tpl);
                break;
            default:
                $this->_displayFrontendList($tpl);
                break;
        }

    }
    
	/**
     * Invoked when the user has selected the option to show lists of publications
     * filtered by team groups and showing the journal acceptance rate per each publication
     * and calculating the average per group.
     */
    private function _displayTabularList($tpl = null){
    	$mainframe = JFactory::getApplication();    	    	
    	$doc = JFactory::getDocument();
    	$params = $mainframe->getParams('com_jresearch');    	
    	$doc->addStyleDeclaration(".title{text-align:center;}");
    	    	    	
    	$user = JFactory::getUser();
    	$model = $this->getModel();
    	$items = $model->getItems();
    	$page = $model->getPagination();
    	    	       
    	$this->_setFilters();
    	
    	$pageHeader = $params->get('page_heading', JText::_('JRESEARCH_PUBLICATIONS'));
    	$showHeader = $params->get('show_page_heading', 1);
    	$showYear = $params->get('show_year', 1);
    	$showResearchAreas = $params->get('show_research_areas', 1);
    	$showBibtex = $params->get('show_export_bibtex', 0);
    	$showRis = $params->get('show_export_ris', 0);
    	$showMods = $params->get('show_export_mods', 0);
    	$showHits = $params->get('show_hits', 0);
    	$format = $params->get('staff_format', 'last_first');
    	$showScore = $params->get('show_score', 0);
    	$showAuthors = $params->get('show_authors', 1);
        $field = $params->get('field_for_score', 'impact_factor');    	    	    	
        $exportAll = $params->get('show_export_all', 0);
    	$exportAllFormat = $params->get('show_export_all_format', 'bibtex');
    	$showFulltext = $params->get('show_fulltext', 0);
    	$showDigitalVersion = $params->get('show_digital_version', 0);

    	$this->assignRef('exportAll', $exportAll);
    	$this->assignRef('exportAllFormat', $exportAllFormat);
    	$this->assignRef('header', $pageHeader);
    	$this->assignRef('showHeader', $showHeader);    	
    	$this->assignRef('items', $items);
    	$this->assignRef('page', $page);
    	$this->assignRef('lists', $lists);
    	$this->assignRef('showScore', $showScore);
    	$this->assignRef('format', $format);
    	$this->assignRef('showYear', $showYear);
    	$this->assignRef('showRis', $showRis);
        $this->assignRef('showMods', $showMods);
    	$this->assignRef('showHits', $showHits);
    	$this->assignRef('showBibtex', $showBibtex);
    	$this->assignRef('showResearchAreas', $showResearchAreas);
    	$this->assignRef('showAuthors', $showAuthors);    	
    	$this->assignRef('fieldForPunctuation', $field);
    	$this->assignRef('showFulltext', $showFulltext);
    	$this->assignRef('showDigitalVersion', $showDigitalVersion);

        $eArguments = array('publications', $this->getLayout());
        $mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    	
    }
    
    /**
     * Renders the publications frontend list. It uses the configured citation
     * style for the format.
     *
     */
    private function _displayFrontendList($tpl){
    	$mainframe = JFactory::getApplication();
        $jinput = JFactory::getApplication()->input;    	
    	$doc = JFactory::getDocument();
    	$params = $mainframe->getParams('com_jresearch');
    	
    	$document = JFactory::getDocument();
    	$feed = 'index.php?option=com_jresearch&amp;view=publicationslist&amp;format=feed';
        $rss = array(
                'type' => 'application/rss+xml',
                'title' => JText::_('Publications RSS Feed')
        );
        $document->addHeadLink(JRoute::_($feed.'&type=rss'), 'alternate', 'rel', $rss);
    	
    	$model = $this->getModel();
    	$publications = $model->getItems();
    	
    	// Get certain variables
    	$filter_order = $jinput->get('filter_order', 'year');
    	$filter_order_Dir = $jinput->get('filter_order_Dir', 'DESC');
    	$style = $params->get('citationStyle', 'APA');
    	
    	//Now time to sort the data for presentation
    	$groupedItems = $this->_group($publications, $filter_order);
    	    	
    	$showmore = $params->get('show_more', 1);
    	$showFulltext = $params->get('show_fulltext', 0);
        $fullTextTag = $params->get('fulltext_tag');
    	$showDigitalVersion = $params->get('show_digital_version', 0);
        $digitalVersionTag = $params->get('digital_version_tag');
    	$layout = $params->get('publications_default_sorting', 'year');
    	$exportAll = $params->get('show_export_all', 0);
    	$showAllFormat = $params->get('show_export_all_format', 'bibtex');
    	$showBibtex = $params->get('show_export_bibtex', 0);
    	$showMODS = $params->get('show_export_mods', 0);    		
    	$showRIS = $params->get('show_export_ris', 0);        
    	
    	$this->_setFilters();    	
    	$pageHeader = $params->get('page_heading', JText::_('JRESEARCH_PUBLICATIONS'));
    	$showHeader = $params->get('show_page_heading', 1);
    	    	    	
    	// Bind variables used in layout
    	$this->assignRef('items', $groupedItems);
        $page = $model->getPagination();
    	$this->assignRef('page', $page);
    	$this->assignRef('user', $user);
    	$this->assignRef('showmore', $showmore);
    	$this->assignRef('showDigital', $showDigitalVersion);
        $this->assignRef('digitalVersionTag', $digitalVersionTag);
    	$this->assignRef('showFulltext', $showFulltext);
        $this->assignRef('fullTextTag', $fullTextTag);
    	$this->assignRef('style', $style);
    	$this->assignRef('layout', $layout);
    	$this->assignRef('exportAll', $exportAll);
    	$this->assignRef('showAllFormat', $showAllFormat);
    	$this->assignRef('showBibtex', $showBibtex);
    	$this->assignRef('showMODS', $showMODS);	
    	$this->assignRef('showRIS', $showRIS);    	
    	$this->assignRef('header', $pageHeader);
    	$this->assignRef('showHeader', $showHeader);
    	
        $eArguments = array('publications', $this->getLayout());
        $mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
        parent::display($tpl);
        $mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
    
    private function _setFilters()
    {
    	$mainframe = JFactory::getApplication();    	
    	$params = $mainframe->getParams('com_jresearch');  
    	$layout = $this->getLayout();
    	
    	$filter = $this->_publicationsFilter($layout,
    		$params->get('show_filter_areas', 1) == 1,
    		$params->get('show_filter_year', 1) == 1,
    		$params->get('show_filter_search', 1) == 1,
    		$params->get('show_filter_type', 1) == 1,
    		$params->get('show_filter_authors', 1) == 1
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
    private function _group($recordsArray, $filter_order = 'year'){
    	$result = array();
    	
    	// Do the grouping
        switch($filter_order){
            case 'year':
                $previousYear = null;
                $yearHeader = null;
                foreach($recordsArray as $pub){
                    if($previousYear != $pub->year){
                        if($pub->year == '0000' || $pub->year == null )
                            $yearHeader = JText::_('JRESEARCH_NO_YEAR');
                        else
                            $yearHeader = $pub->year;

                        $result[$yearHeader] = array();
                    }
                    $result[$yearHeader][] = $pub;
                    $previousYear = $pub->year;
                }
                break;
            case 'pubtype':
                $previousType = null;
                $header = null;
                foreach($recordsArray as $pub){
                    if($previousType != $pub->pubtype){
                        $header = $pub->pubtype;
                        $result[$header] = array();
                    }
                    $result[$header][] = $pub;
                    $previousType = $pub->pubtype;
                }
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
    	$citedRecordsOptionsHTML = array();
    	$url = JURI::root();
        $jinput = JFactory::getApplication()->input;        
        $citeAnswer = preg_replace('#[^A-Z0-9\-\_\[\]]#i', '', $jinput->get('e_name'));   	
    	$citeFailedMsg = JText::_('JRESEARCH_CITE_FAILED');
    	$citeSuccessfulMsg = JText::_('JRESEARCH_CITE_SUCCESSFUL');
    	$noItemsMsg = JText::_('JRESEARCH_NO_ITEMS_TO_CITE');
        $recordRepeatedMsg = JText::_('CITED_RECORD_REPEATED');
        $nextMsg = JText::_('JRESEARCH_NEXT');
        $backMsg = JText::_('JRESEARCH_BACK');  	
    	// Prepare the HTML document
    	$document = JFactory::getDocument();
        $document->setTitle(JText::_('JRESEARCH_CITE_DIALOG'));
        $document->addScript($url.'components/com_jresearch/js/cite.js');        
        $document->addScriptDeclaration("
        var messages = {\"back\": \"$backMsg\", \"next\": \"$nextMsg\", \"citeRepeated\" : \"$recordRepeatedMsg\" , \"citeFailed\" : \"$citeFailedMsg\", \"citeSuccessful\" : \"$citeSuccessfulMsg\", \"noItems\" : \"$noItemsMsg\"};
        window.addEvent('domready',
                function(){
        	    window.document.getElementById('title').addEventListener (\"keyup\", startPublicationSearch);                
                    var searchRequest = new Request({method: 'get', async: true, onSuccess: addSearchResults, onFailure: onSearchFailure});
                    searchRequest.send('option=com_jresearch&controller=publications&task=searchByPrefix&format=xml&key=%%&criteria=all', null);
                 }
        );");
        $document->addStyleDeclaration("ul.citefields { margin: auto; width: -webkit-fit-content; width: -moz-fit-content; width: fit-content; }"
                ."ul.citefields li{ display: inline-block; float: left; margin-right: 35px;}"
                ."ul.citefields li label{ display: inline-block; margin-right: 5px; }");
        $citedRecordsListHTML = JHTML::_('select.genericlist',  $citedRecordsOptionsHTML, 'citedRecords', 'class="inputbox" id="citedRecords" size="10" style="width:50%;height:100px;" ');
        JHtml::_('behavior.framework');

        // Remove button
        $removeButton = '<button onclick="javascript:removeSelectedRecord()">'.JText::_('JRESEARCH_REMOVE').'</button>';
        $citeButton = '<button onclick="javascript:makeCitation(\'cite\')">'.JText::_('JRESEARCH_CITE').'</button>';
        $citeParentheticalButton = '<button onclick="javascript:makeCitation(\'citep\')">'.JText::_('JRESEARCH_CITE_PARENTHETICAL').'</button>';

        // Put the variables into the template
        $this->assignRef('citedRecords', $citedRecordsListHTML);
        $this->assignRef('removeButton', $removeButton);
        $this->assignRef('citeButton', $citeButton);
        $this->assignRef('citeParentheticalButton', $citeParentheticalButton);
        $this->assignRef('url', $url);
        
        parent::display();		
    }
    
    private function _displayGenerateBibliographyDialog(){
    	jresearchimport('helpers.publications', 'jresearch.admin');
    	
    	$session = JSession::getInstance(null,null);
    	$citedRecords = $session->get('citedRecords', array(), 'com_jresearch') ;
    	$citedRecordsOptionsHTML = array();
    	$document = JFactory::getDocument();
        $document->setTitle(JText::_('JRESEARCH_GENERATE_BIBLIOGRAPHY'));
        JHtml::_('behavior.framework');

        foreach($citedRecords as $pub){
            $pubTitle = $pub;
            $pubRecord = JResearchPublicationsHelper::getItemByCitekey($pub);
            if($pubRecord != null){
                $pubTitle = $pubRecord->title;            
                $citedRecordsOptionsHTML[] = JHTML::_('select.option', $pub, $pub.': '.$pubTitle);
            }
        }
        
        $citedRecordsListHTML = JHTML::_('select.genericlist',  $citedRecordsOptionsHTML, 'citedRecords', 'class="inputbox" id="citedRecords" size="10" style="width:60%;height:100px;" ');

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
        
        parent::display();        
    }
    
    /**
     * Displayed when linking publications to other data
     * items.
     * 
     */
    private function _displayCiteFromFormDialog(){
    	$url = JURI::root();
        $jinput = JFactory::getApplication()->input;	
    	// Prepare the HTML document
    	$document = JFactory::getDocument();
    	$citeFailedMsg = JText::_('JRESEARCH_CITE_FAILED');
    	$citeSuccessfulMsg = JText::_('JRESEARCH_CITE_SUCCESSFUL');
    	$noItemsMsg = JText::_('JRESEARCH_NO_ITEMS_TO_CITE');
        $recordRepeatedMsg = JText::_('CITED_RECORD_REPEATED');
        $nextMsg = JText::_('JRESEARCH_NEXT');
        $backMsg = JText::_('JRESEARCH_BACK');  	
        $document->setTitle(JText::_('JRESEARCH_CITE_DIALOG'));
        $document->addScript($url.'components/com_jresearch/js/cite.js');
        
        $citekeys = $jinput->get('value', '');
        if (strlen($citekeys) > 0) {
            $citekeysArray = explode(',', $citekeys);            
            // Update the javascript container
            $elements = "";            
            foreach ($citekeysArray as $citekey) {
                $elements .= "selectedCitekeys.push('$citekey');";
            }
            $document->addScriptDeclaration($elements);
        } else {
            $citekeysArray = array();
        }
        $citedRecordsListHTML = JHtml::_('jresearchfrontend.citekeysHTMLList', 'citedRecords', $citekeysArray);
        
        
        $document->addScriptDeclaration("
                var messages = {\"back\": \"$backMsg\", \"next\": \"$nextMsg\", \"citeRepeated\" : \"$recordRepeatedMsg\" , \"citeFailed\" : \"$citeFailedMsg\", \"citeSuccessful\" : \"$citeSuccessfulMsg\", \"noItems\" : \"$noItemsMsg\"};
        		window.addEvent('domready',
        		function(){
        			window.document.getElementById('title').addEventListener (\"keyup\", startPublicationSearch);
                    var searchRequest = new Request({method: 'get', async: true, onSuccess: addSearchResults, onFailure: onSearchFailure});
                    searchRequest.send('option=com_jresearch&controller=publications&task=searchByPrefix&format=xml&key=%%&criteria=all', null);
                 }
        );");

        JHtml::_('behavior.framework');

        // Remove button
        $removeButton = '<button onclick="javascript:removeSelectedRecord()">'.JText::_('JRESEARCH_REMOVE').'</button>';
        $citeButton = '<button onclick="javascript:makeCitation(\'cite\')">'.JText::_('JRESEARCH_CITE').'</button>';

        // Put the variables into the template
        $this->assignRef('citedRecords', $citedRecordsListHTML);
        $this->assignRef('removeButton', $removeButton);
        $this->assignRef('citeButton', $citeButton);
        $this->assignRef('url', $url);
        
        parent::display();
    }
    
    /**
     * Returns div-container with publication filters, can be activated with given parameter switches
     *
     * @param string $layout
     * @param bool $bTeams
     * @param bool $bAreas
     * @param bool $bYear
     * @param bool $bSearch
     * @param bool $bType
     * @param bool $bAuthors
     * @return string
     */
    private function _publicationsFilter($layout, $bAreas = true, $bYear = true, $bSearch = true, $bType = true, $bAuthors = true) {
        jresearchimport('helpers.publications', 'jresearch.admin');
        jresearchimport('helpers.researchareas', 'jresearch.admin');

        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();		

        $lists = array();
        $js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
        $this->state = $this->get('State');

        if($bSearch === true) {
            $filter_search = $this->state->get('com_jresearch.publications.filter_search');
            $lists['search'] = JText::_('JRESEARCH_FILTER').': <input type="text" name="filter_search" id="filter_search" value="'.htmlentities($filter_search, ENT_COMPAT | ENT_HTML401, ini_get("default_charset")).'" class="text_area" onchange="document.adminForm.submit();" />
                                                            <button onclick="document.adminForm.submit();">'.JText::_('JRESEARCH_GO').'</button> <button onclick="document.adminForm.filter_search.value=\'\';document.adminForm.submit();">'
                                                            .JText::_('JRESEARCH_RESET').'</button>';
        }

        if($bType === true) {
            // Publication type filter
            $typesHTML = array();

            $filter_pubtype =  $this->state->get('com_jresearch.publications.filter_pubtype');
            $types = JResearchPublicationsHelper::getPublicationsSubtypes();

            $typesHTML[] = JHTML::_('select.option', 'all', JText::_('JRESEARCH_PUBLICATION_TYPE'));
            foreach($types as $type)
            {
                    $typesHTML[] = JHTML::_('select.option', $type, JText::_('JRESEARCH_'.strtoupper($type)));
            }
            $lists['pubtypes'] = JHTML::_('select.genericlist', $typesHTML, 'filter_pubtype', 'class="inputbox" size="1" '.$js, 'value','text', $filter_pubtype);
        }

        if($bYear === true) {
            // Year filter
            $yearsHTML = array();


            $filter_year = $this->state->get('com_jresearch.publications.filter_year');			

            $years = JResearchPublicationsHelper::getYears();			
            $yearsHTML[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_YEAR'));
            foreach($years as $y)
            {
                $yearsHTML[] = JHTML::_('select.option', $y, $y);
            }

            $lists['years'] = JHTML::_('select.genericlist', $yearsHTML, 'filter_year', 'class="inputbox" size="1" '.$js, 'value','text', $filter_year);
        }

        if($bAuthors === true) {
            $authorsHTML = array();
            $filter_author = $this->state->get('com_jresearch.publications.filter_author');
            $authors = JResearchPublicationsHelper::getAllAuthors();

            $authorsHTML[] = JHTML::_('select.option', 0, JText::_('JRESEARCH_AUTHORS'));	
            foreach($authors as $auth)
            {
                $authorsHTML[] = JHTML::_('select.option', $auth['mid'], $auth['member_name']); 
            }
            $lists['authors'] = JHTML::_('select.genericlist', $authorsHTML, 'filter_author', 'class="inputbox" size="1" '.$js, 'value','text', $filter_author);    		
        }

        if($bAreas === true) {
            //Researchareas filter
            $areasOptions = array();

            $filter_area = $this->state->get('com_jresearch.publications.filter_area');    		
            $areas = JResearchResearchareasHelper::getResearchAreas();        
            $areasOptions[] = JHTML::_('select.option', 0 ,JText::_('JRESEARCH_RESEARCH_AREAS'));
            foreach($areas as $a)
            {
                $areasOptions[] = JHTML::_('select.option', $a->id, $a->name);
            }    		
            $lists['areas'] = JHTML::_('select.genericlist',  $areasOptions, 'filter_area', 'class="inputbox" size="1" '.$js, 'value', 'text', $filter_area );
        }

        return '<div style="float: left">'.implode('</div><div style="float: left;">', $lists).'</div>';
    }
}
?>
