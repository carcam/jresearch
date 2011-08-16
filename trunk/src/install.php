<?php
/**
* @package		JResearch
* @subpackage	
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

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

/**
 * Script file of HelloWorld component
 */
class com_jresearchInstallerScript
{
    /**
     * method to install the component
     *
     * @return void
     */
	function install($parent) 
    {
     	$manifest = $parent->get("manifest");
        $parent = $parent->getParent();
        $source = $parent->getPath("source");
        $installedPlugins = array();
        $db = JFactory::getDbo();        
             
        $installer = new JInstaller();
            
        // Install plugins
        foreach($manifest->plugins->plugin as $plugin) {
        	$attributes = $plugin->attributes();
            $plg = $source . DS . $attributes['folder'];
            $installer->install($plg);
            $installedPlugins[] = $db->Quote($attributes['plugin']);
        }
            
        // Install modules
        foreach($manifest->modules->module as $module) {
        	$attributes = $module->attributes();
            $mod = $source . DS . $attributes['folder'].DS.$attributes['module'];
            $installer->install($mod);
        }
            

        $tableExtensions = $db->nameQuote("#__extensions");
        $columnElement   = $db->nameQuote("element");
        $columnType      = $db->nameQuote("type");
        $columnEnabled   = $db->nameQuote("enabled");
            
        // Enable plugins
        $installedPluginsText = implode(',', $installedPlugins);
        $db->setQuery(
                "UPDATE 
                    $tableExtensions
                SET
                    $columnEnabled=1
                WHERE
                    $columnElement IN ($installedPluginsText)
                AND
                    $columnType='plugin'"
         );
            
         $db->query();
         
        // Replace tinymce.php file to load the new plugin and controls
		$srcFolder = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'automatic_citation';
		$destFolder = JPATH_SITE.DS.'media'.DS.'editors'.DS.'tinymce'.DS.'jscripts'.DS.'tiny_mce'.DS.'plugins'.DS.'jresearch';		         
		$oldFile = JPATH_PLUGINS.DS.'editors'.DS.'tinymce'.DS.'tinymce.php';
		$backupFile = $oldFile.'.bak';
	
		if(JFile::exists($oldFile)){
			if(!@rename($oldFile, $backupFile)){
				JError::raiseWarning(1, JText::_('TinyMCE editor plugin file could not be backup' ));
			}
		}else{
			JError::raiseWarning(1, JText::_('TinyMCE editor plugin file could not be backup' ));
		}
	
		// Move the new tinymce.php
		$newTinyFile = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'tinymce.php';	
	
		if(JFile::exists($newTinyFile)){
			if(!@rename($newTinyFile, $oldFile)){
				JError::raiseWarning(1, JText::_('TinyMCE editor new plugin file could be not modified so JResearch Automatic Citation plugin will be not available.' ));
			}
		}else{
			JError::raiseWarning(1, JText::_('TinyMCE editor new plugin file could be not modified so JResearch Automatic Citation plugin will be not available.' ));
		}
		
		if(JFolder::exists($srcFolder)){
			if(!JFolder::move($srcFolder, $destFolder)){
				JError::raiseWarning(1, JText::_('Native plugin for TinyMCE automatic citation could not be installed' ));
			}
		}else{
			JError::raiseWarning(1, JText::_('Native plugin for TinyMCE automatic citation could not be installed' ));
		}
    }
 
	/**
    * method to uninstall the component
    *
    * @return void
    */
    function uninstall($parent) {
		// Uninstall TinyMCE native automatic citation plugin
		// This has been added since 1.1.4 to ensure compatibility with Joomla! < 1.5.12

		$automaticCitationFolder = 	JPATH_SITE.DS.'media'.DS.'editors'.DS.'tinymce'.DS.'jscripts'.DS.'tiny_mce'.DS.'plugins'.DS.'jresearch';
			
		if(!deleteDirectory($automaticCitationFolder)){
			echo "Warning: Directory $automaticCitationFolder could not be deleted. Please do it manually.";
		}
		
		$oldFile = JPATH_PLUGINS.DS.'editors'.DS.'tinymce'.DS.'tinymce.php';
		$backupFile = $oldFile.'.bak';		
			
		if(JFile::exists($backupFile)){
			if(JFile::exists($oldFile)){		
				if(!@unlink($oldFile) || !@rename($backupFile, $oldFile))
					echo "Error: File $oldFile could not be restored to ommit automatic citation plugin. Please do it manually.";				
			}else{
				echo "Error: File $oldFile does not exist.";
			}
		}else{
			echo "Error: File $backupFile does not exist. This file is used to restore TinyMCE plugin file to ommit automatic citation plugin. Please solve the problem manually.";
		}
    }
    
}