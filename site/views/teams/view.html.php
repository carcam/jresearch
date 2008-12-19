<?php
jimport( 'joomla.application.component.view' );

/**
 * Teams View
 *
* @package		JResearch
* @subpackage	Teams
 */
class JResearchViewTeams extends JView
{
	/**
	 * Teams view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		global $mainframe;
		
		// Get data from the model
		$model = &$this->getModel();
		$items = $model->getData(null, true, true);
		$params = $mainframe->getParams();
		
		$this->assignRef('params', $params);
		$this->assignRef('items', $items);
		$this->assignRef('page', $model->getPagination());	

		parent::display($tpl);
	}
}
?>