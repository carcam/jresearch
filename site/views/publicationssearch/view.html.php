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
    		

			$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
			$first_filter = $mainframe->getUserStateFromRequest('publicationssearchorder_by1', 'order_by1', 'author_name_ascending');	    	
			$filter_order = JHTML::_('select.genericlist', $orderOptions, 'order_by1', 'class="inputbox" size="1" '.$js, 'value','text', $first_filter); 			 			
  			
			$this->assignRef('items', $items);
  			$this->assignRef('page', $model->getPagination());
  			$this->assignRef('nitems', $nitems);
  			$this->assignRef('filter_order', $filter_order);
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