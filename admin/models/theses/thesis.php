<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Theses
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'thesis.php');


/**
* Model class for holding a single thesis record.
*
* @subpackage	Theses
*/
class JResearchModelThesis extends JResearchModelSingleRecord{

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId){
		$db =& JFactory::getDBO();

		$thesis = new JResearchThesis($db);
		$thesis->load($itemId);
		
		return $thesis;
	}

}
?>
