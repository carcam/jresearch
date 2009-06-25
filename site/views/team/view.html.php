<?php
jimport( 'joomla.application.component.view' );

/**
 * Single Team View
 *
* @package		JResearch
* @subpackage	Teams
 */
class JResearchViewTeam extends JResearchView
{
	/**
	 * Team view display method
	 * @return void
	 **/
	function display($tpl = null)
	{	
		global $mainframe;
		
		$id = JRequest::getInt('id');
		$itemId = JRequest::getVar('Itemid');
		$layout =& $this->getLayout();
		$doc =& JFactory::getDocument();
		
		$arguments = array('team', $id);
		
		// Get data from the model
		$model = &$this->getModel();
		$memberModel = &$this->getModel('Member');
		$pubsModel = &$this->getModel('Publicationlist');
		
		$item = $model->getItem($id);
		
		if(empty($item))
		{
			JError::raiseWarning(1, JText::_('JRESEARCH_TEAM_NOT_FOUND'));
			return;
		}
		
		switch($layout)
		{
			default:
				break;
		}
		
		$members = $item->getMembers();
		$publications = $pubsModel->getDataByTeam($id);
		
		$links = array();
		foreach($members as $member)
		{
			array_push($links, '<a href="index.php?option=com_jresearch&view=member&task=show&id='.$member->id.(isset($itemId)?'&Itemid='.$itemId:'').'" title="">'.$member->__toString().'</a>');
		}
		
		$doc->addStyleDeclaration('
		div.content div.tr
		{
			margin: 5px 0;
		}
		');
		
		$doc->setTitle(JText::_('JRESEARCH_TEAM').' - '.$item->name);

		$this->assignRef('item', $item, JResearchFilter::OBJECT_XHTML_SAFE);
		$this->assignRef('memberLinks', $links);
		$this->assignRef('memberModel', $memberModel);
		$this->assignRef('publications', $publications);
		$this->assignRef('itemId', $itemId);

		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
	}

}
?>