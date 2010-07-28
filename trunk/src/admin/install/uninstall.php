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

/**
 * Invoked during JResearch uninstallation
 * @return boolean True if operations are executed successfully
 */
function com_uninstall(){
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