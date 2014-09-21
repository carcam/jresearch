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

	// get a menu item based on Itemid or currently active
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();

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
	   
       return $vars;
}
