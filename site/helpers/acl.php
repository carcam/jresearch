<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

function setACL()
{
	$acl = JFactory::getACL();
	
	//Publications
	//Add publications
	$acl->addACL('com_jresearch', 'add', 'users', 'author', 'publications', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'editor', 'publications', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'publisher', 'publications', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'manager', 'publications', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'administrator', 'publications', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'super administrator', 'publications', 'all');
	
	//Edit publications
	$acl->addACL('com_jresearch', 'edit', 'users', 'registered', 'publications', 'own');
	$acl->addACL('com_jresearch', 'edit', 'users', 'author', 'publications', 'own');
	$acl->addACL('com_jresearch', 'edit', 'users', 'editor', 'publications', 'all');
	$acl->addACL('com_jresearch', 'edit', 'users', 'publisher', 'publications', 'all');
	$acl->addACL('com_jresearch', 'edit', 'users', 'manager', 'publications', 'all');
	$acl->addACL('com_jresearch', 'edit', 'users', 'administrator', 'publications', 'all');
	$acl->addACL('com_jresearch', 'edit', 'users', 'super administrator', 'publications', 'all');
	
	//Remove publications
	$acl->addACL('com_jresearch', 'remove', 'users', 'registered', 'publications', 'own');
	$acl->addACL('com_jresearch', 'remove', 'users', 'author', 'publications', 'own');
	$acl->addACL('com_jresearch', 'remove', 'users', 'editor', 'publications', 'all');
	$acl->addACL('com_jresearch', 'remove', 'users', 'publisher', 'publications', 'all');
	$acl->addACL('com_jresearch', 'remove', 'users', 'manager', 'publications', 'all');
	$acl->addACL('com_jresearch', 'remove', 'users', 'administrator', 'publications', 'all');
	$acl->addACL('com_jresearch', 'remove', 'users', 'super administrator', 'publications', 'all');
	
	//Theses
	//Add theses
	$acl->addACL('com_jresearch', 'add', 'users', 'author', 'theses', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'editor', 'theses', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'publisher', 'theses', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'manager', 'theses', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'administrator', 'theses', 'all');
	$acl->addACL('com_jresearch', 'add', 'users', 'super administrator', 'theses', 'all');
	
	//Edit theses
	$acl->addACL('com_jresearch', 'edit', 'users', 'registered', 'theses', 'own');
	$acl->addACL('com_jresearch', 'edit', 'users', 'author', 'theses', 'own');
	$acl->addACL('com_jresearch', 'edit', 'users', 'editor', 'theses', 'all');
	$acl->addACL('com_jresearch', 'edit', 'users', 'publisher', 'theses', 'all');
	$acl->addACL('com_jresearch', 'edit', 'users', 'manager', 'theses', 'all');
	$acl->addACL('com_jresearch', 'edit', 'users', 'administrator', 'theses', 'all');
	$acl->addACL('com_jresearch', 'edit', 'users', 'super administrator', 'theses', 'all');
}
?>