<?php
jimport( 'joomla.application.component.view' );

/**
 * Cooperations View
 *
* @package		JResearch
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
		global $mainframe;
		
		$layout = &$this->getLayout();
		$doc = JFactory::getDocument();
		
		// Get data from the model
		$model = &$this->getModel();
		$items = $model->getData(null, true, true);
		$params = $mainframe->getParams();
		
		$doc->setTitle(JText::_('JRESEARCH_COOPERATIONS'));
		$this->assignRef('params', $params);
		$this->assignRef('items', $items);
		$this->assignRef('page', $model->getPagination());	

		$eArguments = array('cooperations', $layout);
		
		$mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
	}
}
?>