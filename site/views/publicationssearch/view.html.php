<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class to render the results of publication searches
 *
 */

class JResearchViewPublicationsSearch extends JResearchView
{
    public function display($tpl = null)
    {
    	global $mainframe;
    	
 		if($this->getLayout() == 'advancedsearch'){
 			$this->_displayAdvancedSearchForm($tpl);
 		}else{
 			//Enable caching in browser
 			JResponse::setHeader('Cache-control', 'max-age=900 Public');
 			$model = $this->getModel('PublicationsSearch');
 			$items = $model->getData();
 			$nitems = $model->getResultsCount();
 			//Status options
    		$orderOptions = array();    		

    		$orderOptions[] = JHTML::_('select.option', 'date_descending', JText::_('JRESEARCH_DATE_DESCENDING'));
    		$orderOptions[] = JHTML::_('select.option', 'date_ascending', JText::_('JRESEARCH_DATE_ASCENDING'));
    		$orderOptions[] = JHTML::_('select.option', 'title', JText::_('JRESEARCH_TITLE'));
    		$orderOptions[] = JHTML::_('select.option', 'author_name_ascending', JText::_('JRESEARCH_AUTHOR_NAME_ASC'));    	
    		$orderOptions[] = JHTML::_('select.option', 'author_name_descending', JText::_('JRESEARCH_AUTHOR_NAME_DESC'));
    		 
			$url = JURI::base().'index.php?option=com_jresearch&view=publicationssearch&task=search';
    		// Time to construct the URL
			$limitstart = JRequest::getInt('limitstart', 0);
			$limit = JRequest::getInt('limit', 20);
		
			$url .= '&limitstart='.$limitstart;
			$url .= '&limit='.$limit;

			$key = $mainframe->getUserStateFromRequest('publicationssearchkey', 'key');
			if(!empty($key))
				$url .= '&key='.htmlentities(urlencode($key));
		
			$key1 = $mainframe->getUserStateFromRequest('publicationssearchkey1', 'key1');
			if(!empty($key1))
				$url .= '&key1='.htmlentities(urlencode($key1));

			$key2 = $mainframe->getUserStateFromRequest('publicationssearchkey2', 'key2');
			if(!empty($key2))
				$url .= '&key2='.htmlentities(urlencode($key2));

			$key3 = $mainframe->getUserStateFromRequest('publicationssearchkey3', 'key3');
			if(!empty($key3))
				$url .= '&key3='.htmlentities(urlencode($key3));
				
			$keyfield0 = $mainframe->getUserStateFromRequest('publicationssearchkeyfield0', 'keyfield0', 'all');			
			if(!empty($key))	
				$url .= '&keyfield0='.$keyfield0;
				
			$keyfield1 = $mainframe->getUserStateFromRequest('publicationssearchkeyfield1', 'keyfield1');			
			if(!empty($key1))	
				$url .= '&keyfield1='.$keyfield1;
				
			$keyfield2 = $mainframe->getUserStateFromRequest('publicationssearchkeyfield2', 'keyfield2');			
			if(!empty($key2))	
				$url .= '&keyfield2='.$keyfield2;
				
			$keyfield3 = $mainframe->getUserStateFromRequest('publicationssearchkeyfield1', 'keyfield3');			
			if(!empty($key3))	
				$url .= '&keyfield3='.$keyfield3;
	
	
			$op1 = $mainframe->getUserStateFromRequest('publicationssearchop1', 'op1');
			if(!empty($key1))
				$url .= '&op1='.$op1;
				
			$op2 = $mainframe->getUserStateFromRequest('publicationssearchop2', 'op2', 'and');
			if(!empty($key2))
				$url .= '&op2='.$op2;
	
			$op3 = $mainframe->getUserStateFromRequest('publicationssearchop3', 'op3', 'and');
			if(!empty($key3))
				$url .= '&op3='.$op3;
				
			$with_abstract = $mainframe->getUserStateFromRequest('publicationssearchwith_abstract', 'with_abstract');
			$url .= '&with_abstract='.$with_abstract;
						
			$osteotype = $mainframe->getUserStateFromRequest('publicationssearchosteotype', 'osteotype');
			$url .= '&osteotype='.$osteotype;
	
			$language =	$mainframe->getUserStateFromRequest('publicationssearchlanguage', 'language');
			$url .= '&language='.$language;
	
			$status = $mainframe->getUserStateFromRequest('publicationssearchkeystatus', 'status', '0');
			$url .= '&status='.$status;

			$yearPattern = '/^[12]\d{3}$/';
			$monthPattern = '/^(0?[1-9]|1[0-2])$/';
			$dayPattern = '/^(0?[1-9]|[12]\d|3[01])$/';
			
			$from_year = trim($mainframe->getUserStateFromRequest('publicationssearchfrom_year', 'from_year'));
			if(!empty($from_year) && !preg_match($yearPattern, $from_year)){
				$from_year = '';			
			}		
			if(!empty($from_year)){
				$url .= '&from_year='.htmlentities($from_year);
			}																			
	
			$from_month = trim($mainframe->getUserStateFromRequest('publicationssearchfrom_month', 'from_month'));		
			if(!empty($from_month) && !preg_match($monthPattern, $from_month)){
				$from_month = '';			
			}				
			if(!empty($from_month)){
				$url .= '&from_month='.htmlentities($from_month);
			}																			
	
			$from_day = trim($mainframe->getUserStateFromRequest('publicationssearchfrom_day', 'from_day'));		
			if(!empty($from_day) && !preg_match($dayPattern, $from_day)){
				$from_day = '';			
			}				
			if(!empty($from_day)){
				$url .= '&from_day='.htmlentities($from_day);
			}																			
			
			$to_year = trim($mainframe->getUserStateFromRequest('publicationssearchto_year', 'to_year'));
			if(!empty($to_year) && !preg_match($yearPattern, $to_year)){
				$to_year = '';
			}				
			if(!empty($to_year))
				$url .= '&to_year='.htmlentities($to_year);																			
			
			$to_month = trim($mainframe->getUserStateFromRequest('publicationssearchto_month', 'to_month'));
			if(!empty($to_month) && !preg_match($monthPattern, $to_month)){
				$to_month = '';
			}				
			if(!empty($to_month))
				$url .= '&to_month='.htmlentities($to_month);																			
							
			$to_day = trim($mainframe->getUserStateFromRequest('publicationssearchto_day', 'to_day'));
			if(!empty($to_day) && !preg_match($dayPattern, $to_day)){
				$to_day = '';
			}		
			if(!empty($to_day))
				$url .= '&to_day='.htmlentities($to_day);
			
			$date_field = $mainframe->getUserStateFromRequest('publicationssearchdatefield', 'date_field', 'publication_date');
			$url .= '&date_field='.$date_field;

			$order_by1 = $mainframe->getUserStateFromRequest('publicationssearchorder_by1', 'order_by1', 'author_name_ascending');
			
			$order_by2 = $mainframe->getUserStateFromRequest('publicationssearchorder_by2', 'order_by2', 'date_descending');
			if(!empty($order_by2) && $order2 != $order_by1)
				$url .= '&order_by2='.$order_by2;																			
	
			$recommended = $mainframe->getUserStateFromRequest('publicationssearchrecommended', 'recommended');
			if(!empty($recommended))
				$url .= '&recommended='.$recommended;																			
				
			$url .= '&newSearch=0';	
    		    		
			$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
			$filter_order = JHTML::_('select.genericlist', $orderOptions, 'order_by1', 'class="inputbox" size="1" '.$js, 'value','text', $order_by1); 			 			
  			
			$this->assignRef('items', $items);
  			$this->assignRef('page', $model->getPagination());
  			$this->assignRef('nitems', $nitems);
  			$this->assignRef('filter_order', $filter_order);
  			$this->assignRef('url', $url);
			parent::display($tpl); 			
 		}
    }
    
    /**
     * Renders the search displayed for advanced publications search capabilities
     */
    private function _displayAdvancedSearchForm($tpl = null){    	
    	parent::display($tpl);
    }
    
}
?>