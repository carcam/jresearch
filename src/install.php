<?php
/**
* @package		JResearch
* @subpackage
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Installation scriot.
*/

defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
/**
 * Script file of HelloWorld component
 */
class com_jresearchInstallerScript
{
    private static $old_version = null;

    /**
    * method to run before an install/update/uninstall method
    *
    * @return void
    */
    function preflight($type, $parent) {
		// This is to prevent updates from 3.0 Beta 1 to fail
    	$jresearchPath = JPATH_ROOT.DIRECTORY_SEPARATOR.'components'
                .DIRECTORY_SEPARATOR.'com_jresearch';
        $simpleStylePath = $jresearchPath.DIRECTORY_SEPARATOR.'citationStyles'
                .DIRECTORY_SEPARATOR.'simple';
        if (JFolder::exists($jresearchPath) && !JFolder::exists($simpleStylePath)) {
            JFolder::create($simpleStylePath, fileperms($jresearchPath));
        }

        $manifest = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'. DIRECTORY_SEPARATOR.'com_jresearch'.DIRECTORY_SEPARATOR.'jresearch.xml';
        if (JFile::exists($manifest)) {
            $xml = simplexml_load_file(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'. DIRECTORY_SEPARATOR.'com_jresearch'.DIRECTORY_SEPARATOR.'jresearch.xml');
            com_jresearchInstallerScript::$old_version = (string)$xml->version;
        }
    }

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {

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

        if (com_jresearchInstallerScript::$old_version == null)
            return;        

        if (version_compare(com_jresearchInstallerScript::$old_version, '3.0 ') == 0) {           
            try {
                $db->setQuery("ALTER TABLE #__jresearch_publication ADD FULLTEXT INDEX #__jresearch_publication_title_keywords_index(title, keywords)");
                $db->execute();
            } catch (Exception $e) {
				echo $e;
            }
            
            try {
                $db->setQuery("DROP TABLE IF EXISTS #__jresearch_publication_research_area");
                $db->execute();
            } catch (Exception $e) {
				echo $e;                
            }
            
            try {
                $db->setQuery("RENAME TABLE #__jresearch_publication_researcharea TO  #__jresearch_publication_research_area");
                $db->execute();
            } catch (Exception $e) {
				echo $e;                
            }
            return;
        } else if (version_compare(com_jresearchInstallerScript::$old_version, '3.0 ') > 0) {
            return;
        }
        
        // Then we are upgrading from J!Research 2.x
        
        try {
            $db->setQuery("DROP TABLE IF EXISTS #__jresearch_project_research_area");
            $db->execute();
        } catch (Exception $e) {

        }

        try {
            $db->setQuery("RENAME TABLE #__jresearch_project_researcharea TO  #__jresearch_project_research_area");
            $db->execute();
        } catch (Exception $e) {

        }
        
        try {
            $db->setQuery("DROP TABLE IF EXISTS #__jresearch_thesis_research_area");
            $db->execute();
        } catch (Exception $e) {

        }

        try {
            $db->setQuery("RENAME TABLE #__jresearch_thesis_researcharea TO  #__jresearch_thesis_research_area");
            $db->execute();
        } catch (Exception $e) {

        }
        
        try {
            $db->setQuery("DROP TABLE IF EXISTS #__jresearch_member_research_area");
            $db->execute();
        } catch (Exception $e) {

        }

        try {
            $db->setQuery("RENAME TABLE #__jresearch_member_researcharea TO  #__jresearch_member_research_area");
            $db->execute();
        } catch (Exception $e) {

        }        
        

        try {
            $db->setQuery("ALTER TABLE #__jresearch_publication` DROP INDEX `#__jresearch_publication_full_index");
            $db->execute();
        } catch (Exception $e) {

        }

        try {
            $db->setQuery("ALTER TABLE #__jresearch_publication ADD FULLTEXT INDEX #__jresearch_publication_full_index(title, abstract)");
            $db->execute();
        } catch (Exception $e) {

        }

        try {
            $db->setQuery("ALTER TABLE #__jresearch_publication_keyword ADD FULLTEXT INDEX #__jresearch_publication_keyword_keyword(keyword)");
            $db->execute();
        } catch (Exception $e) {

        }

        try {
            $db->setQuery("ALTER TABLE #__jresearch_member ADD COLUMN link_to_member tinyint(1) NOT NULL DEFAULT '1'");
            $db->execute();
        } catch (Exception $e) {

        }

        try {
            $db->setQuery("ALTER TABLE #__jresearch_member ADD COLUMN link_to_website tinyint(1) NOT NULL DEFAULT '0'");
            $db->execute();
        } catch (Exception $e) {

        }

        try {
            $db->setQuery("ALTER TABLE #__jresearch_member ADD COLUMN google_scholar varchar(256) default NULL");
            $db->execute();
        } catch (Exception $e) {

        }
    }

   /**
    * method to uninstall the component
    *
    * @return void
    */
    function uninstall($parent) {
    }
}
