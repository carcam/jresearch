<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage		JResearch
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'researchArea.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'project.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'thesis.php');


/**
* Model class for holding a single research area record.
*
* @subpackage		JResearch
*/
class JResearchModelResearchArea extends JResearchModelSingleRecord{

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId){
		$db =& JFactory::getDBO();
		
		$researchArea = new JResearchArea(&$db);
		$researchArea->load($itemId);
		return $researchArea;
	}
	
	/**
	 * Returns the staff members that work in a specific research 
	 * area.
	 * 
	 * @param int $id_area Research area id
	 *
	 */
	public function getStaffMembers($id_area){
		$members = array();
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').'WHERE '.$db->nameQuote('published').' = 1'
				 .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($id_area);

		$db->setQuery($query);
		$result = $db->loadAssocList();

		foreach($result as $r){
			$newMember = new JResearchMember(&$db);
			$newMember->bind($r);
			$members[] = $newMember;
		}
		
		return $members;
		
	}
	
	
	/**
	 * Returns an array with the n latest publications associated to the
	 * research area.
	 *
	 * @param int $areaId
	 * @param int $n
	 * @return array Array of JResearchPublicationObjects
	 */
	function getLatestPublications($areaId, $n = 0){
		$db =& JFactory::getDBO();
		$latestPub = array();
		
		$idd = $db->nameQuote('id');
		$query = "SELECT $idd FROM ".$db->nameQuote('#__jresearch_publication').' WHERE '.$db->nameQuote('published').' = 1 AND '.$db->nameQuote('internal').' = 1'
				 .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY year DESC, created DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
		
		$db->setQuery($query);
		$result = $db->loadResultArray();
		foreach($result as $id){
			$publication =& JResearchPublication::getById($id);
			$latestPub[] = $publication;
		}
		
		return $latestPub;
				 
	}
	
	
	/**
	 * Returns the number of publications where the member has participated.
	 * 
	 * @param int $memberId
	 */
	function countPublications($areaId){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_publication').' WHERE '.$db->nameQuote('published').' =  1 AND '.$db->nameQuote('internal').' = 1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId);		
		$db->setQuery($query);		
		return (int)$db->loadResult();
	}

	/**
	 * Returns an array with the n latest projects in which the member has collaborated.
	 * @param int $areaId
	 * @param int $n
	 */
	function getLatestProjects($areaId, $n = 0){
		$db =& JFactory::getDBO();
		$latestProj = array();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_project').' WHERE '.$db->nameQuote('published').' = 1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY start_date DESC, created DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $r){
			$project = new JResearchProject(&$db);
			$project->bind($r);
			$latestProj[] = $project;
		}
		
		return $latestProj;
		
		
	}
	
		
	/**
	 * Returns the number of projects the member has participated.
	 * @param int $areaId
	 */
	function countProjects($areaId){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_project').' WHERE '.$db->nameQuote('published').' =  1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId);		
		$db->setQuery($query);		
		return (int)$db->loadResult();
		
	}
	
	/**
	 * Returns an array with the n latest theses in which the member has collaborated.
	 * @param int $memberId
	 * @param int $n
	 */
	function getLatestTheses($areaId, $n = 0){
		$db =& JFactory::getDBO();
		$latestThes = array();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_thesis').' WHERE '.$db->nameQuote('published').' = 1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY start_date DESC, created DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $r){
			$thesis = new JResearchThesis(&$db);
			$thesis->bind($r);
			$latestThes[] = $thesis;
		}
		
		return $latestThes;				
	}
	

		
	/**
	 * Returns the number of degree theses the member has participated.
	 * @param int $areaId
	 */
	function countTheses($areaId){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_thesis').' WHERE '.$db->nameQuote('published').' =  1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId);		
		$db->setQuery($query);		
		return (int)$db->loadResult();
		
	}

}
?>
