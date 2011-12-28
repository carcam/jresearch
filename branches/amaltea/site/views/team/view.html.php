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
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'publications.php');		
		global $mainframe;
		
		$id = JRequest::getInt('id');
		$itemId = JRequest::getVar('Itemid');
		$layout = $this->getLayout();
		$doc = JFactory::getDocument();
		$params = $this->getParams();
		$publications = $projects = $theses = $facilities = $cooperations = array();
		$max_publications = $max_projects = $max_theses = $max_cooperations = $max_facilities = 0;
		
		$arguments = array('team', $id);
		
		// Get data from the model
		$model = $this->getModel();
		$memberModel = $this->getModel('Member');
		$pubsModel = $this->getModel('Publicationslist');
		$projectsModel = $this->getModel('Projectslist');
		$thesesModel = $this->getModel('Theseslist');
		$facilitiesModel = $this->getModel('Facilities');
		$cooperationsModel = $this->getModel('Cooperations');
		
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
		
		$members = $model->getMembers($id, 'current');
		$formerMembers = $model->getMembers($id, 'former');
		$show_publications = $params->get('team_show_publications', 'yes');
		$show_projects = $params->get('team_show_projects', 'yes');
		$show_theses = $params->get('team_show_theses', 'yes');
		$show_facilities = $params->get('team_show_facilities', 'yes');
		$show_cooperations = $params->get('team_show_cooperations', 'yes');
		$applyStyles = $params->get('publications_apply_style', 'no');
		$style = $params->get('citationStyle', 'APA');
    	$format = $params->get('staff_format') == 'last_first'?1 : 0;
    	
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

		if($show_cooperations == "yes")
		{
			$coo_view_all = JRequest::getVar('cooperations_view_all', 0);
			if($coo_view_all == 0) 	
				$coocount = $params->get('team_number_last_cooperations', 3);
			else
				$coocount = -1;

			$cooperations = $cooperationsModel->getDataByTeamId($id, $coocount);
			$max_cooperations = $model->getNumberCooperations($id);
			$this->assignRef('ncoo', &$coocount);
		}
		
		if($show_facilities == "yes")
		{
			$fac_view_all = JRequest::getVar('facilities_view_all', 0);
			if($fac_view_all == 0) 	
				$faccount = $params->get('team_number_last_facilities', 3);
			else
				$faccount = -1;

			$facilities = $facilitiesModel->getDataByTeamId($id, $faccount);
			$max_facilities = $model->getNumberFacilities($id);
			$this->assignRef('nfac', &$faccount);
		}
		
		$links = array();
		foreach($members as $member)
		{
			$position = $member->getPosition();
						
			if($member->published)
				$text = JHTML::_('jresearch.link', (!empty($member->title)?$member->title.' ':'').JResearchPublicationsHelper::formatAuthor($member->__toString(), $format), 'member', 'show', $member->id, true);
			else
				$text = (!empty($member->title)?$member->title.' ':'').JResearchPublicationsHelper::formatAuthor($member->__toString(), $format);

			if(!empty($position) && !empty($position))	
				$links[] = $text.' | '.$position->position;				
			else
				$links[] = $text;	
		}
		
		$formerLinks = array();
		foreach($formerMembers as $member)
		{
			$position = $member->getPosition();
						
			if($member->published)
				$text = JHTML::_('jresearch.link', (!empty($member->title)?$member->title.' ':'').JResearchPublicationsHelper::formatAuthor($member->__toString(), $format), 'member', 'show', $member->id, true);
			else
				$text = (!empty($member->title)?$member->title.' ':'').JResearchPublicationsHelper::formatAuthor($member->__toString(), $format);

			if(!empty($position) && !empty($position))	
				$formerLinks[] = $text.' | '.$position->position;				
			else
				$formerLinks[] = $text;	
		}
		
		$doc->addStyleDeclaration('
		div.content div.tr
		{
			margin: 5px 0;
		}
		');
		
		$description = explode('<hr id="system-readmore" />', $item->description);
		$leader = $item->getLeader();		
		$enableThumbnails = $params->get('thumbnail_enable', 1);		

		$doc->setTitle(JText::_('JRESEARCH_TEAM').' - '.$item->name);

		$this->assignRef('item', $item, JResearchFilter::OBJECT_XHTML_SAFE);
		$this->assignRef('memberLinks', $links);
		$this->assignRef('formerMemberLinks', $formerLinks);
		$this->assignRef('memberModel', $memberModel);
		$this->assignRef('publications', $publications);
		$this->assignRef('projects', $projects);
		$this->assignRef('theses', $theses);
		$this->assignRef('facilities', $facilities);
		$this->assignRef('cooperations', $cooperations);
		$this->assignRef('itemId', $itemId);
		$this->assignRef('description', $description);
		$this->assignRef('leader', $leader);
		$this->assignRef('max_publications', $max_publications);
		$this->assignRef('max_projects', $max_projects);
		$this->assignRef('max_theses', $max_theses);
		$this->assignRef('max_facilities', $max_facilities);
		$this->assignRef('max_cooperations', $max_cooperations);		
		$this->assignRef('applyStyles', $applyStyles);
		$this->assignRef('style', $style);
		$this->assignRef('enableThumbnails', $enableThumbnails);
		$this->assignRef('format', $format);
		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
	}

}
?>
