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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');

class JResearchModelFacility extends JResearchModelSingleRecord
{
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{		
		$fac = JTable::getInstance('Facility', 'JResearch');
		$result = $fac->load($itemId);
		
		if($result)
			return $fac;
		else
			return null;	
	}

}
?>