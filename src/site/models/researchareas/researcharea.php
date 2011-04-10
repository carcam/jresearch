<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport('models.modelitem', 'jresearch.site');

/**
* Model class for holding a single research area record.
*
*/
class JResearchModelResearchArea extends JResearchModelItem{
    
        /**
         * Returns the model data store in the user state as a table
         * object
         */
        public function getItem(){
            if(!isset($this->_row)){
                $row = $this->getTable('Researcharea', 'JResearch');
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
	 * Returns the staff members that work in a specific research
	 * area.
	 *
	 * @param int $id_area Research area id
	 *
	 */
	public function getStaffMembers(){
            $members = array();
            $row = $this->getItem();
            if($row === false)
                return $members;

            $id_area = $row->id;            
            $db = JFactory::getDBO();
            $query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').'WHERE '.$db->nameQuote('published').' = 1'
                             .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($id_area).' ORDER BY '.$db->nameQuote('ordering').' ASC';

            $db->setQuery($query);
            $result = $db->loadAssocList();

            foreach($result as $r){
                    $newMember = JTable::getInstance('Member', 'JResearch');
                    $newMember->bind($r);
                    $members[] = $newMember;
            }

            return $members;

	}


	/**
	 * Returns an array with the n latest publications associated to the
	 * research area.
	 *
	 * @param int $n
	 * @return array Array of JResearchPublicationObjects
	 */
	function getLatestPublications($n = 0){
            $latestPub = array();
            $row = $this->getItem();
            if($row === false)
                return $latestPub;

            $areaId = $row->id;
            $db = JFactory::getDBO();

            $query = "SELECT p.* FROM ".$db->nameQuote('#__jresearch_publication').' p JOIN '.$db->nameQuote('#__jresearch_publication_researcharea').' pa'
            		.' WHERE p.id = pa.id_publication AND p.published = 1 AND p.internal = 1 AND pa.id_research_area = '.$db->Quote($areaId)
            		.' ORDER BY year DESC, created DESC';

            if($n > 0){
                    $query .= ' LIMIT 0, '.$n;
            }

            $db->setQuery($query);
            $result = $db->loadAssocList();
            foreach($result as $r){
                $publication = JTable::getInstance('Publication', 'JResearch');
                $publication->bind($r);
                $latestPub[] = $publication;
            }

            return $latestPub;
	}


	/**
	 * Returns the number of publications where the member has participated.
	 *
	 * @param int $memberId
	 */
	function countPublications(){
            $row = $this->getItem();
            if($row === false)
                return -1;

            $areaId = $row->id;

            $db = JFactory::getDBO();
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
	function getLatestProjects($n = 0){
            $latestProj = array();
            $row = $this->getItem();
            if($row === false)
                return $latestProj;

            $areaId = $row->id;
            $db = JFactory::getDBO();
            $query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_project').' WHERE '.$db->nameQuote('published').' = 1'
                            .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY start_date DESC, created DESC';

            if($n > 0){
                    $query .= ' LIMIT 0, '.$n;
            }

            $db->setQuery($query);
            $result = $db->loadAssocList();
            foreach($result as $r){
                $project = JTable::getInstance('Project', 'JResearch');
                $project->bind($r);
                $latestProj[] = $project;
            }

            return $latestProj;
	}


	/**
	 * Returns the number of projects the member has participated.
	 * @param int $areaId
	 */
	function countProjects(){
            $row = $this->getItem();
            if($row === false)
                return -1;

            $areaId = $row->id;
            $db = JFactory::getDBO();

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
	function getLatestTheses($n = 0){
            $latestThes = array();
            $row = $this->getItem();
            if($row === false)
                return $latestThes;

            $areaId = $row->id;

            $db = JFactory::getDBO();
            $query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_thesis').' WHERE '.$db->nameQuote('published').' = 1'
                            .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY start_date DESC, created DESC';

            if($n > 0){
                    $query .= ' LIMIT 0, '.$n;
            }

            $db->setQuery($query);
            $result = $db->loadAssocList();
            foreach($result as $r){
                $thesis = JTable::getInstance('Thesis', 'JResearch');
                $thesis->bind($r);
                $latestThes[] = $thesis;
            }

            return $latestThes;
	}



	/**
	 * Returns the number of degree theses associated to the area.
	 * @param int $areaId
	 */
	function countTheses(){
            $row = $this->getItem();
            if($row === false)
                return -1;

            $db = JFactory::getDBO();

            $query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_thesis').' WHERE '.$db->nameQuote('published').' =  1'
                            .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId);
            $db->setQuery($query);
            return (int)$db->loadResult();

	}


	public function getFacilities($n=0)
	{
            $facilities = array();
            $row = $this->getItem();
            if($row === false)
                return $facilities;

            $areaId = $row->id;
            $db = JFactory::getDBO();

            $query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_facilities').' WHERE '.$db->nameQuote('published').' = 1'
                            .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY name DESC';

            if($n > 0){
                $query .= ' LIMIT 0, '.$n;
            }

            $db->setQuery($query);
            $result = $db->loadAssocList();
            foreach($result as $r){
                $item = JTable::getInstance('Facility', 'JResearch');
                $item->bind($r);
                $facilities[] = $item;
            }

            return $facilities;
	}
}
?>