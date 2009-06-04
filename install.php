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
	
	// Copy Joom!Fish content elements if Joom!Fish extension exists
	$joomFishCheckFile = JPATH_SITE.'components'.DS.'com_joomfish'.DS.'joomfish.php';
	$srcFolder = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_jresearch'.DS.'contentelements';
	$destFolder = JPATH_SITE.'administrator'.DS.'components'.DS.'com_joomfish'.DS.'contentelements';
	
	//Install Joomfish elements
	if(file_exists($joomFishCheckFile))
	{
		//Install content elements
		@rename($srcFolder.DS.'jresearch_cooperations.xml', $destFolder.DS.'jresearch_cooperations.xml');
		@rename($srcFolder.DS.'jresearch_facility.xml', $destFolder.DS.'jresearch_facility.xml');
		@rename($srcFolder.DS.'jresearch_financier.xml', $destFolder.DS.'jresearch_financier.xml');
		@rename($srcFolder.DS.'jresearch_member.xml', $destFolder.DS.'jresearch_member.xml');
		@rename($srcFolder.DS.'jresearch_project.xml', $destFolder.DS.'jresearch_project.xml');
		@rename($srcFolder.DS.'jresearch_research_area.xml', $destFolder.DS.'jresearch_research_area.xml');
		@rename($srcFolder.DS.'jresearch_team.xml', $destFolder.DS.'jresearch_team.xml');
	}
	else 
	{
		//Remove files from component installation, isn't necessary for the current joomla installation
		@unlink($srcFolder.DS.'jresearch_cooperations.xml');
		@unlink($srcFolder.DS.'jresearch_facility.xml');
		@unlink($srcFolder.DS.'jresearch_financier.xml');
		@unlink($srcFolder.DS.'jresearch_member.xml');
		@unlink($srcFolder.DS.'jresearch_project.xml');
		@unlink($srcFolder.DS.'jresearch_research_area.xml');
		@unlink($srcFolder.DS.'jresearch_team.xml');
	}

	//Remove folder from component
	@rmdir($srcFolder);
	
	// Copy TinyMCE plugin files to the right folder
	$srcFolder = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'automatic_citation';
	$destFolder = JPATH_PLUGINS.DS.'editors'.DS.'tinymce'.DS.'jscripts'.DS.'tiny_mce'.DS.'plugins'.DS.'jresearch_automatic_citation';
	
	if(file_exists($srcFolder)){
		if(!@rename($srcFolder, $destFolder)){
			JError::raiseWarning(1, JText::_('Native plugin for TinyMCE automatic citation could not be installed' ));
		}
	}else{
		JError::raiseWarning(1, JText::_('Native plugin for TinyMCE automatic citation could not be installed' ));
	}
	@rmdir($srcFolder);
	
	// Replace tinymce.php file to load the new plugin and controls
	$oldFile = JPATH_PLUGINS.DS.'editors'.DS.'tinymce.php';
	$backupFile = $oldFile.'.bak';
	
	if(file_exists($oldFile)){
		if(!@rename($oldFile, $backupFile)){
			JError::raiseWarning(1, JText::_('TinyMCE editor plugin file could not be backup' ));
		}
	}else{
		JError::raiseWarning(1, JText::_('TinyMCE editor plugin file could not be backup' ));
	}
	
	// Move the new tinymce.php
	$newTinyFile = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'tinymce.php';	
	
	if(file_exists($newTinyFile)){
		if(!@rename($newTinyFile, $oldFile)){
			JError::raiseWarning(1, JText::_('TinyMCE editor new plugin file could be not modified so JResearch Automatic Citation plugin will be not available.' ));
		}
	}else{
		JError::raiseWarning(1, JText::_('TinyMCE editor new plugin file could be not modified so JResearch Automatic Citation plugin will be not available.' ));
	}
	
	// Time to install plugins
	$db = &JFactory::getDBO();

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
			@rmdir($filePath);
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
			@rmdir($filePath);
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
			@rmdir($filePath);
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
			@rmdir($filePath);
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
			@rmdir($filePath);
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
	
	$db->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jresearch'" );
	$id = $db->loadResult();
	$db->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jresearch/assets/jresearch_logomini.png', admin_menu_link = 'option=com_jresearch' WHERE id=$id");
	$db->query();	
	

	$db->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jresearch&controller=cooperations'" );
	$id = $db->loadResult();
	$db->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jresearch/assets/cooperations_mini.png' WHERE id=$id");
	$db->query();	
	
	$db->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jresearch&controller=publications'" );
	$id = $db->loadResult();
	$db->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jresearch/assets/publications_mini.png' WHERE id=$id");
	$db->query();

	$db->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jresearch&controller=projects'" );
	$id = $db->loadResult();
	$db->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jresearch/assets/projects_mini.png' WHERE id=$id");
	$db->query();	

	$db->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jresearch&controller=staff'" );
	$id = $db->loadResult();
	$db->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jresearch/assets/staff_mini.png' WHERE id=$id");
	$db->query();	

	$db->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jresearch&controller=researchAreas'" );
	$id = $db->loadResult();
	$db->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jresearch/assets/jresearch_logomini.png' WHERE id=$id");
	$db->query();	

	$db->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jresearch&controller=theses'" );
	$id = $db->loadResult();
	$db->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jresearch/assets/theses_mini.png' WHERE id=$id");
	$db->query();	

	$db->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jresearch&controller=financiers'" );
	$id = $db->loadResult();
	$db->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jresearch/assets/financier_mini.png' WHERE id=$id");
	$db->query();	
	
	return true;
}

?>