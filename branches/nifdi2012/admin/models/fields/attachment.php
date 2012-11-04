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
		$mainframe = JFactory::getDbo();
		$doc = JFactory::getDocument();
		$url = JURI::root();
		$doc->addScript($url."components/com_jresearch/js/fileuploader.js");
		$name = $this->element['name'];
		$singleFile = $this->element['singleFile'];
		$params = JComponentHelper::getParams('com_jresearch');
		$controller = $this->element['controller'];

		if(!empty($this->value))
			$uploadedFiles = explode(';', $this->value);
		else
			$uploadedFiles = array();
		
		$k = count($uploadedFiles);
		$textFields = '<input type="hidden" name="jform[count_'.$name.']" id="jform[count_'.$name.']" value="'.$k.'" />';
				
		$uploadField = '<div class="divTdl">';	
		if($singleFile != 'true')
			$uploadField .= '<a id="add_'.$name.'" href="javascript:addUploader(\''.$name.'\', \''.JText::_('Delete').'\')">'.JText::_('Add').'</a>';

		$uploadField .= '<ul id="div_upload_'.$name.'">';	
		$uploadField .= '<li><input id="jform[file_'.$name.'_'.$k.']" name="jform[file_'.$name.'_'.$k.']" type="file" /></li>';	
		//Render the uploaded files
		$baseUrl = $url.'administrator/components/com_jresearch/'.$params->get('files_root_path', 'files').'/'.$controller;
		$n = 0;
		
		foreach($uploadedFiles as $file){	
			if(!empty($file)){		
				$result .= '<li><a href="'.$baseUrl.'/'.$file.'">'.$file.'</a>&nbsp;&nbsp;<label for="jform[delete_'.$name.'_'.$n.']">'.JText::_('Delete').'</label><input type="checkbox" name="jform[delete_'.$name.'_'.$n.']" id="jform[delete_'.$name.'_'.$n.']" />';
				$result .= '<input type="hidden" name="jform[old_'.$name.'_'.$n.']" id="jform[old_'.$name.'_'.$n.']" value="'.$file.'" />';
				$result .= '</li>';
				$n++;
			}
		}
		$result .= '</ul></div>';
		
		return ' '.$uploadField.' '.$textFields.$result;	
	}
}


?>