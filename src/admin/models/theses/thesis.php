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

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'modelSingleRecord.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'thesis.php');


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
            if($thesis->load($itemId) === false)
                return null;
            else
                return $thesis;
	}

}
?>
