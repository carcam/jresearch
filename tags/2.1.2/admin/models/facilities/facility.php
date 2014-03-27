<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the facility model.
*/
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'modelSingleRecord.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'facility.php');

class JResearchModelFacility extends JResearchModelSingleRecord
{
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$db =& JFactory::getDBO();
		
		$fac = new JResearchFacility($db);
		$result = $fac->load($itemId);
		
		if($result)
			return $fac;
		else
			return null;	
	}

}
?>