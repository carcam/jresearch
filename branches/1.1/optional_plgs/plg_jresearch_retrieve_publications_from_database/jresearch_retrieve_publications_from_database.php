<?php
/**
 * @version		$Id$
* @package		JResearch
* @subpackage	Plugins
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent('onBeforeExecuteJResearchTask', 'plgJResearchRetrievePublicationsFromDatabase');

/**
 * Allows the retrieval of publications from public databases like PubMED by taking the 
 * pmid from request.
 * @return boolean True, if this plugin has decided to handle request, false otherwise.
 *
 */
function plgJResearchRetrievePublicationsFromDatabase(){
	
	// This event will handle the request so J!Research controller won't be invoked
	return true;

}

?>