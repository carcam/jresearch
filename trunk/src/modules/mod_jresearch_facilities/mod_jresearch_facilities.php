<?php
/**
* @version		$Id: mod_feed.php 9764 2007-12-30 07:48:11Z ircmaxell $
* @package		Joomla
* @subpackage 	JResearch
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Direct Access not allowed.');

global $params;

if(!JComponentHelper::isEnabled('com_jresearch', true))
{
	JError::raiseError(0, 'J!Research is not enabled or installed');
}

// Include the helper functions only once
require_once (dirname(__FILE__).DS.'helper.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jresearch'.DS.'models'.DS.'researchareas'.DS.'researcharea.php');

$result = modJResearchFacilitiesHelper::getFacilities($params);

$facs =& $result['facs'];
$areas =& $result['areas'];

require(JModuleHelper::getLayoutPath('mod_jresearch_facilities'));
