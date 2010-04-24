<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage           Cooperations
* @copyright            Copyright (C) 2008 Luis Galárraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of cooperations in the backend interface.
*/

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'journal.php');

/**
* JResearch Journal Backend Controller
*
* @package	JResearch
* @subpackage	Cooperations
*/
class JResearchJournalsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
            parent::__construct();
            $this->registerTask('getImpactFactor', 'getImpactFactor');
	}

        function getImpactFactor(){
            $doc = JFactory::getDocument();
            $db = JFactory::getDBO();
            
            $doc->setMimeEncoding('text/plain');
            $journalId = JRequest::getInt('journalId');
            $db->setQuery('SELECT impact_factor FROM #__jresearch_journals WHERE id = '.$db->Quote($journalId));
            echo $db->loadResult();
        }
}
?>
