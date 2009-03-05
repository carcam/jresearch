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
		$availableTypes = array(
			'image/png' => 'png',
			'image/gif' => 'gif',
			'image/jpeg' => 'jpg',
			'image/tiff' => 'tiff'
		);
		
		$uploadedFile = $file['tmp_name'];
		
		if($uploadedFile != null)
		{
			list($width, $height, ,) = getimagesize($uploadedFile);
			
			if(!array_key_exists($file['type'], $availableTypes))
			{
				JError::raiseWarning(1, JText::_('Image format not supported. Please provide images with extension jpg, gif, png'));
			}
			elseif($width > $maxWidth || $height > $maxHeight)
			{
				JError::raiseWarning(1, JText::_('The image exceeds maximum size allowed ('.$maxWidth.'x'.$maxHeight.')'));
			}
		}
		
		return JResearch::upload($imageVar, $file, $folder, $availableTypes, $delete);
	}
	
	/**
	 * Allows the upload of a document. Supported formats are pdf, plain text, postscript, MSOffice (2003 and 2007) and Open Office
	 * documents.
	 *
	 * @param array $fileArray Array with the information about the uploaded file, provided by the server
	 * @param string $path Path within J!Research administrator space, where the file will be located.
	 * @return string The basename of the uploaded file or null if there is a problem during the upload or a non-supported format file
	 * has been provided.
	 */
	function uploadDocument($file, $path){
		$uploadedFile = $file['tmp_name'];
		$availableTypes = array('application/msword'=>'doc','application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>'docx',
		'application/pdf'=>'pdf','application/postscript'=>'ps', 
		'application/vnd.oasis.opendocument.text'=>'odt', 'text/plain'=>'txt');
		if($uploadedFile != null){
			if(isset($availableTypes[$file['type']])){
				$newName = JPATH_COMPONENT_ADMINISTRATOR.DS.$path.DS.basename($file['name']);
				if(!move_uploaded_file($uploadedFile, $newName)){
					JError::raiseWarning(1, JText::sprintf('JRESEARCH_FILE_COULD_NOT_BE_IMPORTED', basename($newName)));
				}else{
					//Construct the file entry
					return basename($newName);
				}
			}else{
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_DOCUMENT_FORMAT_NOT_SUPPORTED', basename($newName)));
			}
		}
		
		return null;
	}
	
	
	/**
	 * Uploads ONE file to specific folder (relative path from component administrator folder)
	 */
	public static function upload(&$fileVar, $file, $folder, array $types=array(), $delete=false)
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
			if(array_key_exists($file['type'], $types))
			{
				$newName = JPATH_COMPONENT_ADMINISTRATOR.DS.JPath::clean($folder).DS.$file['name'];
				$base = basename($newName);
				
				if(!move_uploaded_file($uploadedFile, $newName))
				{
					JError::raiseWarning(1, JText::sprintf('JRESEARCH_FILE_COULD_NOT_BE_IMPORTED', $base));
				}
				else
				{
					//@todo make relative path
					//JURI::base().'components/com_jresearch/'.
					$path = JString::str_ireplace(DS,'/',$folder).$base;
					$fileVar = $path;
				}
			}
			else 
			{
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_FILE_FORMAT_NOT_SUPPORTED', basename($newName)));
			}
		}
		
		return $path;
	}
	
	public static function getUrlByRelative($rel_path)
	{
		$path = JURI::base().'components/com_jresearch/';
		
		return $path.str_replace($path, '', $rel_path); //For backward compatibility if absolute path is stored
	}
}
?>