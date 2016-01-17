<?php // no direct access
/**
* @package		JResearch
* @subpackage 	Modules
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

$path_relative = JString::str_ireplace(JPATH_BASE, '', $dirname );
$path_relative = JPath::clean( $path_relative, '/');
$modpath = JURI::root(true) . $path_relative . '/';
$document = &JFactory::getDocument();

$document->addScript('http://d3js.org/d3.v3.min.js');
$document->addScript($modpath.'d3.layout.cloud.js');
$document->addScript($modpath.'cloud.js');

if ($params->get('cssfile') != '')
{
	$cssfile = $params->get('cssfile');
	$cssfile = JURI::root(true).'/'.$cssfile;
	$document->addStyleSheet( $cssfile );
}

?>

<div id="cloud"></div>
