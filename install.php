<?php
/**
* @package		JResearch
* @subpackage	
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('_JEXEC') or die('Restricted access');

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
            

        $tableExtensions = $db->quoteName("#__extensions");
        $columnElement   = $db->quoteName("element");
        $columnType      = $db->quoteName("type");
        $columnEnabled   = $db->quoteName("enabled");
            
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
    * method to run after an install/update/uninstall method
    *
    * @return void
    */
    function postflight($type, $parent){
    	$db = JFactory::getDbo();
    	$rules = '{"core.admin":{"7":1},"core.manage":{"6":1},"core.publications.create":{"6":1,"3":1},"core.publications.edit":{"6":1,"4":1,"5":1},"core.publications.edit.own":{"6":1,"3":1,"5":1},"core.publications.edit.state":{"6":1,"5":1},"core.publications.delete":{"6":1},"core.projects.create":{"6":1,"3":1,"5":1},"core.projects.edit":{"6":1,"4":1},"core.projects.edit.own":{"6":1,"3":1},"core.projects.edit.state":{"6":1,"5":1},"core.projects.delete":{"6":1},"core.staff.create":{"6":1},"core.staff.edit":{"6":1,"4":1},"core.staff.edit.own":{"6":1,"3":1},"core.staff.delete":{"6":1},"core.staff.edit.state":{"6":1,"5":1},"core.researchareas.create":{"6":1,"3":1},"core.researchareas.edit":{"6":1,"4":1},"core.researchareas.edit.own":{"6":1,"3":1},"core.researchareas.delete":{"6":1},"core.researchareas.edit.state":{"6":1,"5":1}}';
    	$db->setQuery('UPDATE #__assets SET rules = '.$db->Quote($rules).' WHERE name LIKE '.$db->Quote('com_jresearch'));
    	$db->query();
    }
 
	/**
    * method to uninstall the component
    *
    * @return void
    */
    function uninstall($parent) {
    }
}