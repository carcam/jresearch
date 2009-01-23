<?php 
/**
 * @package JResearch
 * @subpackage Configuration
 * View containing J!Research Help page.
 */
defined('_JEXEC') or die('Restricted access'); 

$file = JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'conf'.DS.'tmpl'.DS.$this->langprefix.'_help.html';
// Load english help by default
if(!file_exists($file))
	include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'conf'.DS.'tmpl'.DS.'en_GB_help.html');
else
	include_once($file);	

?>

