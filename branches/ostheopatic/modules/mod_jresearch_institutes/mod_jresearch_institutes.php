<?php
/**
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
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
global $params;

if(!JComponentHelper::isEnabled('com_jresearch', true))
{
	JError::raiseError(0, JText::_('JRESEARCH_NOT_INSTALLED_OR_ENABLED'));
}

$dirname = dirname(__FILE__);
$limit = $params->get('limit', 5);

// Include the helper functions only once
require_once (trim($dirname).DS.'helper.php');

$institutes = modJResearchInstitutesHelper::getHitparade($limit);

$layout = (string) $params->get('layout', 'default');

require(JModuleHelper::getLayoutPath('mod_jresearch_institutes'));