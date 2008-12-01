<?php
jimport( 'joomla.application.component.view' );

/**
 * Single Cooperation View
 *
* @package		JResearch
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
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'toolbar.jresearch.html.php');
		
		JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');
		JResearchToolbar::editCooperationAdminToolbar();
		
		$id = JRequest::getInt('id');
		$layout =& $this->getLayout();
		
		// Get data from the model
		$model = &$this->getModel();
		$item = $model->getItem($id);
		
		$editor =& JFactory::getEditor();

		$this->assignRef('coop', $item);
		$this->assignRef('editor', $editor);

		parent::display($tpl);
	}
}
?>