<?php
/**
* @package		JResearch
* @subpackage	Models
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

jresearchimport('models.modelitem', 'jresearch.site');

/**
* Model class for holding a single research area record.
*
*/
class JResearchModelMember extends JResearchModelItem{
    
	/**
    * Returns the model data store in the user state as a table
    * object
    */
    public function getItem(){
    	if(!isset($this->_row)){
        	$row = $this->getTable('Member', 'JResearch');
            if($row->load(JRequest::getInt('id'))){
            	if($row->published)
                	return $row;
                else
                    return false;
            }else
            	return false;                
       	}

        return $this->_row;
    }
        
	/**
	 * Returns an array with the n latest internal and published publications 
	 * in which the member collaborated.
	 *
	 * @param int $n
	 * @return array Array of JResearchPublicationObjects
	 */
	function getLatestPublications($n = 0){
		$db = JFactory::getDBO();
		$latestPub = array();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);
		
		$query = 'SELECT p.* FROM '.$db->nameQuote('#__jresearch_publication_internal_author').' ia JOIN '
				 .$db->nameQuote('#__jresearch_publication').' p WHERE '.$db->nameQuote('p').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_publication').' '
				 .' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId)
				 .' AND p.published = '.$db->Quote('1').' AND p.internal =  '.$db->Quote('1').' ORDER BY '.$db->nameQuote('p').'.'.$db->nameQuote('year')
				 .' DESC, STR_TO_DATE(p.'.$db->nameQuote('month').', \'%M\' ) DESC, '.'p.'.$db->nameQuote('created').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $row){
			$publication = JTable::getInstance('Publication', 'JResearch');
			$publication->bind($row);
			$latestPub[] = $publication;
		}
		
		return $latestPub;				 
	}
	
	
	/**
	 * Returns the number of publications where the member has participated.
	 * 
	 */
	function countPublications(){
		$db = JFactory::getDBO();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);
		
		$internal_author = $db->nameQuote('#__jresearch_publication_internal_author');
		$publications = $db->nameQuote('#__jresearch_publication');				
		$memberValue = $db->Quote($memberId);

		$query = "SELECT COUNT(*) FROM $internal_author pia, $publications p WHERE pia.id_publication = p.id 
		AND p.published = 1 AND p.internal = 1 AND pia.id_staff_member = $memberValue";
		$db->setQuery($query);		
		
		return (int)$db->loadResult();
	}

	/**
	 * Returns an array with the n latest projects in which the member has collaborated.
	 * @param int $n
	 */
	function getLatestProjects($n = 0){
		$db = JFactory::getDBO();
		$latestProj = array();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);		
		
		$query = 'SELECT p.* FROM '.$db->nameQuote('#__jresearch_project_internal_author').' ia,  '
				 .$db->nameQuote('#__jresearch_project').' p WHERE '.$db->nameQuote('p').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_project')
				 .' AND p.published = '.$db->Quote('1').' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId)
				 .' ORDER BY '.$db->nameQuote('p').'.'.$db->nameQuote('start_date').' DESC, '.$db->nameQuote('p').'.'.$db->nameQuote('end_date').' DESC, p.'.$db->nameQuote('created').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);

		$result = $db->loadAssocList();
		foreach($result as $row){
			$project = JTable::getInstance('Project', 'JResearch');
			$project->bind($row);
			$latestProj[] = $project;
		}
		
		return $latestProj;
	}
	
		
	/**
	 * Returns the number of projects the member has participated.
	 */
	function countProjects(){
		$db = JFactory::getDBO();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);		

		$internal_author = $db->nameQuote('#__jresearch_project_internal_author');
		$projects = $db->nameQuote('#__jresearch_project');				
		$memberValue = $db->Quote($memberId);
		
		$query = "SELECT COUNT(*) FROM $internal_author pia, $projects p 
				 WHERE pia.id_project = p.id AND p.published = 1 
				 AND pia.id_staff_member = $memberValue";
		
		$db->setQuery($query);		
		
		return (int)$db->loadResult();
	}
	
	/**
	 * Returns an array with the n latest theses in which the member has collaborated.
	 * @param int $n
	 */
	function getLatestTheses($n = 0){
		$db = JFactory::getDBO();
		$latestThes = array();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);		
		
		$query = 'SELECT t.* FROM '.$db->nameQuote('#__jresearch_thesis_internal_author').' ia,  '
				 .$db->nameQuote('#__jresearch_thesis').' t WHERE '.$db->nameQuote('t').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_thesis')
				 .' AND t.published = '.$db->Quote('1').' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId)
				 .' ORDER BY '.$db->nameQuote('t').'.'.$db->nameQuote('start_date').' DESC, '.$db->nameQuote('t').'.'.$db->nameQuote('end_date').' DESC, t.'.$db->nameQuote('created').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $row){
			$thesis = JTable::getInstance('Thesis', 'JResearch');
			$thesis->bind($row);
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
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);
				
		$internal_author = $db->nameQuote('#__jresearch_thesis_internal_author');
		$theses = $db->nameQuote('#__jresearch_thesis');				
		$memberValue = $db->Quote($memberId);
		
		$query = "SELECT COUNT(*) FROM $internal_author pia, $theses p 
		WHERE pia.id_thesis = p.id AND p.published = 1 AND pia.id_staff_member = $memberValue";
		$db->setQuery($query);		

		return (int)$db->loadResult();
	}

}

?>