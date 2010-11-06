<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');


/**
* Model class for holding a single thesis record.
*
*/
class JResearchModelThesis extends JResearchModelSingleRecord{

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId){
		$thesis = JTable::getInstance('Thesis', 'JResearch');
		$result = $thesis->load($itemId);
		
		if($result)
			return $thesis;
		else
			return null;	
	}

}
?>
