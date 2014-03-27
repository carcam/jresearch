<?php
/**
 * @package	JResearch
 * @subpackage	Fields
 * @copyright	2010, Luis Galárraga.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.fields.list');
require_once(JPATH_LIBRARIES.DS.'joomla'.DS.'form'.DS.'fields'.DS.'list.php');

/**
 * Form list for J!Research forms.
 *
 * @package     JResearch
 * @subpackage	Fields
 * @since 2.0
 */
class JFormFieldResearchareaslist extends JFormFieldList
{

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
        $query->from('#__jresearch_research_area');
        $query->where('published = 1');
        $db->setQuery((string)$query);

        $areas = $db->loadAssocList();

        $options[] = JHtml::_('select.option', 1, JText::_('JRESEARCH_UNCATEGORIZED'), 'value', 'text', 0);                

        foreach($areas as $area){
        	// Only add <option /> elements.
            if ($area['id'] == 1) {
            	continue;
            }

            $tmp = JHtml::_('select.option', $area['id'], $area['name'], 'value', 'text', 0);

            // Add the option object to the result set.
            $options[] = $tmp;
        }
        
		reset($options);

		return $options;
	}
}

?>