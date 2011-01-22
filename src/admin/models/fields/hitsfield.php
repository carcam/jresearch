<?php
/**
* @package		JResearch
* @subpackage	F
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Control for displaying the number of hits of a row and reset them
*/

defined('JPATH_BASE') or die;



/**
 * Control for authors selection
 *
 */
class JFormFieldHitsfield extends JFormField
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput(){		
		$result = '';
		if(!empty($this->element['value'])){
			$result .= '<span class="hits"><span>'.JText::_('Reset').': '.$this->element['value'].' '.JText::_('JRESEARCH_HITS').'</span><span><label for="'.$this->element['name'].'">(';
			$result .= '</label></span><span><input type="checkbox" name="'.$this->element['name'].'" id="'.$this->element['name'].'" /></span></span>';
		}
		return $result;
	}
}

?>