<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Journals
* @copyright	Copyright (C) 2010 Luis Galárraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'financier.php');


/**
* Model class for retrieving a single journal record.
*
*/
class JResearchModelJournal extends JResearchModelSingleRecord
{

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$journal = JTable::getInstance('Journal', 'JResearch');
		$result = $journal->load($itemId);
		
		if($result)
			return $journal;
		else
			return null;	
	}

}
?>
