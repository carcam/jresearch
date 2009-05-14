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
		global $mainframe;
		$arguments = array('team');
		
		$id = JRequest::getInt('id');
		$layout =& $this->getLayout();
		$doc =& JFactory::getDocument();
		
	   	if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return;
    	}
    	
		// Get data from the model
		$model = &$this->getModel();
		$item = $model->getItem($id);
		
		if(empty($item)){
			JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			return;
		}
		
		$arguments[] = $id;
		
		//Switch layout
		/*
		switch($layout)
		{
			default:
				break;
		}*/
		
		$doc->addStyleDeclaration('
		div.content div.tr
		{
			margin: 5px 0;
		}
		');
		$doc->setTitle(JText::_('JRESEARCH_TEAM').' - '.$item->name);

		$this->assignRef('item', $item);

		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
	}

}
?>