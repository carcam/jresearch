<?php
/**
 * @version			$Id$
 * @package			Joomla
 * @subpackage		JResearch	
 * @copyright		Copyright (C) 2008 Luis Galarraga.
 * @license			GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table'.DS.'plugin.php');

/**
 * Invoked during JResearch uninstallation
 * @return boolean True if operations are executed successfully
 */
function com_uninstall(){
	// Uninstall TinyMCE native automatic citation plugin
	$automaticCitationFolder = 	JPATH_PLUGINS.DS.'editors'.DS.'tinymce'.DS.'jscripts'.DS.'tiny_mce'.DS.'plugins'.DS.'jresearch_automatic_citation';
	
	if(!deleteDirectory($automaticCitationFolder)){
		echo "Warning: Directory $automaticCitationFolder could not be deleted. Please do it manually.";
	}
	
	$oldFile = JPATH_PLUGINS.DS.'editors'.DS.'tinymce.php';
	$backupFile = $oldFile.'.bak';
		
	if(file_exists($backupFile)){
		if(file_exists($oldFile)){		
			if(!@unlink($oldFile) || !@rename($backupFile, $oldFile))
				echo "Error: File $oldFile could not be restored to ommit automatic citation plugin. Please do it manually.";				
		}else{
			echo "Error: File $oldFile does not exist.";
		}
	}else{
		echo "Error: File $backupFile does not exist. This file is used to restore TinyMCE plugin file to ommit automatic citation plugin. Please solve the problem manually.";
	}
	
	$sh404sefPluginLanguageFolder=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sh404sef'.DS.'language'.DS.'plugins';
	if(file_exists($sh404sefPluginLanguageFolder)){
	$sh404sefPluginLanguageFile=$sh404sefPluginLanguageFolder.DS.'com_jresearch.php';
		if(!@unlink($sh404sefPluginLanguageFile)){
			echo "Warning: sh404SEF plugin language file could not be deleted. Please do it manually.";
		}
	}
	
	// Time to uninstall plugins
	$db = &JFactory::getDBO();
	
	$element = $db->nameQuote('element');
	$tableName = $db->nameQuote('#__plugins');
	$elementValue = $db->Quote('jresearch');
	
	$query = "DELETE FROM $tableName WHERE $element = $elementValue";
	$db->setQuery($query);
	if($db->query()){
		$phpFile = JPATH_PLUGINS.DS.'search'.DS.'jresearch.php';
		if(!@unlink($phpFile))
			JError::raiseWarning(1, JText::_("File $phpFile could not be removed. Please do it manually."));
				
		$xmlFile = JPATH_PLUGINS.DS.'search'.DS.'jresearch.xml';
		if(!@unlink($xmlFile))
			JError::raiseWarning(1, JText::_("File $xmlFile could not be removed. Please do it manually."));
	}else{
		JError::raiseWarning(1, JText::_('Plugin for searching JResearch items could not be uninstalled. Please remove it manually'));
	}
	
	$elementValue = $db->Quote('jresearch_automatic_citation');
	$query = "DELETE FROM $tableName WHERE $element = $elementValue";
	$db->setQuery($query);
	if($db->query()){
		$phpFile = JPATH_PLUGINS.DS.'editors-xtd'.DS.'jresearch_automatic_citation.php';
		if(!@unlink($phpFile))
			JError::raiseWarning(1, JText::_("File $phpFile could not be removed. Please do it manually."));
				
		$xmlFile = JPATH_PLUGINS.DS.'editors-xtd'.DS.'jresearch_automatic_citation.xml';
		if(!@unlink($xmlFile))
			JError::raiseWarning(1, JText::_("File $xmlFile could not be removed. Please do it manually."));
	}else{
		JError::raiseWarning(1, JText::_('Plugin for automatic citation could not be uninstalled. Please remove it manually'));
	}
	
	$elementValue = $db->Quote('jresearch_automatic_bibliography_generation');
	$query = "DELETE FROM $tableName WHERE $element = $elementValue";
	$db->setQuery($query);
	if($db->query()){
		$phpFile = JPATH_PLUGINS.DS.'editors-xtd'.DS.'jresearch_automatic_bibliography_generation.php';
		if(!@unlink($phpFile))
			JError::raiseWarning(1, JText::_("File $phpFile could not be removed. Please do it manually."));
				
		$xmlFile = JPATH_PLUGINS.DS.'editors-xtd'.DS.'jresearch_automatic_bibliography_generation.xml';
		if(!@unlink($xmlFile))
			JError::raiseWarning(1, JText::_("File $xmlFile could not be removed. Please do it manually."));
	}else{
		JError::raiseWarning(1, JText::_('Plugin for automatic bibliography generation could not be uninstalled. Please remove it manually'));
	}
	
	
	$elementValue = $db->Quote('jresearch_persistent_cited_records');
	$query = "DELETE FROM $tableName WHERE $element = $elementValue";
	$db->setQuery($query);
	if($db->query()){
		$phpFile = JPATH_PLUGINS.DS.'content'.DS.'jresearch_persistent_cited_records.php';
		if(!@unlink($phpFile))
			JError::raiseWarning(1, JText::_("File $phpFile could not be removed. Please do it manually."));
				
		$xmlFile = JPATH_PLUGINS.DS.'content'.DS.'jresearch_persistent_cited_records.xml';
		if(!@unlink($xmlFile))
			JError::raiseWarning(1, JText::_("File $xmlFile could not be removed. Please do it manually."));
	}else{
		JError::raiseWarning(1, JText::_('Plugin for persistent cited records could not be uninstalled. Please remove it manually'));
	}
	
	$elementValue = $db->Quote('jresearch_load_cited_records');
	$query = "DELETE FROM $tableName WHERE $element = $elementValue";
	$db->setQuery($query);
	if($db->query()){
		$phpFile = JPATH_PLUGINS.DS.'system'.DS.'jresearch_load_cited_records.php';
		if(!@unlink($phpFile))
			JError::raiseWarning(1, JText::_("File $phpFile could not be removed. Please do it manually."));
				
		$xmlFile = JPATH_PLUGINS.DS.'system'.DS.'jresearch_load_cited_records.xml';
		if(!@unlink($xmlFile))
			JError::raiseWarning(1, JText::_("File $xmlFile could not be removed. Please do it manually."));
	}else{
		JError::raiseWarning(1, JText::_('Plugin for loading cited records into session could not be uninstalled. Please remove it manually'));
	}
			
	return true;
}

/**
 * Tries to delete a non-empty directory. The function deletes files or subfolders when possible. 
 * If any of the items in the directory hierachy cannot be deleted or the directory does not exist,
 * the method returns false.
 *
 * @param string $directory Name of a directory.
 * @return boolean True if the operation is completely successful.
 */
function deleteDirectory($directory){
	if(!file_exists($directory))
		return false;

	$contents = scandir($directory);

	foreach($contents as $entry){
		if($entry != "." && $entry != ".."){
			if(is_dir($directory.DS.$entry)){
				deleteDirectory($directory.DS.$entry);
			}else{
				@unlink($directory.DS.$entry);
			}
		}
	}
	
	return rmdir($directory);
}

?>