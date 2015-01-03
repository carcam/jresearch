<?php
/**
 * @package	J!Research
 * @subpackage	Form
 * @copyright	Luis Galárraga (C) 2008
 * @license	GNU/GPL
 */

defined('_JEXEC') or die( 'Restricted access' );

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
        $mode = empty($this->element['mode']) ? 'upload' : $this->element['mode'];
        $size = empty($this->element['size']) ? '15' : $this->element['size'];
        $tagLabel = JText::_('JRESEARCH_TAG');
        
        if(!empty($this->value)) {
            $uploadedFiles = explode(';', $this->value);
        } else {
            $uploadedFiles = array();
        }

        $k = count($uploadedFiles);
        $textFields = '<input type="hidden" name="jform[count_'.$name.']" id="jform[count_'.$name.']" value="'.$k.'" />';

        $uploadField = '<div>';	
        if($singleFile != 'true') {
            $uploadField .= '<a class="aaddfile" id="add_'.$name
                    .'" href="javascript:addUploader(\''
                    .$name.'\', \''.JText::_('JRESEARCH_DELETE').'\', \''.$tagLabel.'\', true)">'
                    .JText::_('JRESEARCH_ADD_FILE').'</a>';

            $uploadField .= '<a class="aaddurl" id="add_url_'.$name
                    .'" href="javascript:addUploader(\''
                    .$name.'\', \''.JText::_('JRESEARCH_DELETE').'\', \''.$tagLabel.'\', false)">'
                    .JText::_('JRESEARCH_ADD_URL').'</a>';
        }

        $uploadField .= '<ul class="divupload" id="div_upload_'.$name.'">';
        $uploadField .= '<li>';
        if ($mode == 'upload') {
            $uploadField .= '<input id="jform[file_'.$name.'_'.$k.']" name="jform[file_'
                    .$name.'_'.$k.']" class="attachmentfield" type="file" />';
        } else {
            $uploadField .= '<input id="jform[file_'.$name.'_'.$k.']" name="jform[file_'
                    .$name.'_'.$k.']" class="urlfield" type="text" size="'.$size.'" />';            
        }
        // The tag
        $uploadField .= '<span><label class="labelfiletag" for="jform[file_tag_'.$name.'_'.$k.']">'.$tagLabel.'</label>';
        $uploadField .= '<input id="jform[file_tag_'.$name.'_'.$k.']" name="jform[file_tag_'
                    .$name.'_'.$k.']" type="text" class="inputfiletag" size="255" /></span>';
        
        $uploadField .= '</li>';
        //Render the uploaded files
        $baseUrl = $url.'administrator/components/com_jresearch/'.$params->get('files_root_path', 'files').DS.$controller;
        $n = 0;

        $result = '';
        foreach($uploadedFiles as $entry){
            if (empty($entry)) {
                continue;
            }
            $entryParts = explode('|', $entry);
            $file = $entryParts[0];
            $tag = $entryParts[1];
            $tagClass = JResearchUtilities::alias($tag);
            if(!empty($file)){
                $result .= '<li>';
                if (!JResearchUtilities::isValidURL($file)) {
                    $result .= '<a class="attachmentlink '.$tagClass.'" href="'.$baseUrl.DS.$file.'">'
                            .$file.'</a>';
                } else {
                    $result .= '<a class="attachmentlink '.$tagClass.'" href="'.$file.'">'
                            .$file.'</a>';                    
                }
                $result .= '<input type="hidden" name="jform[old_'.$name.'_'.$n.']" id="jform[old_'.$name.'_'.$n.']" value="'.$file.'" />';
                $result .= '<input class="inputfiletag" size="255" type="text" name="jform[old_tag_'.$name.'_'.$n.']" id="jform[old_tag_'.$name.'_'.$n.']" value="'.$tag.'" />';
                $result .= '<label class="filedeletelabel" for="jform[delete_'.$name.'_'.$n.']">'.JText::_('JRESEARCH_DELETE').'</label>';                
                $result .= '<input type="checkbox" name="jform[delete_'.$name.'_'.$n.']" id="jform[delete_'.$name.'_'.$n.']" />';                
                $result .= '</li>';                
                $n++;
            }
        }
        $result .= '</ul></div>';
        return ' '.$uploadField.' '.$textFields.$result;	
    }
}
?>