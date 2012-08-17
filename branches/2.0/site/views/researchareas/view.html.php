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
class JResearchViewResearchareas extends JResearchView
{
    public function display($tpl = null)
    {
    	$doc = JFactory::getDocument();
    	$mainframe = JFactory::getApplication('site');

        // Require css and styles
        $model = $this->getModel();
        $items = $model->getItems();
        $params = $mainframe->getParams('com_jresearch');
        $doc->setTitle($params->get('page_title'));
        
        
        $this->assignRef('items', $items);
        $this->assignRef('page', $model->getPagination());
        $this->assignRef('params', &$params);

        $eArguments = array('researchareas', $this->getLayout());
		
        $mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
		
        parent::display($tpl);
		
        $mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
}
?>