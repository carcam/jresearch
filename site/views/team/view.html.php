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
		$max_publications = $max_projects = $max_theses = 0;
		
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
		$show_publications = $params->get('team_show_publications', 'yes');
		$show_projects = $params->get('team_show_projects', 'yes');
		$show_theses = $params->get('team_show_theses', 'yes');
		if($show_publications == "yes")
		{
			$pub_view_all = JRequest::getVar('publications_view_all', 0);
			if($pub_view_all == 0) 	
				$pubcount = $params->get('team_number_last_publications', 3);
			else
				$pubcount = -1;
			$publications = $pubsModel->getDataByTeamId($id, $pubcount);
			$max_publications = $model->getNumberPublications($id);
			$this->assignRef('npubs', &$pubcount);
		}
		
		if($show_projects == "yes")
		{
			$pro_view_all = JRequest::getVar('projects_view_all', 0);
			if($pro_view_all == 0) 	
				$procount = $params->get('team_number_last_projects', 3);
			else
				$procount = -1;

			$projects = $projectsModel->getDataByTeamId($id, $procount);
			$max_projects = $model->getNumberProjects($id);
			$this->assignRef('npro', &$procount);			
		}
		
		
		if($show_theses == "yes")
		{
			$the_view_all = JRequest::getVar('theses_view_all', 0);
			if($the_view_all == 0) 	
				$thecount = $params->get('team_number_last_theses', 3);
			else
				$thecount = -1;

			$theses = $thesesModel->getDataByTeamId($id, $thecount);
			$max_theses = $model->getNumberTheses($id);
			$this->assignRef('nthes', &$thecount);
		}
		
		$links = array();
		foreach($members as $member)
		{
			if($member->published)
				$links[] = JHTML::_('jresearch.link', $member, 'member', 'show', $member->id);
			else
				$links[] = $member->__toString();
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
		$this->assignRef('max_publications', $max_publications);
		$this->assignRef('max_projects', $max_projects);
		$this->assignRef('max_theses', $max_theses);
		
		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
	}

}
?>
