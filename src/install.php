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
            
    }
 
	/**
    * method to uninstall the component
    *
    * @return void
    */
    function uninstall($parent) {
    	// $parent is the class calling this method
        echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
    }
 
    /**
    * method to update the component
    *
    * @return void
    */
    function update($parent) 
    {
    	// $parent is the class calling this method
        echo '<p>' . JText::_('COM_HELLOWORLD_UPDATE_TEXT') . '</p>';
    }
 
    /**
    * method to run before an install/update/uninstall method
    *
    * @return void
    */
    function preflight($type, $parent) 
    {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
    	echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }
 
    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) 
    {
    	// $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';

    }
}