<?php
/**
* @version		$Id: mod_quickcoops.php 9764 2007-12-30 07:48:11Z ircmaxell $
* @package		Joomla
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

//global $params;

if(!JComponentHelper::isEnabled('com_jresearch', true))
{
	JError::raiseError(0, 'J!Research is not enabled or installed');
}
$DS = DIRECTORY_SEPARATOR;
require_once(JPATH_ADMINISTRATOR.$DS.'components'.$DS.'com_jresearch'.$DS.'helpers'.$DS.'keywords.php');

$dirname = dirname(__FILE__);

$types = array();
if ($params->get('include_publications') == '1')
	$types[] = 'publications';

if ($params->get('include_projects') == '1')
	$types[] = 'projects';

$keywords = JResearchKeywordsHelper::getKeywordsByRelevance($types);
$layout = (string) $params->get('layout', 'default');

require(JModuleHelper::getLayoutPath('mod_jresearch_keywords_cloud', $layout));
