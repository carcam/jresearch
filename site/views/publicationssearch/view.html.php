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
 			$model = $this->getModel('PublicationsSearch');
 			$items = $model->getData();
  			$this->assignRef('items', $items);
  			$this->assignRef('page', $model->getPagination());
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