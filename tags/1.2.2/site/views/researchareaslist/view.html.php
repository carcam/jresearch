<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for management of list of research
* areas in the frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for management of research areas list in
 * JResearch Component frontend
 *
 */
class JResearchViewResearchAreasList extends JResearchView
{
    public function display($tpl = null)
    {
        global $mainframe;
        
    	$doc = JFactory::getDocument();
    	$layout = &$this->getLayout();
    	
    	// Require css and styles
        $model =& $this->getModel();
        $areas = $model->getData(null, true, true);
        
        $doc->setTitle(JText::_('JRESEARCH_RESEARCH_AREAS'));
        
        $this->assignRef('items', $areas);
        $this->assignRef('page', $model->getPagination());

        $eArguments = array('researchareas', $layout);

        $mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);

        parent::display($tpl);

        $mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
}

?>