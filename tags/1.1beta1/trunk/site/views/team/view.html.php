<?php
jimport( 'joomla.application.component.view' );

/**
 * Single Team View
 *
* @package		JResearch
* @subpackage	Teams
 */
class JResearchViewTeam extends JView
{
	/**
	 * Team view display method
	 * @return void
	 **/
	function display($tpl = null)
	{	
		$id = JRequest::getInt('id');
		$layout =& $this->getLayout();
		$doc =& JFactory::getDocument();
		
		// Get data from the model
		$model = &$this->getModel();
		$item = $model->getItem($id);
		
		switch($layout)
		{
			default:
				break;
		}
		$doc->addStyleDeclaration('
		div.content div.tr
		{
			margin: 5px 0;
		}
		');
		$doc->setTitle(JText::_('JRESEARCH_TEAM').' - '.$item->name);

		$this->assignRef('item', $item);

		parent::display($tpl);
	}

}
?>