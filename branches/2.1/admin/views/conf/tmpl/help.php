<?php 
/**
 * @package JResearch
 * @subpackage Configuration
 * @license		GNU/GPL
 * View containing J!Research Help page.
 */
defined('_JEXEC') or die('Restricted access'); 
?>

<form id="adminForm" action="index.php" name="adminForm" method="post">
<?php
$directory = JPATH_ADMINISTRATOR.DS.'language';

$default_file = $directory.DS.'en-GB'.DS.'en-GB-jresearch_help.html';
$file = $directory.DS.$this->langtag.DS.$this->langtag.'-jresearch_help.html';



include_once(((file_exists($file)) ? $file : $default_file));
?>
<input type="hidden" name="option" value="com_jresearch"/>
<input type="hidden" name="task" value=""/>
</form>
