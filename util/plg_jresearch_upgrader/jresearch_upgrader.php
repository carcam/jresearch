<?php
/**
 * @version		$Id$
 * @package		JResearch
 * @subpackage	Plugins
 * @copyright	Luis Galárraga
 * @license	GNU/GPL, see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent('onBeforeExecuteJResearchTask', 'plgJResearchUpgrader');

/**
 * Entry point for automatic upgrade functionality
 *
 */
function plgJResearchUpgrader(){
	$controller = JRequest::getVar('controller');
	$task = JRequest::getVar('task');
	global $mainframe;

	// Here check if we 
	if($controller != 'upgrader'){
		// Call controller of hacked component
		// We have to change relative paths there
		// And a line to execute SQL Script
	}
	
}

?>
