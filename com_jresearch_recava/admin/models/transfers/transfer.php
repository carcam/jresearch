<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Transfers
* @copyright	Copyright (C) 2010 Florian Prinz.
* @license		GNU/GPL
* This file implements the transfer model.
*/

jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'xxx.php');

class JResearchModelTransfer extends JResearchModelSingleRecord
{
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$db =& JFactory::getDBO();
		
		$transfer = new JResearchxxx($db);
		$result = $transfer->load($itemId);
		
		if($result)
			return $transfer;
		else
			return null;	
	}
}
?>