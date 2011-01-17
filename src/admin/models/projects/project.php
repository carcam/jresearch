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
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'project.php');

/**
* Model class for holding a single project record.
*
* @subpackage	Projects
*/
class JResearchModelProject extends JResearchModelSingleRecord{

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId){
            $project = JTable::getInstance('Project','JResearch');
            if($project->load($itemId) === false)
                return null;
            else
                return $project;
	}

}
?>
