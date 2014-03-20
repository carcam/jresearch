<?php
/**
* @package		JResearch
* @subpackage	Fields
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Control displaying available list of member positions
*/

defined('_JEXEC') or die;

jimport('joomla.form.fields.list');
require_once(JPATH_LIBRARIES.DS.'joomla'.DS.'form'.DS.'fields'.DS.'list.php');


class JFormFieldMemberpositionslist extends JFormFieldList{

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__jresearch_member_position');
        $query->where('published = 1');
        $db->setQuery((string)$query);

        $positions = $db->loadAssocList();

        $options[] = JHtml::_('select.option', 0, JText::_('JRESEARCH_MEMBER_POSITIONS'), 'value', 'text', 0);                

        foreach($positions as $position){
           	$tmp = JHtml::_('select.option', $position['id'], $position['position'], 'value', 'text', 0);
           	// Add the option object to the result set.
        	$options[] = $tmp;
        }

		reset($options);

		return $options;
	}	

}
