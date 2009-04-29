<?php
/**
 * @version		$Id$
 * @package		JResearch
 * @subpackage	Plugins
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgXMLRPCJresearch extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	* @return array An array of associative arrays defining the available methods
	*/
	public function onGetWebServices()
	{
		global $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		return array
		(
			'jresearch.getPubmedId' => array(
				'function' => 'plgXMLRPCJresearchServices::getPubmedId',
				'docstring' => JText::_('Returns an article of the Pubmed database with the given id.'),
				'signature' => array(array($xmlrpcStruct, $xmlrpcString))
			)
		);
	}
}

class plgXMLRPCJresearchServices
{
	/**
	 * Gets an pubmed article by id
	 *
	 * @param string $id
	 * @return Array
	 * @static 
	 */
	public static function getPubmedId($id)
	{
		global $mainframe, $xmlrpcerruser, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;
		
		plgXMLRPCJresearchHelper::importType('eFetchPubmedService');
		plgXMLRPCJresearchHelper::importService('pubmed');
		
		$soap = new eFetchPubmedService();
		$request = new eFetchRequest();
		$request->retstart = '0';
		$request->retmax = '1';
		$request->id = $id;
		
		$result = new JresearchServicesPubmed($soap->run_eFetch($request));
		
		if($result->hasResult())
		{
			$struct = plgXMLRPCJresearchHelper::createStruct(
				$result->getTitle(),
				$result->getAbstract(),
				$result->getAuthors()
			);
			
			return new xmlrpcresp($struct);
		}

		return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('No result set'));		
	}
}

class plgXMLRPCJresearchHelper
{
	/**
	 * Imports WSDL service types
	 *
	 * @param string $type
	 */
	public static function importType($type)
	{
		$type = str_replace('.php','', $type);
		$file = JPATH_PLUGINS.DS.'xmlrpc'.DS.'jresearch'.DS.'types'.DS.$type.'.php';
		
		if(file_exists($file))
			require_once($file);
	}

	public static function importService($service)
	{
		$service = str_replace('.php','', $service);
		$file = JPATH_PLUGINS.DS.'xmlrpc'.DS.'jresearch'.DS.'services'.DS.$service.'.php';
		
		if(file_exists($file))
			require_once($file);
	}
	
	/**
	 * Authenticates a user against Joomla Users
	 *
	 * @param string $user
	 * @param string $pwd
	 * @return bool
	 */
	public static function isCorrectLogin($user, $pwd)
	{
		// Get the global JAuthentication object
		jimport( 'joomla.user.authentication');
		
		$auth = & JAuthentication::getInstance();
		
		$credentials = array( 'username' => $user, 'password' => $pwd );
		$options = array();
		
		$response = $auth->authenticate($credentials, $options);
		
		return $response->status === JAUTHENTICATE_STATUS_SUCCESS;
	}
	
	public static function createStruct($title, $abstract, array $authors)
	{
		global $mainframe, $xmlrpcerruser, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;
		
		return new xmlrpcval(
			array(
				'title' => new xmlrpcval($title),
				'abstract' => new xmlrpcval($abstract),
				'authors' => new xmlrpcval($authors, $xmlrpcArray)
			),
			$xmlrpcStruct
		);
	}
}
?>