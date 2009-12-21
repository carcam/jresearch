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

$mainframe->registerEvent('onBeforeEditJResearchEntity', 'plgJResearchOnBeforeEditEntity');


/**
 * Saves the records that were cited during a JResearch entity edition. Must be invoked
 *
 * @param string $entityType The type of records. JResearch entities include projects (project),
 * publications (publication), theses (thesis), staff members (member) and research areas (researcharea).
 * @param int $recordId The id of the record.
 */

function plgJResearchOnBeforeEditEntity($entityType, $recordId){
	global $mainframe;
	$db =& JFactory::getDBO();
	$session =& JFactory::getSession();

	if($recordId != null){
		$query = 'SELECT '.$db->nameQuote('citekey').' FROM '.$db->nameQuote('#__jresearch_cited_records')
				.' WHERE '.$db->nameQuote('id_record').'='.$db->Quote($recordId).' AND '.$db->nameQuote('record_type').'='.$db->Quote($entityType);
		
		$db->setQuery($query);
		$citedRecords = $db->loadResultArray();
		$session->set('citedRecords', $citedRecords, 'jresearch');			
	}
			
}

?>