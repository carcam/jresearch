<?php
/**
 * Joomla! Upgrade Helper
 */
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.filesystem.file');
juimport('pasamio.pfactory');

$session =& JFactory::getSession();
$file = $session->get('jresearchupgrader_filename');

if(!$file) { // jump again
    JError::raiseWarning(1, var_export($file, true));
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_jresearch&mode=upgrader&task=step1'); // back to step one if invalid session
}

$plugin = JPluginHelper::getPlugin('jresearch', 'jresearch_upgrader');
$params = new JParameter($plugin->params);
$extractor = $params->get('extractor', 0);

define('JUPDATEMAN_EXTRACTOR_16', 		0);
define('JUPDATEMAN_EXTRACTOR_15', 		1);
define('JUPDATEMAN_EXTRACTOR_PEAR', 	2);


@set_time_limit(0); // try to set this just in case - doesn't hurt either

$config =& JFactory::getConfig();
$tmp_path = $config->getValue('config.tmp_path');
$filename = $tmp_path .DS. $file;

switch($extractor)
{
	case JUPDATEMAN_EXTRACTOR_16:
		juimport('joomla.filesystem.archive');
		if(!JArchive::extract($filename, JPATH_SITE)) {
			HTML_jupgrader::showError('Failed to extract archive!');
			return false;
		}
		break;

	case JUPDATEMAN_EXTRACTOR_15:
		jimport('joomla.filesystem.archive');
		if(!JArchive::extract($filename, JPATH_SITE)) {
			HTML_jupgrader::showError('Failed to extract archive!');
			return false;
		}
		break;
		
	case JUPDATEMAN_EXTRACTOR_PEAR:
		jimport('pear.archive_tar.Archive_Tar');
		$extractor = new Archive_Tar($filename);
		if(!$extractor->extract(JPATH_SITE)) {
			HTML_jupgrader::showError('Failed to extract archive!');
			return false;
		}		
		break;
}

//Now time to execute the update scripts
$installation = JPATH_SITE .DS.'installation';

$upgradeRoutine = $installation.DS.'upgrade.sql';
$fh = @fopen($upgradeRoutine, 'r');
if($fh !== false){
    $sqlsentences = fread($fh, filesize($upgradeRoutine));
    $db = JFactory::getDBO();
    $db->setQuery($sqlsentences);
    if(!$db->query()){
        JError::raiseWarning(1, JText::_('JRESEARCH_SQL_UPGRADE_FAILED').': '.$db->getErrorMsg());
    }

    fclose($fh);
}



$removedFiles = $installation.DS.'deleted.txt';
$fh = @fopen($removedFiles, 'r');
if($fh !== false){
    while ($line = fgets ($fh)) {
        if ($line !== false){
            $comp = explode(' ', $line);
            // Assume directories are empty
            $item = JPATH_SITE.$comps[1];
            if($comp[0] == 'D'){
                if(is_file($item)){
                    @unlink($item);
                }elseif(is_dir($item)){
                   @rmdir($item);
                }
            }
        }
    }
    
    fclose ($fh);
}

if (is_dir( $installation )) {
    JFolder::delete($installation);
}

$cached_update = $params->get('cached_update', 0);

// delete the left overs unless cached update
if(!$cached_update) 
{
    if (is_file( $filename ) ) {
            JFile::delete($filename);
    }

    $upgrade_xml = $tmp_path . DS . 'jupgrader.xml';
    if ( is_file( $upgrade_xml ) ) {
            JFile::delete($upgrade_xml);
    }
}
?>

<p>You have successfully upgraded your J!Research install! Congratulations!</p>

<?php
if($cached_update) {
	?><p>Note: You will have to delete the update files yourself from your temporary directory.</p><?php 
}