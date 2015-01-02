<?php
/**
 * @package		J!Research
 * @subpackage	Form
 * @copyright	Luis Galárraga (C) 2008
 * @license		GNU/GPL
 */

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Control for authors selection
 *
 */
class JFormFieldAuthorsselector extends JFormField
{

   /**
    *
    * @return  string  The field input markup.
    */
   protected function getInput()
   {
       JHtml::_('jresearchhtml.tags', 'jform_'.$this->element['id'], 
               'option=com_jresearch&controller=staff'
               .'&task=retrieveAuthors&format=json', false);
       
       return  '<input type="hidden" id="jform_'.$this->element['id'].'" '
               . 'name="jform['.$this->element['name'].']" '
               . 'size="'.$this->element['size'].'" '
               . 'value="'.$this->value.'" />';
       
   }
}

?>
