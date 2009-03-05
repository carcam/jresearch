<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Financiers
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for publication of financiers information.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML Admin View class for single research area management in JResearch Component
 *
 */

class JResearchAdminViewFinancier extends JView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	
      	JResearchToolbar::editFinancierAdminToolbar();
      	
		JHTML::_('JResearch.validation');
        JRequest::setVar( 'hidemainmenu', 1 );
        
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$arguments = array('financier');
    	
    	if($cid){
        	$fin = $model->getItem($cid[0]);
        	$arguments[] = $fin->id;
    	}else{
    		$arguments[] = null; 		
    	}
    	
    	//HTML List for published selection
    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $fin?$fin->published:1));
    	
    	$this->assignRef('financier', $fin);
    	$this->assignRef('publishedRadio', $publishedRadio); 	
    	
		// Load cited records
		$mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);
		
       	parent::display($tpl);

    }
}

?>
