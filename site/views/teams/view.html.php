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
		
		$layout = $this->getLayout();
		$doc = JFactory::getDocument();
		
		// Get data from the model
		$model = &$this->getModel();
		$items = $model->getData(null, true, true);
		$params = $mainframe->getParams();
		$doc->setTitle(JText::_('JRESEARCH_TEAMS'));		
		
		$this->assignRef('params', $params);
		$this->assignRef('items', $items);
		$this->assignRef('page', $model->getPagination());	

		$eArguments = array('teams', $layout);
		
		$mainframe->triggerEvent('onBeforeListJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListJResearchEntities', $eArguments);
	}
}
?>