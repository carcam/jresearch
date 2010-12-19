<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'modelSingleRecord.php');

/**
* Model class for holding a single member record.
*
*/
class JResearchModelMember extends JResearchModelSingleRecord{

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	public function getItem($itemId){
		$db =& JFactory::getDBO();
		
		$member = JTable::getInstance('Member', 'JResearch');
		$result = $member->load($itemId);
		
		if($result)
			return $member;
		else
			return null;	
	}
	
	/**
	 * Returns the record with the username specified
	 *
	 * @param string $username
	 * @return JResearchMember Member with the username specified.
	 */
	public function getByUsername($username){
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM ".$db->nameQuote('#__jresearch_member')." WHERE ".$db->nameQuote('username').' = '.$db->Quote($username);
		$db->setQuery($query);
		$results = $db->loadAssoc();		
		$member = JTable::getInstance('Member', 'JResearch');
		$member->bind($results);
		return $member;
	}
		
	/**
	 * Returns an array with the n latest internal and published publications 
	 * in which the member collaborated.
	 *
	 * @param int $memberId
	 * @param int $n
	 * @return array Array of JResearchPublicationObjects
	 */
	function getLatestPublications($memberId, $n = 0){
		$db =& JFactory::getDBO();
		$latestPub = array();
		
		$query = 'SELECT '.$db->nameQuote('id_publication').' FROM '.$db->nameQuote('#__jresearch_publication_internal_author').' ia,  '
				 .$db->nameQuote('#__jresearch_publication').' p WHERE '.$db->nameQuote('p').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_publication').' '
				 .' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId).' AND p.published = '.$db->Quote('1').' AND p.internal =  '.$db->Quote('1').' ORDER BY '.$db->nameQuote('p').'.'.$db->nameQuote('year').' DESC, STR_TO_DATE(p.'.$db->nameQuote('month').', \'%M\' ) DESC, '.'p.'.$db->nameQuote('created').' DESC';

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
	function countPublications($memberId){
		$db = JFactory::getDBO();
		$internal_author = $db->nameQuote('#__jresearch_publication_internal_author');
		$publications = $db->nameQuote('#__jresearch_publication');				
		$memberValue = $db->Quote($memberId);
		$query = "SELECT COUNT(*) FROM $internal_author pia, $publications p WHERE pia.id_publication = p.id AND p.published = 1 AND p.internal = 1 AND pia.id_staff_member = $memberValue";
		$db->setQuery($query);		
		return (int)$db->loadResult();
	}

	/**
	 * Returns an array with the n latest projects in which the member has collaborated.
	 * @param int $memberId
	 * @param int $n
	 */
	function getLatestProjects($memberId, $n = 0){
		$db =& JFactory::getDBO();
		$latestProj = array();
		
		$query = 'SELECT '.$db->nameQuote('id_project').' FROM '.$db->nameQuote('#__jresearch_project_internal_author').' ia,  '
				 .$db->nameQuote('#__jresearch_project').' p WHERE '.$db->nameQuote('p').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_project').' AND p.published = '.$db->Quote('1').' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId).' ORDER BY '.$db->nameQuote('p').'.'.$db->nameQuote('start_date').' DESC, '.$db->nameQuote('p').'.'.$db->nameQuote('end_date').' DESC, p.'.$db->nameQuote('created').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);

		$result = $db->loadResultArray();
		foreach($result as $id){
			$project = JTable::getInstance('Project', 'JResearch');
			$project->load($id);
			$latestProj[] = $project;
		}
		
		return $latestProj;
		
		
	}
	
		
	/**
	 * Returns the number of projects the member has participated.
	 * @param int $memberId
	 */
	function countProjects($memberId){
		$db = JFactory::getDBO();
		$internal_author = $db->nameQuote('#__jresearch_project_internal_author');
		$projects = $db->nameQuote('#__jresearch_project');				
		$memberValue = $db->Quote($memberId);
		$query = "SELECT COUNT(*) FROM $internal_author pia, $projects p WHERE pia.id_project = p.id AND p.published = 1 AND pia.id_staff_member = $memberValue";
		$db->setQuery($query);		
		return (int)$db->loadResult();
	}
	
	/**
	 * Returns an array with the n latest theses in which the member has collaborated.
	 * @param int $memberId
	 * @param int $n
	 */
	function getLatestTheses($memberId, $n = 0){
		$db =& JFactory::getDBO();
		$latestThes = array();
		
		$query = 'SELECT '.$db->nameQuote('id_thesis').' FROM '.$db->nameQuote('#__jresearch_thesis_internal_author').' ia,  '
				 .$db->nameQuote('#__jresearch_thesis').' t WHERE '.$db->nameQuote('t').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_thesis').' AND t.published = '.$db->Quote('1')
				 .' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId).' ORDER BY '.$db->nameQuote('t').'.'.$db->nameQuote('start_date').' DESC, '.$db->nameQuote('t').'.'.$db->nameQuote('end_date').' DESC, t.'.$db->nameQuote('created').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadResultArray();
		foreach($result as $id){
			$thesis = JTable::getInstance('Thesis', 'JResearch');
			$thesis->load($id);
			$latestThes[] = $thesis;
		}
		
		return $latestThes;				
	}
	

		
	/**
	 * Returns the number of degree theses the member has participated.
	 * @param int $memberId
	 */
	function countTheses($memberId){
		$db = JFactory::getDBO();
		$internal_author = $db->nameQuote('#__jresearch_thesis_internal_author');
		$theses = $db->nameQuote('#__jresearch_thesis');				
		$memberValue = $db->Quote($memberId);
		$query = "SELECT COUNT(*) FROM $internal_author pia, $theses p WHERE pia.id_thesis = p.id AND p.published = 1 AND pia.id_staff_member = $memberValue";
		$db->setQuery($query);		
		return (int)$db->loadResult();
	}
	
	
	public function getTeams($memberId)
	{
		$db = JFactory::getDBO();
		$teams = array();
		
		$sql = 'SELECT '.$db->nameQuote('id_team').' FROM '.$db->nameQuote('#__jresearch_team_member').' WHERE '.$db->nameQuote('id_member').' = '.$db->Quote($memberId);
		$db->setQuery($sql);
		
		$ids = $db->loadResultArray();
		
		foreach($ids as $id)
		{
			$team = JTable::getInstance('Team', 'JResearch');
			$team->load($id);
			$teams[] = $team;
		}
		
		
		return $teams;
	}
}
?>
