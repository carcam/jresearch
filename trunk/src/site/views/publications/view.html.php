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
             case 'generatebibliography':
                $this->_displayGenerateBibliographyDialog();
                break;
            case 'filtered':
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
    private function _displayFrontendList($tpl){
    	$mainframe = JFactory::getApplication();
    	
    	$doc = JFactory::getDocument();
    	$params = $mainframe->getParams('com_jresearch');    	
    	$filter_pubtype = $params->get('filter_pubtype', 'all');
    	
    	if($filter_pubtype != 'all')
    	{
    		JRequest::setVar('filter_pubtype', $filter_pubtype);
    	}
    	
    	$user = JFactory::getUser();
    	$filter_show = $params->get('filter_show', 'all');
    	
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
    	$publications = $model->getItems();
    	
    	// Get certain variables
    	$filter_order = JRequest::getVar('filter_order', 'year');
    	$filter_order_Dir = JRequest::getVar('filter_order_Dir', 'DESC');
    	$style = $params->get('citationStyle', 'APA');
    	
    	//Now time to sort the data for presentation
    	$groupedItems = $this->_group($publications, $filter_order);
    	    	
    	$showmore = ($params->get('show_more', 'yes') == 'yes');
    	$showdigital = ($params->get('show_digital', 'yes') == 'yes');
    	$layout = $params->get('publications_default_sorting', 'year');
    	$exportAll = ($params->get('show_export_all', 'no') == 'yes');
    	$showAllFormat = $params->get('show_export_all_format', 'bibtex');
    	$showBibtex = ($params->get('show_export_bibtex', 'no') == 'yes');
    	$showMODS = ($params->get('show_export_mods', 'no') == 'yes');    		
    	$showRIS = ($params->get('show_export_ris', 'no') == 'yes');    	
    	
    	$this->_setFilters();    	
    	$pageHeader = $params->get('page_heading', JText::_('JRESEARCH_PUBLICATIONS'));
    	$showHeader = $params->get('show_page_heading', 1);
    	    	    	
    	// Bind variables used in layout
    	$this->assignRef('items', $groupedItems);
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
                            	$yearHeader = JText::_('JRESEARCH_YEAR').': '.$pub->year;

                            $result[$yearHeader] = array();
                        }
                       	$result[$yearHeader][] = $pub;
                       	$previousYear = $pub->year;
                    }
                	break;
                case 'type':
                        $previousType = null;
                        $header = null;
                        foreach($recordsArray as $pub){
                                if($previousType != $pub->pubtype){
                                        $header = JText::_('JRESEARCH_PUBLICATION_TYPE').': '.$pub->pubtype;
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
    	
    	// Prepare the HTML document
    	$document = JFactory::getDocument();
        $document->setTitle(JText::_('JRESEARCH_CITE_DIALOG'));
        $document->addScriptDeclaration("window.addEvent('domready',
                function(){
                        var searchRequest = new Request({method: 'get', async: true, onSuccess: addSearchResults, onFailure: onSearchFailure});
                        searchRequest.send('option=com_jresearch&controller=publications&task=searchByPrefix&format=xml&key=%%&criteria=all', null);
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

        // Put the variables into the template
        $this->assignRef('citedRecords', $citedRecordsListHTML);
        $this->assignRef('removeButton', $removeButton);
        $this->assignRef('citeButton', $citeButton);
        $this->assignRef('citeParentheticalButton', $citeParentheticalButton);
        $this->assignRef('closeButton', $closeButton);
        $this->assignRef('citeYearButton', $citeYearButton);
        $this->assignRef('noCiteButton', $noCiteButton);
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
        JHTML::_('behavior.mootools');

        foreach($citedRecords as $pub){
	        $pubTitle = $pub;
            $pubRecord = JResearchPublicationsHelper::getItemByCitekey($pub);
            if($pubRecord != null){
                $pubTitle = $pubRecord->title;            
	           	$citedRecordsOptionsHTML[] = JHTML::_('select.option', $pub, $pub.': '.$pubTitle);
            }
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
	private function _publicationsFilter($layout, $bTeams = true, $bAreas = true, $bYear = true, $bSearch = true, $bType = true, $bAuthors = true)
	{
		jresearchimport('helpers.publications', 'jresearch.admin');
		jresearchimport('helpers.teams', 'jresearch.admin');
		jresearchimport('helpers.researchareas', 'jresearch.admin');
						
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();		
		
		$lists = array();
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		
		if($bSearch === true)
        {
    		$filter_search = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_search', 'filter_search');
     		$lists['search'] = JText::_('Filter').': <input type="text" name="filter_search" id="filter_search" value="'.htmlentities($filter_search).'" class="text_area" onchange="document.adminForm.submit();" />
								<button onclick="document.adminForm.submit();">'.JText::_('Go').'</button> <button onclick="document.adminForm.filter_search.value=\'\';document.adminForm.submit();">'
								.JText::_('Reset').'</button>';
    	}
    	
		if($bType === true)
    	{
    		// Publication type filter
    		$typesHTML = array();
    		
			$filter_pubtype = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_pubtype', 'filter_pubtype');    		
			$types = JResearchPublicationsHelper::getPublicationsSubtypes();
			
			$typesHTML[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_PUBLICATION_TYPE'));
			foreach($types as $type)
			{
				$typesHTML[] = JHTML::_('select.option', $type, JText::_('JRESEARCH_'.strtoupper($type)));
			}
			$lists['pubtypes'] = JHTML::_('select.genericlist', $typesHTML, 'filter_pubtype', 'class="inputbox" size="1" '.$js, 'value','text', $filter_pubtype);
    	}
    	
		if($bYear === true)
    	{
			// Year filter
			$yearsHTML = array();

			
			$filter_year = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_year', 'filter_year');			
			
			$db->setQuery('SELECT DISTINCT year FROM '.$db->nameQuote('#__jresearch_publication').' ORDER BY '.$db->nameQuote('year').' DESC ');
			$years = $db->loadResultArray();
			
			$yearsHTML[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_YEAR'));
			foreach($years as $y)
			{
				$yearsHTML[] = JHTML::_('select.option', $y, $y);
			}
				
			$lists['years'] = JHTML::_('select.genericlist', $yearsHTML, 'filter_year', 'class="inputbox" size="1" '.$js, 'value','text', $filter_year);
    	}
    	
    	if($bAuthors === true)
    	{
			$authorsHTML = array();
    		$filter_author = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_author', 'filter_author');
			$authors = JResearchPublicationsHelper::getAllAuthors();

			$authorsHTML[] = JHTML::_('select.option', 0, JText::_('JRESEARCH_AUTHORS'));	
			foreach($authors as $auth)
			{
				$authorsHTML[] = JHTML::_('select.option', $auth['id'], $auth['name']); 
			}
			$lists['authors'] = JHTML::_('select.genericlist', $authorsHTML, 'filter_author', 'class="inputbox" size="1" '.$js, 'value','text', $filter_author);    		
    	}
		
		if($bTeams === true)
		{
			//Team filter
			$teamsOptions = array();  
	    	$filter_team = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_team', 'filter_team');    		
    		$teams = JResearchTeamsHelper::getTeams();
        	      
	        $teamsOptions[] = JHTML::_('select.option', -1 ,JText::_('JRESEARCH_ALL_TEAMS'));
	        foreach($teams as $t)
	        {
	    		$teamsOptions[] = JHTML::_('select.option', $t->id, $t->name);
	    	}    		
	    	$lists['teams'] = JHTML::_('select.genericlist',  $teamsOptions, 'filter_team', 'class="inputbox" size="1" '.$js, 'value', 'text', $filter_team );
    	}
    	
    	if($bAreas === true)
    	{
    		//Researchareas filter
    		$areasOptions = array();
    		
			$filter_area = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_area', 'filter_area');    		
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