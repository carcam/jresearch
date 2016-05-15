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

$mainframe = JFactory::getApplication();
$mainframe->registerEvent('onAfterSaveJResearchEntity', 'plgJResearchOnAfterSaveEntity');


/**
 * Saves the records that were cited during a JResearch entity edition. Must be invoked
 *
 * @param string $record 
 * @param string $recordType
 */

function plgJResearchOnAfterSaveEntity($record, $recordType){
    $mainframe = JFactory::getApplication();

    $db = JFactory::getDBO();	
    $session = JFactory::getSession() ;
    $citedRecords = $session->get('citedRecords', array(), 'jresearch');

    if(empty($record) || empty($record->id))
            return false;

    // Clear the table
    $query = 'DELETE FROM '.$db->quoteName('#__jresearch_cited_records').' WHERE '.$db->quoteName('id_record').' = '.$db->Quote($record->id)
                            .' AND '.$db->quoteName('record_type').'='.$db->Quote($recordType);
    $db->setQuery($query);
    $db->query();		

    //Save the records	
    foreach($citedRecords as $key){
        $query = 'INSERT INTO '.$db->quoteName('#__jresearch_cited_records')
                                .'('.$db->quoteName('id_record').','.$db->quoteName('record_type').','.$db->quoteName('citekey').')'
                                .' VALUES('.$record->id.','.$db->Quote($recordType).','.$db->Quote($key).')';
        $db->setQuery($query);

        if(!$db->query()){
            JError::raiseWarning(1, $db->getErrorMsg());
            return false;			
        }
    }

    $session->set('citedRecords', array(), 'jresearch');

    return true;
}

?>
