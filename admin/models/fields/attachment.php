<?php
/**
 * @package		J!Research
 * @subpackage	Form
 * @copyright	Luis Galárraga (C) 2008
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');


/**
 * Control to display file attachments
 *
 */
class JFormFieldAttachment extends JFormField{
	
	protected $type = 'Attachment';
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput()
	{	  
		$params = JComponentHelper::getParams('com_jresearch');
		$filename = $this->value;
		$name = $this->element['name'];
		$extension = explode('.', $this->element['value']);
		$supportedExtensions = array('doc', 'docx', 'pdf', 'ps', 'odt', 'txt');
		$assetsUrl = 'administrator/components/com_jresearch/assets/extensions/';
		$url = JURI::root().'administrator/components/com_jresearch/'.$params->get('files_root_path', 'files').'/'.$this->element['controller'].'/'.$filename;
	
		if(in_array($extension[1], $supportedExtensions)){
			$img = JURI::root().$assetsUrl.$extension[1].'.png';
		}else{
			$img = JURI::root().$assetsUrl.'default.png';				
		}
		
		$input = "<a href=\"$url\" ><img style=\"border: 0px;\" align=\"left\" src=\"$img\" />$filename</a>";
		$input .= "<input name=\"jform[$name]\" type=\"hidden\" id=\"jform_$name\" value=\"$filename\" />";	
		return $input;
	
	}
}


?>