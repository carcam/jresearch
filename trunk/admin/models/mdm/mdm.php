<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	MtM
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the member of the month model.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');

class JResearchModelMdm extends JResearchModelSingleRecord 
{
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$db =& JFactory::getDBO();
		
		$mdm = new JResearchMdm($db);
		$mdm->load($itemId);
		
		return $mdm;
	}
}
?>