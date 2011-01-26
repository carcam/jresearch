<?php
jimport( 'joomla.application.component.view' );

/**
 * Teams View
 *
* @package		JResearch
* @subpackage	Teams
 */
class JResearchViewTeams extends JResearchView
{
	/**
	 * Teams view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		global $mainframe;
		
		$doc = JFactory::getDocument();
		$layout = $this->getLayout();
		
		// Get data from the model
		$model = &$this->getModel();
		$items = $model->getData(null, true, true);
		$params = $mainframe->getParams();
		
		$doc->setTitle(JText::_('JRESEARCH_TEAMS'));		
		
		$this->assignRef('params', $params);
		$this->assignRef('items', $items, JResearchFilter::ARRAY_OBJECT_XHTML_SAFE, array('exclude_keys' => array('description')));
		$this->assignRef('page', $model->getPagination());	

		$eArguments = array('teams', $layout);
		
		$mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
	}
}
?>