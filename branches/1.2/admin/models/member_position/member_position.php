<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the project model.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'modelSingleRecord.php');

class JResearchModelMember_position extends JResearchModelSingleRecord 
{
	/**
	 * @see JResearchModelSingleRecord::getItem()
	 *
	 * @param int $itemId
	 * @return JResearchMember_position
	 */
	public function getItem($itemId)
	{
		$db = JFactory::getDBO();
		
		$position = JTable::getInstance('Member_position', 'JResearch');
		$result = $position->load($itemId);
		
		return ($result) ? $position : null;	
	}
}
?>
