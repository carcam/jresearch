<?php
jimport( 'joomla.application.component.view' );

/**
 * Cooperations View
 *
* @package		J!Research
* @subpackage	Cooperations
 */
class JResearchViewCooperations extends JView
{
	/**
	 * Cooperations view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		// Get data from the model
		$model = &$this->getModel();
		$items = $model->getData(null, true, true);
		
		$this->assignRef('items', $items);
		$this->assignRef('page', $model->getPagination());	

		parent::display($tpl);
	}
}
?>