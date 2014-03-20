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
		$params = $this->getParams();
		$publications = array();
		$projects = array();
		$theses = array();
		
		$arguments = array('team', $id);
		
		// Get data from the model
		$model = &$this->getModel();
		$memberModel = &$this->getModel('Member');
		$pubsModel = &$this->getModel('Publicationslist');
		$projectsModel = &$this->getModel('Projectslist');
		$thesesModel = &$this->getModel('Theseslist');
		
		$item = $model->getItem($id);
		
		if(empty($item))
		{
			JError::raiseWarning(1, JText::_('JRESEARCH_TEAM_NOT_FOUND'));
			return;
		}
		
		$this->addPathwayItem($item->alias, 'index.php?option=com_jresearch&view=team&id='.$item->id);
		
		switch($layout)
		{
			default:
				break;
		}
		
		$members = $model->getMembers($id);
		
		$show_publications = $params->get('team_show_publications', 1);
		$show_projects = $params->get('team_show_projects', 1);
		$show_theses = $params->get('team_show_theses', 1);
		
		if($show_publications == "yes")
		{
			$count = $params->get('team_number_last_publications', 5);
			$publications = $pubsModel->getDataByTeamId($id, $count);
		}
		
		if($show_projects == "yes")
		{
			$count = $params->get('team_number_last_projects', 5);
			$projects = $projectsModel->getDataByTeamId($id, $count);
		}
		
		if($show_theses == "yes")
		{
			$count = $params->get('team_number_last_theses', 5);
			$theses = $thesesModel->getDataByTeamId($id, $count);
		}
		
		$links = array();
		foreach($members as $member)
		{
			$links[] = JHTML::_('jresearch.link', $member, 'member', 'show', $member->id);
		}
		
		$doc->addStyleDeclaration('
		div.content div.tr
		{
			margin: 5px 0;
		}
		');
		
		$description = explode('<hr id="system-readmore" />', $item->description);
		$leader = $item->getLeader();
		
		$doc->setTitle(JText::_('JRESEARCH_TEAM').' - '.$item->name);

		$this->assignRef('item', $item, JResearchFilter::OBJECT_XHTML_SAFE);
		$this->assignRef('memberLinks', $links);
		$this->assignRef('memberModel', $memberModel);
		$this->assignRef('publications', $publications);
		$this->assignRef('projects', $projects);
		$this->assignRef('theses', $theses);
		$this->assignRef('itemId', $itemId);
		$this->assignRef('description', $description);
		$this->assignRef('leader', $leader);
		
		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
	}

}
?>