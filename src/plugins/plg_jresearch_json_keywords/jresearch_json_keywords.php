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
$mainframe->registerEvent('onBeforeExecuteJResearchTask', 'plgJResearchJSONKeywords');
jresearchimport('helpers.keywords', 'jresearch.admin');

/**
 * Saves the records that were cited during a JResearch entity edition. Must be invoked
 *
 * @param string $record 
 * @param string $recordType
 */

function plgJResearchJSONKeywords(){
	$jinput = JFactory::getApplication()->input;
	$task = $jinput->get('task', '');
	$document = JFactory::getDocument();
	if ($task == 'keywordsAndFrequency') {
		$typesStr = $jinput->get('controllers', '');
		$types = array();
		if (empty($typesStr)) {
			$types = array();
		} else {
			$types = explode(',', $typesStr);
		}
		$assocArray = JResearchKeywordsHelper::getKeywordsByRelevance($types);
		$elements = array();
		foreach ($assocArray as $word=>$frequency) {
			$elements[] = '{ "text" : "'.$word.'", "size" : '.$frequency.' }';
		}
		echo "[". implode(',', $elements). "]";
		JFactory::getApplication()->close();
	}
}
?>
