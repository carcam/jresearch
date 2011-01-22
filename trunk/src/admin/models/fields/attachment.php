<?php
/**
 * @package		J!Research
 * @subpackage	Form
 * @copyright	Luis Galárraga (C) 2008
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;


/**
 * Control to display file attachments
 *
 */
class JFormFieldAttachment extends JFormField{
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput()
	{
		$params = JComponentHelper::getParams('com_jresearch');
		$filename = $params->get('files_root_path', 'files').DS.$this->element['controller'].DS.$this->element['value'];
		$extension = explode('.', $this->element['value']);
		$supportedExtensions = array('doc', 'docx', 'pdf', 'ps', 'odt', 'txt');
		$assetsUrl = 'administrator/components/com_jresearch/assets/extensions/';
	
		if(in_array($extension[1], $supportedExtensions)){
			$img = $assetsUrl.$extension[1].'.png';
		}else{
			$img = $assetsUrl.'default.png';				
		}
		
		return "<a href=\"$url\" ><img style=\"border: 0px;\" src=\"$img\" />$filename</a>";
		
	
	}
}


?>