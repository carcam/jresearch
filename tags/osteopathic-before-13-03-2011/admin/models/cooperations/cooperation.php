<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Institutes
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the institute model.
*/

jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');

class JResearchModelCooperation extends JResearchModelSingleRecord
{
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$coop = JTable::getInstance('Cooperation', 'JResearch');
		$result = $coop->load($itemId);
		
		if($result)
			return $coop;
		else
			return null;	
	}
}
?>