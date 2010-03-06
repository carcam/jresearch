<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Courses
* @copyright	Copyright (C) 2010 Florian Prinz.
* @license		GNU/GPL
* This file implements the course model.
*/

jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'course.php');

class JResearchModelCourse extends JResearchModelSingleRecord
{
	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId)
	{
		$db =& JFactory::getDBO();
		
		$course = new JResearchCourse($db);
		$result = $course->load($itemId);
		
		if($result)
			return $course;
		else
			return null;	
	}
}
?>