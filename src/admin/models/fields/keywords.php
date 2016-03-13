<?php
/**
 * @package	JResearch
 * @subpackage	Fields
 * @copyright	2010, Luis Galárraga.
 * @license	GNU General Public License version 2; see LICENSE.txt
 */

defined('_JEXEC') or die( 'Restricted access' );


/**
 * Provides a mechanism to specify a set of keywords to an item.
 *
 */
class JFormFieldKeywords extends JFormField {

   /**
    * @return  string  The field input markup.
    *
    */
   protected function getInput() {
       $jinput = JFactory::getApplication()->input; 
       JHtml::_('jresearchhtml.tags', 'jform_'.$this->element['id'], 
               'option=com_jresearch&controller='.$jinput->get('controller')
               .'&task=retrieveKeywords&format=json', false);
       
       return  '<input type="hidden" id="jform_'.$this->element['id'].'" '
               . 'name="jform['.$this->element['name'].']" '
               . 'size="'.$this->element['size'].'" '
               . 'value="'.$this->value.'" />';       
   }
}