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
        
        //Add template path explicitly (useful when requesting from the backend)
        $this->addTemplatePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'views'.DS.'publicationslist'.DS.'tmpl');  
        JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');

        $layout = $this->getLayout();

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
		
		$mainframe->triggerEvent('onBeforeListJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListJResearchEntities', $eArguments);
    }
    
	/**
     * Invoked when the user has selected the option to show lists of publications
     * filtered by team groups and showing the journal acceptance rate per each publication
     * and calculating the average per group.
     * 
     * @todo Set marked publications
     */
    private function _displayTabularList(){
    	global $mainframe;    	
    	
    	$session =& JFactory::getSession();
    	$doc = JFactory::getDocument();
    	$lang = JFactory::getLanguage();
    	$lang->load('com_jresearch.teams');
    	$doc->addStyleDeclaration(".title{text-align:center;}");
    	$doc->addScript(JURI::base().'components/com_jresearch/js/mootools-for-dropdown.js');
    	$doc->addScript(JURI::base().'components/com_jresearch/js/UvumiDropdown-compressed.js');
    	$doc->addStylesheet(JURI::base().'components/com_jresearch/css/uvumi-dropdown.css');    	
    	$doc->addStylesheet(JURI::base().'components/com_jresearch/css/uvumi-dropdown-vertical.css');
    	$doc->addScriptDeclaration("
			var myMenu = new UvumiDropdown('dropdown-menu', {clickToOpen: true, transition: Fx.Transitions.Quint.easeIn});
    	");
    	
    	$teamId = JRequest::getVar('teamid');
    	$model = $this->getModel();
    	$teamsModel = $this->getModel('teams');
    	$areasModel = $this->getModel('researchareaslist');
    	$items = array();
    	$lists = array();
    	$markedItems = array();    	

    	$items = $model->getData(null, true, true);
    	$page = $model->getPagination();
    	
    	//Set marked items for export
    	$markedItems = $model->getData(null, true);
    	
    	$ids = array();
    	foreach($markedItems as $item)
    	{
    		array_push($ids, $item->id);
    	}
    	$session->set('markedRecords', $ids, 'jresearch');
    	
    	$params = $mainframe->getParams('com_jresearch'); 
    	$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		$field = $params->get('field_for_average');    	
    	
    	if($params->get('filter_teams') == 'yes'){
	    	$filter_team = $mainframe->getUserStateFromRequest('tabularpublicationsfilter_team', 'filter_team');    		
    		$teams = $teamsModel->getData();
	        $Itemid = JRequest::getInt('Itemid', -1);	        	    		
        	$teamsOptions = array();        
	        $teamsOptions[] = JHTML::_('select.option', -1 ,JText::_('JRESEARCH_ALL_TEAMS'));
	        $lists['teams'] = '<ul id="dropdown-menu" class="dropdown">';
	        $href = 'index.php?option=com_jresearch&amp;view=publicationslist&amp;layout=filtered&amp;task=filtered&amp;modelkey=tabular&amp;limitstart=0&amp;filter_team=-1'.($Itemid != -1? '&amp;Itemid='.$Itemid:'');	        
	        $lists['teams'] .= '<li><a href="#">'.JText::_('JRESEARCH_TEAMS').'</a>'; 	        
	        $lists['teams'] .= '<ul>';
	        $lists['teams'] .= '<li><a href="'.$href.'">'.JText::_('JRESEARCH_ALL_TEAMS').'</a>';	        
	        foreach($teams as $t){
	        	$href = 'index.php?option=com_jresearch&amp;view=publicationslist&amp;layout=filtered&amp;task=filtered&amp;modelkey=tabular&amp;limitstart=0&amp;filter_team='.$t->id.($Itemid != -1? '&amp;Itemid='.$Itemid:'');
	    		$lists['teams'] .= '<li><a title="'.$t->name.'" href="'.$href.'">'.$t->name.'</a>';
	    	}    		
	        $lists['teams'] .= '</ul></li>';
	    	$lists['teams'] .= '</ul>';
    	}
    	
    	if($params->get('show_average') == 'yes'){
    		$average = $model->getAverage();
    		$this->assignRef('average', $average);
    	}
    	
    	if($params->get('filter_areas') == 'yes'){
			$filter_area = $mainframe->getUserStateFromRequest('tabularpublicationsfilter_area', 'filter_area');    		
    		$areas = $areasModel->getData();
        	$areasOptions = array();        
	        $areasOptions[] = JHTML::_('select.option', 0 ,JText::_('JRESEARCH_RESEARCH_AREAS'));
	        foreach($areas as $a){
	    		$areasOptions[] = JHTML::_('select.option', $a->id, $a->name);
	    	}    		
    		$filter_area = $filter_area = $mainframe->getUserStateFromRequest('tabularpublicationsfilter_area', 'filter_area');    	
	    	$lists['areas'] = JHTML::_('select.genericlist',  $areasOptions, 'filter_area', 'class="inputbox" size="1" '.$js, 'value', 'text', $filter_area );
    	}
    	
    	if($params->get('filter_year') == 'yes'){
			// Year filter
			$filter_year = $mainframe->getUserStateFromRequest('tabularpublicationsfilter_year', 'filter_year');			
			$db = &JFactory::getDBO();
			$db->setQuery('SELECT DISTINCT year FROM '.$db->nameQuote('#__jresearch_publication').' ORDER BY '.$db->nameQuote('year').' DESC ');
			$years = $db->loadResultArray();
			$yearsHTML = array();
			$yearsHTML[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_YEAR'));
			foreach($years as $y)
				$yearsHTML[] = JHTML::_('select.option', $y, $y);
				
			$lists['years'] = JHTML::_('select.genericlist', $yearsHTML, 'filter_year', 'class="inputbox" size="1" '.$js, 'value','text', $filter_year);
    		
    	}
    	
    	if($params->get('filter_type') == 'yes'){
    		// Publication type filter
			$filter_pubtype = $mainframe->getUserStateFromRequest('tabularpublicationsfilter_pubtype', 'filter_pubtype');    		
			$types = JResearchPublication::getPublicationsSubtypes();
			$typesHTML = array();
			$typesHTML[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_PUBLICATION_TYPE'));
			foreach($types as $type){
				$typesHTML[] = JHTML::_('select.option', $type, JText::_('JRESEARCH_'.strtoupper($type)));
			}
			$lists['pubtypes'] = JHTML::_('select.genericlist', $typesHTML, 'filter_pubtype', 'class="inputbox" size="1" '.$js, 'value','text', $filter_pubtype);
    	}
    	
        if($params->get('filter_search') == 'yes'){
    		$filter_search = $mainframe->getUserStateFromRequest('tabularpublicationsfilter_search', 'filter_search');
     		$lists['search'] = JText::_('Filter').': <input type="text" name="filter_search" id="filter_search" value="'.$filter_search.'" class="text_area" onchange="document.adminForm.submit();" />
								<button onclick="document.adminForm.submit();">'.JText::_('Go').'</button> <button onclick="document.adminForm.filter_search.value=\'\';document.adminForm.submit();">'
								.JText::_('Reset').'</button>';
    	}
    	
    	if($params->get('filter_authors') == 'yes'){
			$filter_author = $mainframe->getUserStateFromRequest('tabularpublicationsfilter_author', 'filter_author');
			$authors = $model->getAllAuthors();
			$authorsHTML = array();
			$authorsHTML[] = JHTML::_('select.option', 0, JText::_('JRESEARCH_AUTHORS'));	
			foreach($authors as $auth){
				$authorsHTML[] = JHTML::_('select.option', $auth['id'], $auth['name']); 
			}
			$lists['authors'] = JHTML::_('select.genericlist', $authorsHTML, 'filter_author', 'class="inputbox" size="1" '.$js, 'value','text', $filter_author);    		
    	}
    	
    	$format = $params->get('staff_format') == 'last_first'?1:0;
    	
    	$doc->setTitle(JText::_('JRESEARCH_PUBLICATIONS'));
    
    	$this->assignRef('items', $items);
    	$this->assignRef('page', $page);
    	$this->assignRef('lists', $lists);
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
    	$filter_pubtype = $params->get('filter_pubtype','0');
    	
    	if($filter_pubtype != '')
    	{
    		JRequest::setVar('filter_pubtype', $filter_pubtype);
    	}
    	
    	$user = JFactory::getUser();
    	$filter_show = $params->get('filter_show');
    	
    	//My publications
    	$id_member = null;
    	
    	if($filter_show == "my")
    	{
    		//Filter only my publications
	    	$db = JFactory::getDBO();
	    	
    		$member = new JResearchMember($db);
    		$member->bindFromUsername($user->username);
    		$id_member = $member->id;
    	}
    	
    	if($id_member == null)
    			$id_member = -1;
	    	
	    JRequest::setVar('filter_author', $id_member);
    	
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
    	    	
    	$showmore = ($params->get('show_more') == 'yes');
    	$showdigital = ($params->get('show_digital') == 'yes');
    	$doc->setTitle(JText::_('JRESEARCH_PUBLICATIONS'));
    	    	
    	// Bind variables used in layout
    	$this->assignRef('items', $sortedItems);
    	$this->assignRef('page', $model->getPagination());
    	$this->assignRef('user', $user);
    	$this->assignRef('showmore', $showmore);
    	$this->assignRef('showdigital', $showdigital);
    	$this->assignRef('style', $style);    	
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
				if($result[$yearHeader])
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
