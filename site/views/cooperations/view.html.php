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
		
		$doc = JFactory::getDocument();
		$layout = $this->getLayout();
		// Get data from the model
		$model 	= 	&$this->getModel();
		$items 	= 	$model->getData(null, true, true);
		$cats 	= 	$model->getCategories();
		$params = 	$mainframe->getParams();
		
		$doc->setTitle(JText::_('JRESEARCH_COOPERATIONS'));
		$this->assignRef('params', $params);
		$this->assignRef('items', $items);
		$this->assignRef('cats', $cats);
		$this->assignRef('page', $model->getPagination());	

		$eArguments = array('cooperations', $layout);
		
		$mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
	}
}
?>