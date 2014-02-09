<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Frontend
* @copyright	Copyright (C) 2008-2014 Hepta Technologies.
* @author		Carlos Cámara
* @license		GNU/GPL
* This file is the Joomla! router
*/
defined('_JEXEC') or die;

function JresearchBuildRoute(&$query)
{
	$segments = array();
	if(isset($query['view']))
	{
		$segments[] = $query['view'];
		unset( $query['view'] );
	}
	if(isset($query['task']))
	{
		$segments[] = $query['task'];
		unset( $query['task'] );
	}
	if(isset($query['id']))
	{
		$segments[] = $query['id'];
		unset( $query['id'] );
	};
	return $segments;
}

function JresearchParseRoute( $segments )
{
       $vars = array();
	   $vars['view'] = $segments[0];
	   $vars['task'] = $segments[1];
	   $vars['id'] = $segments[2];
   /*    switch($vars['view'])
       {
			case 'cooperation':
				$q = 'SELECT id, name FROM #__jresearch_cooperations WHERE id='.$id;
				$database->setQuery($q);
				$shCoop = $database->loadObject();
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_COOPERATION'];
				$title[] = $shCoop->name;
				break;
			case 'cooperations':
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_COOPERATIONS'];
				break;
			case 'facilities':
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_FACILITIES'];
				break;
			case 'facility':
				$q = 'SELECT id, name FROM #__jresearch_facilities WHERE id='.$id;
				$database->setQuery($q);
				$shFac = $database->loadObject();
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_FACILITY'];
				$title[] = $shFac->name;
				break;
			case 'publicationslist':
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PUBLICATIONS_LIST'];
				break;
				case 'projectslist':
				case 'projects':
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PROJECTS_LIST'];
				break;
			case 'researchareaslist':
			case 'researchareas':
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_RESEARCH_AREAS'];
				break;
			case 'staff':
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_STAFF'];
				break;
			case 'theseslist':
			case 'theses':
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_THESIS_LIST'];
				break;
			case 'publication':
				$q = 'SELECT id, title  FROM #__jresearch_publication WHERE id = '.$id;
				$database->setQuery($q);
				$shPublication = $database->loadObject( );
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PUBLICATION'];
				$title[] = $shPublication->title;
				break;
			case 'project':
				$q = 'SELECT id, title  FROM #__jresearch_project WHERE id = '.$id;
				$database->setQuery($q);
				$shPublication = $database->loadObject( );
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PROJECT'];
				$title[] = $shPublication->title;
				break;
			case 'researcharea':
				$q = 'SELECT id, name  FROM #__jresearch_research_area WHERE id = '.$id;
				$database->setQuery($q);
				$shPublication = $database->loadObject( );
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_RESEARCH_AREA'];
				$title[] = $shPublication->name;
				break;
			case 'member':
				$vars['id'] = $segments[2];
				switch($vars['task'])
				{
					case 'edit':
						$vars['id'] = $segments[2];
						break;
					case 'show':
						$vars['id'] = $segments[2];
						break;
				}
				break;
			case 'team':
				$q = 'SELECT id, name FROM #__jresearch_team WHERE id='.$id;
				$database->setQuery($q);
				$shTeam = $database->loadObject();
				$title[] = 'Team';
				$title[] = $shTeam->name;
				break;
			case 'teams':
				$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_TEAMS'];
				break;
			case 'thesis':
				$q = 'SELECT id, title  FROM #__jresearch_thesis WHERE id = '.$id;
				$database->setQuery($q);
				$shPublication = $database->loadObject( );
				$title[] = $shPublication->title;
				break;
       }*/
       return $vars;
}
