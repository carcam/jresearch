<?php
jimport( 'joomla.application.component.view' );

/**
 * Single Cooperation View
 *
* @package		J!Research
* @subpackage	Cooperations
 */
class JResearchViewCooperation extends JView
{
	/**
	 * Cooperation view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$id = JRequest::getInt('id');
		
		// Get data from the model
		$model = &$this->getModel();
		$item = $model->getItem($id);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}
}
?>