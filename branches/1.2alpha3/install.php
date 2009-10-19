<?php
/**
 * @version			$Id$
 * @package			JResearch
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
 * Invoked after JResearch installation to install the files used for TinyMCE native
 * automatic citation.
 * @return boolean True if operations are executed successfully
 */
function com_install(){
	
	// Time to install plugins
	$db = &JFactory::getDBO();
	$dbVersion = array();
	$jresearchAdminFolder = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_jresearch';
	
	preg_match_all( "/(\d+)\.(\d+)\.(\d+)/i", $db->getVersion(), $dbVersion );
	$bDbVersion = ($dbVersion[1] >= 5 && $dbVersion[3] >= 1);
	
	// Copy Joom!Fish content elements if Joom!Fish extension exists
	$joomFishCheckFile = JPATH_SITE.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php';
	$srcFolder = $jresearchAdminFolder.DS.'contentelements';
	$destFolder = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_joomfish'.DS.'contentelements';
	
	$files = JFolder::files($srcFolder);
	
	//Install Joomfish elements if joomfish exists and correct mysql version exists >= 5.0.1
	if(JFile::exists($joomFishCheckFile) && $bDbVersion)
	{
		//Create views with extra sql file
		$buffer = file_get_contents($jresearchAdminFolder.DS.'joomfish_views.sql');
		$queries = $db->splitSql($buffer);
		
		foreach($queries as $query)
		{
			$db->setQuery($query);
			
			if(!$db->query())
			{
				JError::raiseWarning(1, 'J!Research: '.JText::_('SQL Error')." ".$db->stderr(true));
			}
		}
		
		//Install content elements
		foreach($files as $file)
		{
			if($file != '.' && $file != '..' && is_file($srcFolder.DS.$file))
				@rename($srcFolder.DS.$file, $destFolder.DS.$file);
		}
	}
	else 
	{
		//Remove files from component installation, isn't necessary for the current joomla installation
		foreach($files as $file)
		{
			if($file != '.' && $file != '..' && is_file($srcFolder.DS.$file))
				JFile::delete($srcFolder.DS.$file);
		}
		
		JError::raiseWarning(1, JText::_('JoomFish content elements can\'t be installed. JoomFish not installed or MySQL version < 5.0.1'));
	}

	//Remove folder from component
	JFolder::delete($srcFolder);
	
	// This has been added since 1.1.4 to ensure compatibility with Joomla! < 1.5.12
	$version = new JVersion();
	$versionText = $version->getShortVersion();
	$versioncomps = explode('.', $versionText);
	
	if(((int)$versioncomps[2]) < 12){		
		// Copy TinyMCE plugin files to the right folder		
		$srcFolder = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'legacy_automatic_citation';
		$destFolder = JPATH_PLUGINS.DS.'editors'.DS.'tinymce'.DS.'jscripts'.DS.'tiny_mce'.DS.'plugins'.DS.'jresearch_automatic_citation';
		// Move the new tinymce.php
		$newTinyFile = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'tinymce.legacy.php';			
	}else{
		// Copy TinyMCE plugin files to the right folder
		$srcFolder = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'automatic_citation';
		$destFolder = JPATH_PLUGINS.DS.'editors'.DS.'tinymce'.DS.'jscripts'.DS.'tiny_mce'.DS.'plugins'.DS.'jresearch';		
		// Move the new tinymce.php
		$newTinyFile = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'tinymce.php';					
	}
	
	if(JFolder::exists($srcFolder)){
		if(!JFolder::move($srcFolder, $destFolder)){
			JError::raiseWarning(1, JText::_('Native plugin for TinyMCE automatic citation could not be installed' ));
		}
	}else{
		JError::raiseWarning(1, JText::_('Native plugin for TinyMCE automatic citation could not be installed' ));
	}
	
	// Replace tinymce.php file to load the new plugin and controls
	$oldFile = JPATH_PLUGINS.DS.'editors'.DS.'tinymce.php';
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

	$searchPlugin = new JTablePlugin($db);	
	$searchPlugin->name = 'Search - JResearch';
	$searchPlugin->element = 'jresearch';
	$searchPlugin->folder = 'search';
	$searchPlugin->access = 0;
	$searchPlugin->ordering = 0;
	$searchPlugin->published = 1;
	$searchPlugin->iscore = 0;
	$searchPlugin->client_id = 0;
	$searchPlugin->checked_out = 0;
	
	if($searchPlugin->store()){
		$filePath = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'plg_jresearch';
		$phpFile =  $filePath.DS.'jresearch.php';
		$xmlFile =  $filePath.DS.'jresearch.xml';
		
		$newPhpFile = JPATH_PLUGINS.DS.'search'.DS.'jresearch.php';
		$newXmlFile = JPATH_PLUGINS.DS.'search'.DS.'jresearch.xml';
		@rename($xmlFile, $newXmlFile);
		if(!@rename($phpFile, $newPhpFile))
		{
			JError::raiseWarning(1, JText::_('Plugin for searching JResearch items could not be installed. Please install it manually'));
		}
		else 
		{
			//Added deletion of empty dirs
			JFolder::delete($filePath);
		}
	}else{
		JError::raiseWarning(1, JText::_('Plugin for searching JResearch items could not be installed. Please install it manually'));
	}
	

	$automaticCitationPlugin = new JTablePlugin($db);	
	$automaticCitationPlugin->name = 'JResearch Automatic Citation';
	$automaticCitationPlugin->element = 'jresearch_automatic_citation';
	$automaticCitationPlugin->folder = 'editors-xtd';
	$automaticCitationPlugin->access = 0;
	$automaticCitationPlugin->ordering = 0;
	$automaticCitationPlugin->published = 1;
	$automaticCitationPlugin->iscore = 0;
	$automaticCitationPlugin->client_id = 0;
	$automaticCitationPlugin->checked_out = 0;
	
	if($automaticCitationPlugin->store()){
		$filePath = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'plg_jresearch_automatic_citation';
		$phpFile =  $filePath.DS.'jresearch_automatic_citation.php';
		$xmlFile =  $filePath.DS.'jresearch_automatic_citation.xml';
		
		$newPhpFile = JPATH_PLUGINS.DS.'editors-xtd'.DS.'jresearch_automatic_citation.php';
		$newXmlFile = JPATH_PLUGINS.DS.'editors-xtd'.DS.'jresearch_automatic_citation.xml';
		@rename($xmlFile, $newXmlFile);
		if(!@rename($phpFile, $newPhpFile))
		{
			JError::raiseWarning(1, JText::_('Plugin for automatic citation could not be installed. Please install it manually'));
		}
		else 
		{
			//Added deletion of empty dirs
			JFolder::delete($filePath);
		}
	}else{
		JError::raiseWarning(1, JText::_('Plugin for automatic citation could not be installed. Please install it manually'));
	}
	
	$automaticBibliographyPlugin = new JTablePlugin($db);	
	$automaticBibliographyPlugin->name = 'JResearch Automatic Bibliography Generation';
	$automaticBibliographyPlugin->element = 'jresearch_automatic_bibliography_generation';
	$automaticBibliographyPlugin->folder = 'editors-xtd';
	$automaticBibliographyPlugin->access = 0;
	$automaticBibliographyPlugin->ordering = 0;
	$automaticBibliographyPlugin->published = 1;
	$automaticBibliographyPlugin->iscore = 0;
	$automaticBibliographyPlugin->client_id = 0;
	$automaticBibliographyPlugin->checked_out = 0;
	
	if($automaticBibliographyPlugin->store()){
		$filePath = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'plg_jresearch_automatic_bibliography_generation';
		$phpFile =  $filePath.DS.'jresearch_automatic_bibliography_generation.php';
		$xmlFile =  $filePath.DS.'jresearch_automatic_bibliography_generation.xml';
		
		$newPhpFile = JPATH_PLUGINS.DS.'editors-xtd'.DS.'jresearch_automatic_bibliography_generation.php';
		$newXmlFile = JPATH_PLUGINS.DS.'editors-xtd'.DS.'jresearch_automatic_bibliography_generation.xml';
		@rename($xmlFile, $newXmlFile);
		if(!@rename($phpFile, $newPhpFile))
		{
			JError::raiseWarning(1, JText::_('Plugin for automatic bibliography generation could not be installed. Please install it manually'));
		}
		else 
		{
			//Added deletion of empty dirs
			JFolder::delete($filePath);
		}
	}else{
		JError::raiseWarning(1, JText::_('Plugin for automatic bibliography generation could not be installed. Please install it manually'));
	}
	
	$persitentCitedRecordsPlugin = new JTablePlugin($db);	
	$persitentCitedRecordsPlugin->name = 'JResearch Persistent Cited Records';
	$persitentCitedRecordsPlugin->element = 'jresearch_persistent_cited_records';
	$persitentCitedRecordsPlugin->folder = 'content';
	$persitentCitedRecordsPlugin->access = 0;
	$persitentCitedRecordsPlugin->ordering = 0;
	$persitentCitedRecordsPlugin->published = 1;
	$persitentCitedRecordsPlugin->iscore = 0;
	$persitentCitedRecordsPlugin->client_id = 0;
	$persitentCitedRecordsPlugin->checked_out = 0;
	
	if($persitentCitedRecordsPlugin->store()){
		$filePath = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'plg_jresearch_persistent_cited_records';
		$phpFile =  $filePath.DS.'jresearch_persistent_cited_records.php';
		$xmlFile =  $filePath.DS.'jresearch_persistent_cited_records.xml';
		
		$newPhpFile = JPATH_PLUGINS.DS.'content'.DS.'jresearch_persistent_cited_records.php';
		$newXmlFile = JPATH_PLUGINS.DS.'content'.DS.'jresearch_persistent_cited_records.xml';
		@rename($xmlFile, $newXmlFile);
		if(!@rename($phpFile, $newPhpFile))
		{
			JError::raiseWarning(1, JText::_('Plugin for persistence of cited records for com_content could not be installed. Please install it manually'));
		}
		else 
		{
			//Added deletion of empty dirs
			JFolder::delete($filePath);
		}
	}else{
		JError::raiseWarning(1, JText::_('Plugin for persistence of cited records for com_content could not be installed. Please install it manually'));
	}
	
	$loadCitedRecordsPlugin = new JTablePlugin($db);	
	$loadCitedRecordsPlugin->name = 'JResearch Load Cited Records';
	$loadCitedRecordsPlugin->element = 'jresearch_load_cited_records';
	$loadCitedRecordsPlugin->folder = 'system';
	$loadCitedRecordsPlugin->access = 0;
	$loadCitedRecordsPlugin->ordering = 0;
	$loadCitedRecordsPlugin->published = 1;
	$loadCitedRecordsPlugin->iscore = 0;
	$loadCitedRecordsPlugin->client_id = 0;
	$loadCitedRecordsPlugin->checked_out = 0;
	
	if($loadCitedRecordsPlugin->store()){
		$filePath = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'plg_jresearch_load_cited_records';
		$phpFile =  $filePath.DS.'jresearch_load_cited_records.php';
		$xmlFile =  $filePath.DS.'jresearch_load_cited_records.xml';
		
		$newPhpFile = JPATH_PLUGINS.DS.'system'.DS.'jresearch_load_cited_records.php';
		$newXmlFile = JPATH_PLUGINS.DS.'system'.DS.'jresearch_load_cited_records.xml';
		@rename($xmlFile, $newXmlFile);
		if(!@rename($phpFile, $newPhpFile))
		{
			JError::raiseWarning(1, JText::_('Plugin for loading cited records into session for com_content could not be installed. Please install it manually'));
		}
		else 
		{
			//Added deletion of empty dirs
			JFolder::delete($filePath);
		}
	}else{
		JError::raiseWarning(1, JText::_('Plugin for loading cited records into session for com_content could not be installed. Please install it manually'));
	}
	
	//Verify we can execute bibtutils tools
	if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
		$folder = 'win32';
	}elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'MAC'){
		$folder = 'macos';
	}else{
		$folder = 'unix';	
	}
	$bibutilsPath = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'bibutils'.DS.$folder;
	$files = scandir($bibutilsPath);	

	foreach($files as $f){
		if($f !== '..' && $f !== '.' && $f != 'index.html')
			if(!chmod($bibutilsPath.DS.$f, 0755))
				JError::raiseWarning(1, JText::sprintf('Execution permissions could not be added to file %s. Please do it manually', $f));
	}
	
	return true;
}

?>