<?php
/**
 * @version		$Id$
 * @package		Joomla
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

$mainframe->registerEvent('onAfterRoute', 'plgJResearchOnAfterRoute');


/**
 * Triggered after the application has been routed.
 */
function plgJResearchOnAfterRoute()
{
	global $mainframe;
	$component = JRequest::getVar('option');
	$task = JRequest::getVar('task');
	$db =& JFactory::getDBO();
	$session =& JFactory::getSession();
	
	if($component == 'com_content'){
		if($task == 'edit'){
			if($mainframe->isAdmin()){
				$cid = JRequest::getVar('cid');
				$id = $cid[0];
			}else{
				$id = JRequest::getVar('id');	
			}
			// In that case, load cited records into the session
			if($id != null){
				$query = 'SELECT '.$db->nameQuote('citekey').' FROM '.$db->nameQuote('#__jresearch_cited_records')
							.' WHERE '.$db->nameQuote('id_record').'='.$db->Quote($id).' AND '.$db->nameQuote('record_type').'='.$db->Quote('content');
				
				$db->setQuery($query);
				$citedRecords = $db->loadResultArray();
				$session->set('citedRecords', $citedRecords, 'jresearch');			
			}
		}elseif($task == 'add'){
			$session->set('citedRecords', array(), 'jresearch');
		}		
	}

}

?>