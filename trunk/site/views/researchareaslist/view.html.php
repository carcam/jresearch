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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for management of research areas list in
 * JResearch Component frontend
 *
 */



class JResearchViewResearchAreasList extends JView
{
    public function display($tpl = null)
    {
    	// Require css and styles
        $model =& $this->getModel();
        $areas = $model->getData(null, true, true);
        
		$this->assignRef('items', $areas);
		$this->assignRef('page', $model->getPagination());
        parent::display($tpl);
    }
}

?>
