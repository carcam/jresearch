<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Financiers
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'financier.php');


/**
* Model class for holding a single financier record.
*
*/
class JResearchModelFinancier extends JResearchModelSingleRecord
{

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$db =& JFactory::getDBO();
		
		$fin = new JResearchFinancier($db);
		$result = $fin->load($itemId);
		
		if($result)
			return $fin;
		else
			return null;	
	}

}
?>
