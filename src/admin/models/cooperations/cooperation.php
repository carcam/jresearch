<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the cooperation model.
*/

jimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'models'.'/'.'modelSingleRecord.php');
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'tables'.'/'.'cooperation.php');

class JResearchModelCooperation extends JResearchModelSingleRecord
{
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$db =& JFactory::getDBO();
		
		$coop = new JResearchCooperation($db);
		$result = $coop->load($itemId);
		
		if($result)
			return $coop;
		else
			return null;	
	}
}
?>