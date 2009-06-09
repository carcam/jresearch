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
		
		$id = JRequest::getInt('id');
		$itemId = JRequest::getVar('Itemid');
		$layout =& $this->getLayout();
		$doc =& JFactory::getDocument();
		
		$arguments = array('team', $id);
		
		// Get data from the model
		$model = &$this->getModel();
		$memberModel = &$this->getModel('Member');
		
		$item = $model->getItem($id);
		
		switch($layout)
		{
			default:
				break;
		}
		
		$members = $item->getMembers();
		
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

		$this->assignRef('item', $item);
		$this->assignRef('memberLinks', $links);
		$this->assignRef('memberModel', $memberModel);
		$this->assignRef('itemId', $itemId);

		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
	}

}
?>