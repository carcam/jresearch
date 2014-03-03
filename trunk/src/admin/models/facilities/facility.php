<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the facility model.
*/
jimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'models'.'/'.'modelSingleRecord.php');
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'tables'.'/'.'facility.php');

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