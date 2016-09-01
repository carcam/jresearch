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
* This file relies on the library TagCloud (http://lotsofcode.github.com/tag-cloud)
* to render a HTML clickable word-cloud.
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(__DIR__ .'/lotsofcode/TagCloud/TagCloud.php');

use lotsofcode\TagCloud\TagCloud;


if(!JComponentHelper::isEnabled('com_jresearch', true))
{
	JFactory::getApplication()->enqueueMessage('J!Research is not enabled or installed', "error");
}

JFactory::getDocument()->addStyleSheet(JURI::base(true).'/modules/mod_jresearch_keywords_cloud/tagcloud.css');

$DS = DIRECTORY_SEPARATOR;

require_once(JPATH_ADMINISTRATOR.$DS.'components'.$DS.'com_jresearch'.$DS.'helpers'.$DS.'keywords.php');

$dirname = dirname(__FILE__);

$types = array();
if ($params->get('include_publications') == '1')
    $types[] = 'publications';

if ($params->get('include_projects') == '1')
    $types[] = 'projects';

$keywords = JResearchKeywordsHelper::getKeywordsByRelevance($types);
$cloud = new lotsofcode\TagCloud\TagCloud;
$baseUrl = JURI::base(true);
$cloud->setHtmlizeTagFunction(function($tag, $size) use ($baseUrl) {
	$link = '<a href="'.$baseUrl.'/'.$tag['url'].'">'.$tag['tag'].'</a>';
	return "<span class='tag size{$size} colour-{$tag['colour']}'>{$link}</span> ";
});
// Now expand the keywords based on their frequency
$corpus = array();
$maxWords = count($keywords);
foreach($keywords as $wordEntry) {
	$url = 'index.php?option=com_search&searchword='.urlencode($wordEntry['keyword']).'&ordering=newest&searchphrase=all';
	$cloud->addTag(array('tag' => $wordEntry['keyword'], 'size' => intval($wordEntry['relevance']), 'url' => $url, 'colour' => rand(1, 6)));
}

$layout = (string) $params->get('layout', 'default');

require(JModuleHelper::getLayoutPath('mod_jresearch_keywords_cloud', $layout));
