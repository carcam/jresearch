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

/**
 * HTML Admin View class for single research area management in JResearch Component
 *
 */

class JResearchAdminViewFinancier extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
    	
      	JResearchToolbar::editFinancierAdminToolbar();
      	
        JHTML::_('jresearchhtml.validation');
        JRequest::setVar( 'hidemainmenu', 1 );
        
    	// Information about the member
    	$cid = JRequest::getVar('cid');
    	$model =& $this->getModel();
    	$fin = $model->getItem($cid[0]);
    	$arguments = array('financier');
    	
    	if($cid)
            $arguments[] = $fin;
    	else
            $arguments[] = null;
    	
    	//HTML List for published selection
    	$publishedRadio = JHTML::_('jresearchhtml.publishedlist', array('name' => 'published', 'attributes' => 'class="inputbox"', 'selected' => $fin?$fin->published:1));
        $mainframe->triggerEvent('onBeforeEditJResearchEntity', $arguments);

    	$this->assignRef('financier', $fin, JResearchFilter::OBJECT_XHTML_SAFE);
    	$this->assignRef('publishedRadio', $publishedRadio); 	
    			
       	parent::display($tpl);

       	$mainframe->triggerEvent('onAfterRenderJResearchEntityForm', $arguments);
    }
}

?>
