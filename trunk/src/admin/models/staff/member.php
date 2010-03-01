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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'project.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'thesis.php');


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
		
		$member = new JResearchMember($db);
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
		
		$member = new JResearchMember($db);
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
				 .' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId).' AND p.published = '.$db->Quote('1').' AND p.internal =  '.$db->Quote('1').' ORDER BY '.$db->nameQuote('p').'.'.$db->nameQuote('year').' DESC';

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
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_publication_internal_author').' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId);
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
				 .$db->nameQuote('#__jresearch_project').' p WHERE '.$db->nameQuote('p').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_project').' AND p.published = '.$db->Quote('1').' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId).' ORDER BY '.$db->nameQuote('p').'.'.$db->nameQuote('start_date').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);

		$result = $db->loadResultArray();
		foreach($result as $id){
			$project = new JResearchProject($db);
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
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_project_internal_author').' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId);
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
				 .' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId).' ORDER BY '.$db->nameQuote('t').'.'.$db->nameQuote('start_date').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadResultArray();
		foreach($result as $id){
			$thesis = new JResearchThesis($db);
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
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_thesis_internal_author').' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId);
		$db->setQuery($query);
		return (int)$db->loadResult();
	}
	
	
	public function getTeams($memberId)
	{
		$db =& JFactory::getDBO();
		$teams = array();
		
		$sql = 'SELECT '.$db->nameQuote('id_team').' FROM '.$db->nameQuote('#__jresearch_team_member').' WHERE '.$db->nameQuote('id_member').' = '.$db->Quote($memberId);
		$db->setQuery($sql);
		
		$ids = $db->loadResultArray();
		
		foreach($ids as $id)
		{
			$team = new JResearchTeam($db);
			$team->load($id);
			$teams[] = $team;
		}
		
		
		return $teams;
	}
}
?>
