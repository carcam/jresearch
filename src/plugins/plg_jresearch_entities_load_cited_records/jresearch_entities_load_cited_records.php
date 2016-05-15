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
$mainframe->registerEvent('onBeforeRenderJResearchEntityForm', 'plgJResearchOnBeforeEditEntity');


/**
 * Saves the records that were cited during a JResearch entity edition. Must be invoked
 *
 * @param array $data
 * @param string $recordType
 */

function plgJResearchOnBeforeEditEntity($data, $recordType){
    $mainframe = JFactory::getApplication();
    $db = JFactory::getDBO();
    $session = JFactory::getSession();

    if(!empty($data) && !empty($data['id'])){
        $query = 'SELECT '.$db->quoteName('citekey').' FROM '.$db->quoteName('#__jresearch_cited_records')
                        .' WHERE '.$db->quoteName('id_record').'='.$db->Quote($data['id']).' AND '.$db->quoteName('record_type').'='.$db->Quote($recordType);

        $db->setQuery($query);
        $citedRecords = $db->loadResultArray();
        $session->set('citedRecords', $citedRecords, 'jresearch');			
    }			
}

?>
