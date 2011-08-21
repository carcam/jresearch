<?php
/**
* @package		JResearch
* @subpackage	Frontend.Models
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

jresearchimport( 'joomla.application.component.modelform' );

/**
 * This is the parent class for all frontend models 
 * dealing with frontend edition
 *
 * @author lgalarra
 *
 */
abstract class JResearchModelForm extends JModelForm{
	/**
	 * 
	 * Cache for form data
	 * @var array
	 */
	protected $_data;
	
	/**
	 * 
	 * Cache for item
	 * @var array
	 */
	protected $_row;
	
	
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	abstract public function getItem();
	
	
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param	string The table name. Optional.
	 * @param	string The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	object	The table
	 */
	public function getTable($name='', $prefix='JResearch', $options = array())
	{
		if (empty($name)) {
			$name = $this->getName();
		}

		if ($table = JTable::getInstance($name, $prefix))  {
			return $table;
		}

		JError::raiseError(0, JText::sprintf('JLIB_APPLICATION_ERROR_TABLE_NAME_NOT_SUPPORTED', $name));

		return null;
	}

}