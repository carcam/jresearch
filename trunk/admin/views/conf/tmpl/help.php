<?php 
/**
 * @package JResearch
 * @subpackage Configuration
 * View containing J!Research Help page.
 */
defined('_JEXEC') or die('Restricted access'); 

$directory = JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'conf'.DS.'tmpl';

$default_file = $directory.DS.'en_GB_help.html';
$file = $directory.DS.$this->langprefix.'_help.html';

// Load english help by default
include_once(((!file_exists($file)) ? $file : $default_file));
?>

