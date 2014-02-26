<?php
/**
* @package		JResearch
* @subpackage	Frontend.Models
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

jresearchimport('models.modelitem', 'jresearch.site');

/**
* Model class for holding a single project record.
*
*/
class JResearchModelProject extends JResearchModelItem{
	
    /**
     * Returns the model data store in the user state as a table
     * object
     */
    public function getItem(){
        if(!isset($this->_row)){
            $row = $this->getTable('Project', 'JResearch');
             if($row->load(JRequest::getInt('id'))){
                 if($row->published)
                     $this->_row = $row;
                else
                    return false;
            }else
                return false;                
         }

        return $this->_row;
    }

	/**
	 * Returns an array with the n latest publications associated to the
	 * project.
	 *
	 * @param int $n
	 * @return array Array of JResearchPublicationObjects
	 */
	function getPublications($n = 0){
    	$latestPub = array();
        $row = $this->getItem();
        if($row === false)
        	return $latestPub;

        $db = JFactory::getDBO();

        $query = "SELECT p.* FROM ".$db->nameQuote('#__jresearch_publication').' p JOIN '.$db->nameQuote('#__jresearch_project_publication').' pp'
            		.' WHERE p.id = pp.id_publication AND p.published = 1 AND p.internal = 1 AND pp.id_project = '.$db->Quote($row->id)
            		.' ORDER BY p.year DESC, p.created DESC';

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

        $db = JFactory::getDBO();
        $query = "SELECT count(pp.id_publication) FROM ".$db->nameQuote('#__jresearch_publication').' p JOIN '.$db->nameQuote('#__jresearch_project_publication').' pp'
        .' WHERE p.id = pp.id_publication AND p.published = 1 AND p.internal = 1 AND pp.id_project = '.$db->Quote($row->id);
        
        $db->setQuery($query);
        return (int)$db->loadResult();
	}    
    
}
?>