<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'modelList.php');


/**
* Model class for holding lists of thesis records.
*
*/
class JResearchModelThesesList extends JResearchModelList{
	
	/**
	* Class constructor.
	*/
	function __construct(){
		parent::__construct();
		$this->_tableName = '#__jresearch_thesis';
	}

	/**
	* Returns the SQL used to get the data from projects table.
	* 
	* @pàram $memberId If non null, it represents the id of a staff member and the method returns
	* only those items of the member's authoring.
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	*
	* @return string
	*/
	protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false ){		
		$db = JFactory::getDBO();		
		if($memberId === null){		
			$resultQuery = 'SELECT * FROM '.$db->nameQuote($this->_tableName);
		}else{
			$resultQuery = '';
		}
		// Deal with pagination issues
		if($paginate){
			$resultQuery .= $this->_buildQueryWhere($onlyPublished).' '.$this->_buildQueryOrderBy();
			$limit = (int)$this->getState('limit');
			if($limit != 0)
				$resultQuery .= ' LIMIT '.(int)$this->getState('limitstart').' , '.$this->getState('limit');
							
		}
		
		return $resultQuery;
	}


	/**
	* Like method _buildQuery, but it does not consider LIMIT clause.
	* 
	* @return string SQL query.
	*/	
	protected function _buildCountQuery(){
		$db =& JFactory::getDBO();
		$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName); 	
		$resultQuery .= $this->_buildQueryWhere($this->_onlyPublished).' '.$this->_buildQueryOrderBy();
		return $resultQuery;
	}

	private function _getTeamThesisIds($teamId, $count=0)
	{
		$db = JFactory::getDBO();
		
		$query = 'SELECT id FROM '.$db->nameQuote('#__jresearch_thesis').' WHERE '.$db->nameQuote('published').' = '.$db->Quote(1);
		$query .= ' AND '.$db->nameQuote('id_team').' = '.$db->Quote($teamId).' ORDER BY start_date DESC';
				 
		if($count > 0)
		{
			$query .= " LIMIT 0, $count";
		}
				
		$db->setQuery($query);
		return $db->loadResultArray();
		
	}
	
	/**
	* Returns an associative array with the information of all members and external authors.
	* @return array
	*/
	function getAllAuthors(){
		$db = JFactory::getDBO();
		$query = 'SELECT DISTINCT '.$db->nameQuote('author_name').' as id, '.$db->nameQuote('author_name').' as name FROM '.$db->nameQuote('#__jresearch_thesis_external_author').' UNION SELECT id, CONCAT_WS( \' \', firstname, lastname ) as name FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('published').' = '.$db->Quote('1');
		$db->setQuery($query);
		return $db->loadAssocList();
	}


		
	/**
	* Returns an array of ALL the items of an entity independently of its published state considering
	* pagination parameters. 
	*
	* @pàram $memberId If non null, it represents the id of a staff member and the method returns
	* only those items of the member's authoring.
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	* @return 	array
	*/
	public function getData($memberId = null, $onlyPublished = false, $paginate = false){
	
		if($memberId !== $this->_memberId || $onlyPublished !== $this->_onlyPublished || $this->_paginate !== $this->_paginate || empty($this->_items)){
			$this->_memberId = $memberId;
			$this->_onlyPublished = $onlyPublished;
			$this->_paginate = $paginate;					
			$this->_items = array();
			
			$db = &JFactory::getDBO();
			$query = $this->_buildQuery($memberId, $onlyPublished, $paginate);
	
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			$this->_items = array();
			foreach($rows as $row){
                $thesis = JTable::getInstance('Thesis', 'JResearch');
                $thesis->bind($row, array(), true);
                $this->_items[] = $thesis;
			}
			if($paginate)
				$this->updatePagination();
		}			
		return $this->_items;

	}
	
	/**
	 * Gets data by team id
	 *
	 * @param int $teamId
	 * @return array
	 */
	public function getDataByTeamId($teamId, $count=0)
	{
		$model = JModel::getInstance('Team', 'JResearchModel');
		$team = $model->getItem($teamId);
		$theses = array();
		$db = JFactory::getDBO();
		
		if(!empty($team))
		{
			$ids = $this->_getTeamThesisIds($team->id, intval($count));
			
			foreach($ids as $id)
			{
				$thesis = JTable::getInstance('Thesis', 'JResearch');
				$thesis->load($id);
				$theses[] = $thesis;
			}
		}
		
		return $theses;
	}
	
	/**
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy(){
		global $mainframe;
		$db = JFactory::getDBO();
		//Array of allowable order fields
		$orders = array('title', 'published', 'id_research_area', 'start_date', 'degree');
		
		$filter_order = $mainframe->getUserStateFromRequest('thesesfilter_order', 'filter_order', 'title');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('thesesfilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
		//Validate order direction
		if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
			$filter_order_Dir = 'ASC';
		//if order column is unknown, use the default
		if(!in_array($filter_order, $orders))
			$filter_order = $db->nameQuote('published');	
		
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', '.$db->nameQuote('created').' DESC';
	}	
	
	/**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere($published = false){
            	global $mainframe;
            	$db = JFactory::getDBO();
            	$Itemid = JRequest::getVar('Itemid');
            	$filter_state = $mainframe->getUserStateFromRequest('thesesfilter_state'.$Itemid, 'filter_state');
            	$filter_status = $mainframe->getUserStateFromRequest('thesesfilter_status'.$Itemid, 'filter_status');
            	$filter_search = $mainframe->getUserStateFromRequest('thesesfilter_search'.$Itemid, 'filter_search');
            	$filter_author = $mainframe->getUserStateFromRequest('thesesfilter_author'.$Itemid, 'filter_author');
            	$filter_degree = $mainframe->getUserStateFromRequest('thesesfilter_degree'.$Itemid, 'filter_degree');
		$filter_area = $mainframe->getUserStateFromRequest('thesesfilter_area'.$Itemid, 'filter_area');
		
		// prepare the WHERE clause
		$where = array();
		
		if(!$published){
			if($filter_state == 'P')
				$where[] = $db->nameQuote('published').' = 1 ';
			elseif($filter_state == 'U')
				$where[] = $db->nameQuote('published').' = 0 '; 	
		}else
			$where[] = $db->nameQuote('published').' = 1 ';

		if(!empty($filter_author) && $filter_author != -1){
			$ids = $this->_getAuthorThesesIds(trim($filter_author));			
			if(count($ids) > 0)
				$where[] = $db->nameQuote('id').' IN ('.implode(',', $ids).')';
			else
				$where[] = '0 = 1';
		}
				
					
		if(($filter_search = trim($filter_search))){
			$filter_search = JString::strtolower($filter_search);
			$filter_search = $db->getEscaped($filter_search);
			$where[] = 'LOWER('.$db->nameQuote('title').') LIKE '.$db->Quote('%'.$filter_search.'%');
		}

                if(!empty($filter_degree)){
                    $where[] = $db->nameQuote('degree').' = '.$db->Quote($filter_degree);
                }

                if(!empty($filter_status)){
                    $where[] = $db->nameQuote('status').' = '.$db->Quote($filter_status);
                }
                
                if(!empty($filter_area)){
                    $where[] = $db->nameQuote('id_research_area').' = '.$db->Quote($filter_area);	
                }
		
		return (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
			
	}

	/**
	* Returns the ids of the projects where the member has participated. 
	* @param $author Integer database id or author name depending if the collaborator is member
	* of the center or not.
	*/
	private function _getAuthorThesesIds($author){
		$db = JFactory::getDBO();
		if(is_numeric($author)){
			$query = 'SELECT '.$db->nameQuote('id_thesis').' FROM '.$db->nameQuote('#__jresearch_thesis_internal_author').' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($author);
		}else{
			$query = 'SELECT '.$db->nameQuote('id_thesis').' FROM '.$db->nameQuote('#__jresearch_thesis_external_author').' WHERE '.$db->nameQuote('author_name').' LIKE '.$db->Quote($author);
		}
		$db->setQuery($query);
		return $db->loadResultArray();
	}
	
}
?>
