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
		$params = JComponentHelper::getParams('com_jresearch');
		
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
		
		//Upload image and get relative url
		$url = self::upload($imageVar, $file, $folder, $availableTypes, $delete);
		
		//Create thumbnail if it is enabled and upload was successful
		if($params->get('thumbnail_enable', 1) == 1 && $url)
		{
			self::_createThumbnail(JPATH_COMPONENT_ADMINISTRATOR.DS.$url, $folder);
		}
		
		return $url;
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
		'application/pdf'=>'pdf', 'application/x-pdf' => 'pdf', 'application/postscript'=>'ps', 
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
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_DOCUMENT_FORMAT_NOT_SUPPORTED', basename($file['name']).' ('. $file['type']. ')'));
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
                        $newName = JPATH_COMPONENT_ADMINISTRATOR.DS.JPath::clean($folder).DS.$file['name'];
			if(array_key_exists($file['type'], $types))
			{
				$base = basename($newName);
				
				if(!move_uploaded_file($uploadedFile, $newName))
				{
					JError::raiseWarning(1, JText::sprintf('JRESEARCH_FILE_COULD_NOT_BE_IMPORTED', $base));
				}
				else
				{
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
	
	private static function _createThumbnail($uploadedFile, $folder)
	{
		$params = JComponentHelper::getParams('com_jresearch');
		
		list($width, $height, $type,) = getimagesize($uploadedFile);
		
		$thumbSize = self::_getThumbSize($width, $height);
		
		$imageThumb = imagecreatetruecolor($thumbSize->width, $thumbSize->height);
		$imageUploaded = self::_getImageResourceFromFile($uploadedFile, $type);
		
		//Create thumbnail
		if($imageUploaded && imagecopyresampled($imageThumb, $imageUploaded, 0, 0, 0, 0, $thumbSize->width, $thumbSize->height, $width, $height))
		{
			$filename = 'thumb_'.basename($uploadedFile);
			$filepath = JPATH_COMPONENT_ADMINISTRATOR.DS.$folder.$filename;
			
			switch($type)
			{
				case IMAGETYPE_BMP:
					imagewbmp($imageThumb, $filepath);
					break;
				case IMAGETYPE_GIF:
					imagegif($imageThumb, $filepath);
					break;
				case IMAGETYPE_PNG:
					imagepng($imageThumb, $filepath);
					break;
				case IMAGETYPE_JPEG:
				case IMAGETYPE_JPEG2000:
					imagejpeg($imageThumb, $filepath, 90);
					break;
			}
			
			return true;
		}
		
		return false;
	}
	
	private static function _getThumbSize($width, $height, $array = false)
	{
		$object = new stdClass();
		$factor = floatval(intval($height)/intval($width));
		
		if($height > $width)
		{
			$object->height = 78;
			$object->width = intval($object->height/$factor);
		}
		else
		{
			$object->width = 102;
			$object->height = intval($object->width * $factor);
		}
		
		return ($array)?array('width' => $object->width, 'height' => $object->height):$object;
	}
	
	private static function _getImageResourceFromFile($uploadedFile, $type)
	{
		switch($type)
		{
			case IMAGETYPE_BMP:
				$imageUploaded = imagecreatefromwbmp($uploadedFile);
				break;
			case IMAGETYPE_GIF:
				$imageUploaded = imagecreatefromgif($uploadedFile);
				break;
			case IMAGETYPE_PNG:
				$imageUploaded = imagecreatefrompng($uploadedFile);
				break;
			case IMAGETYPE_JPEG:
			case IMAGETYPE_JPEG2000:
				$imageUploaded = imagecreatefromjpeg($uploadedFile);
				break;
			default:
				$imageUploaded = null;
				break;
		}
		
		return $imageUploaded;
	}
	
	public static function getUrlByRelative($rel_path)
	{
		global $mainframe;
		
		$path = JURI::base().((!$mainframe->isAdmin())?'administrator/':'').'components/com_jresearch/';
		
		return $path.str_replace($path, '', $rel_path); //For backward compatibility if absolute path is stored
	}
	
	public static function getThumbUrlByRelative($rel_path)
	{
		$url = JResearch::getUrlByRelative(dirname($rel_path).'/thumb_'.basename($rel_path));
		
		//Check thumb
		if(@file_get_contents($url))
		{
			return $url;
		}
		
		//If thumb doesn't exist, return full size
		return JResearch::getUrlByRelative($rel_path);
	}
	
	/**
	 * Creates hierarchy for different arrays
	 *
	 * @param array $array
	 * @param string $attribute
	 * @param string $key
	 * @param string $indent
	 * @return array
	 */
	public static function hierarchy(array $array, $key=null, $indent='', $attribute='name')
	{
		$class = array();
		
		if(@array_key_exists($key, $array))
		{
			foreach($array[$key] as $value)
			{
				$node = new stdClass();
				
				$node->object = $value;
				$node->treename = $indent.$value->$attribute;
				
				if(@$array[$value->id])
				{
					$node->children = self::hierarchy($array, $value->id, $indent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
				}
				else 
				{
					$node->children = array();
				}
				
				$class[] = $node;
			}
		}
		
		return $class;
	}
	
	/**
	 * Generates an alias for the title sent as parameter. It uses Joomla! style 
	 * for the generation of aliases
	 * @param $text
	 * @return string
	 */
	public static function alias($text){
		return JFilterOutput::stringURLSafe($text);       	
	}
}
?>