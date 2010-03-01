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

$mainframe->registerEvent('onAfterContentSave', 'plgJResearchOnAfterContentSave');


/**
 * Triggered after an article is saved.
 */
function plgJResearchOnAfterContentSave(&$article, $isNew)
{
	global $mainframe;
	
	$db = &JFactory::getDBO();	
	$session =& JSession::getInstance();
	$citedRecords = $session->get('citedRecords', array(), 'jresearch');
	
	// Clear the table
	$query = 'DELETE FROM '.$db->nameQuote('#__jresearch_cited_records').' WHERE '.$db->nameQuote('id_record').' = '.$db->Quote($article->id)
				.' AND '.$db->nameQuote('record_type').'='.$db->Quote('content');
	$db->setQuery($query);
	$db->query();		
	
	//Save the records	
	foreach($citedRecords as $key){

		$query = 'INSERT INTO '.$db->nameQuote('#__jresearch_cited_records')
					.'('.$db->nameQuote('id_record').','.$db->nameQuote('record_type').','.$db->nameQuote('citekey').')'
					.' VALUES('.$article->id.','.$db->Quote('content').','.$db->Quote($key).')';
		$db->setQuery($query);
		
		if(!$db->query())
			JError::raiseWarning(1, $db->getErrorMsg());			
	}
	
	return true;
}

?>