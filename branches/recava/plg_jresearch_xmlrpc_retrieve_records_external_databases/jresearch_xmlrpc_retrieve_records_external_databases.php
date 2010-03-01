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

class plgXMLRPCjresearch_xmlrpc_retrieve_records_external_databases extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage('', JPATH_ADMINISTRATOR );
	}

	/**
	* @return array An array of associative arrays defining the available methods
	*/
	public function onGetWebServices()
	{
		global $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		return array
		(
			'jresearch.getRemotePublication' => array(
				'function' => 'plgXMLRPCJresearchServices::getRemotePublication',
				'docstring' => JText::_('Returns the information about a publication located in an external database'),
				'signature' => array(array($xmlrpcArray, $xmlrpcString ))
			)
		);
	}
}

class plgXMLRPCJresearchServices
{
	/**
	 * Gets a publication in an external database.
	 *
	 * @param string $id
	 * @return Array
	 */
	public static function getRemotePublication($id)
	{
		global $mainframe, $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;
		JPlugin::loadLanguage('plg_jresearch_xmlrpc_retrieve_records_external_databases', JPATH_ADMINISTRATOR);
		
		$service = JRequest::getVar('service', 'Pubmed');
		$serviceClass = $service.'Service';
		
		$wrapper = plgXMLRPCJresearchHelper::getServiceInstance($serviceClass);
		JRequest::setVar('pmid', $id);
		$request = $wrapper->getPreparedRequest();

		try{
			$struct = $wrapper->call($request);
		}catch(Exception $e){
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('JRESEARCH_NO_RESULT_SET'));
		}

		if(empty($struct))
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('JRESEARCH_NO_RESULT_SET'));

		$format = JRequest::getVar('format', 'xml');	
		return $wrapper->serialize($struct, $format);	
	}
}

class plgXMLRPCJresearchHelper
{
	/**
	 * Imports WSDL service types
	 *
	 * @param string $type
	 */
	public static function importService($service)
	{
		$type = str_replace('.php','', $service);
		$file = JPATH_PLUGINS.DS.'xmlrpc'.DS.'jresearch_xmlrpc_retrieve_records_external_databases'.DS.'types'.DS.$service.'.php';
		if(file_exists($file))
			require_once($file);
	}
	
	/**
	 * Returns an instance of the specified service class.
	 *
	 * @param string $serviceClass The name of the class that implements the service.
	 * @return $mixed An instance of the service class or NULL if the object could not be
	 * instantiated.
	 */
	public static function getServiceInstance($serviceClass){
		static $services_array = array();
		
		if(isset($services_array[$serviceClass])){
			return $services_array[$serviceClass];
		}else{
			self::importService($serviceClass);
			if(class_exists($serviceClass)){
				$services_array[$serviceClass] = new $serviceClass();
				return $services_array[$serviceClass];
			}else{
				return null;
			}		
		}
	}
}
?>