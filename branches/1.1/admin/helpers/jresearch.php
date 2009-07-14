<?php
/**
 * @package JResearch
 * @subpackage Helpers
 * @author Florian Prinz
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Helper function for different functionalities
 *
 */
class JResearch
{	
	/**
	 * Uploads one image file
	 * 
	 * @param string $imageVar
	 * @param array $file
	 * @param string $folder
	 * @param bool $delete
	 * @param int $maxWidth
	 * @param int $maxHeight
	 * @return string
	 */
	public static function uploadImage(&$imageVar, $file, $folder, $delete=false, $maxWidth=1024, $maxHeight=768)
	{
		$path = '';
		$ext = 'tmp';
		$availableTypes = array('image/png','image/gif','image/jpg','image/jpeg');
		$uploadedFile = $file['tmp_name'];
		
		list($width, $height, ,) = getimagesize($uploadedFile);
		
		if($uploadedFile != null)
		{
			if(!in_array($file['type'], $availableTypes))
			{
				JError::raiseWarning(1, JText::_('Image format not supported. Please provide images with extension jpg, gif, png'));
			}
			elseif($width > $maxWidth || $height > $maxHeight)
			{
				JError::raiseWarning(1, JText::_('The image exceeds maximum size allowed ('.$maxWidth.'x'.$maxHeight.')'));
			}
			
			// Get extension
			$extArr = explode('/', $file['type']);
			$ext = $extArr[1];
		}
		
		$path = JResearch::upload($imageVar, $file, $folder, $ext, $delete);
		
		return $path;
	}
	
	/**
	 * Uploads ONE file to specific folder (relative path from component administrator folder)
	 */
	public static function upload(&$fileVar, $file, $folder, $ext='tmp', $delete=false)
	{
		$path = '';
		
		//Delete file and set file variable to empty string
		if(($delete === true) && ($fileVar != null))
		{
			@unlink(JPATH_COMPONENT_ADMINISTRATOR.DS.$folder.basename($fileVar));
			$fileVar = '';
		}
		
		$uploadedFile = $file['tmp_name'];
		
		if($uploadedFile != null)
		{
			$newName = JPATH_COMPONENT_ADMINISTRATOR.DS.$folder.DS.basename($uploadedFile).'.'.$ext;

			if(!move_uploaded_file($uploadedFile, $newName))
			{
				JError::raiseWarning(1, JText::_('The file could not be imported into J!Research space.'));
			}
			else
			{
				$fileVar = 'components/com_jresearch/'.JString::str_ireplace(DS,'/',$folder).basename($newName);
			}
		}
		
		return $fileVar;
	}
	
	public static function getUrlByRelative($rel_path)
	{
		global $mainframe;
		
		$path = JURI::base().((!$mainframe->isAdmin())?'administrator/':'');
		
		return $path.str_replace($path, '', $rel_path); //For backward compatibility if absolute path is stored
	}
}
?>