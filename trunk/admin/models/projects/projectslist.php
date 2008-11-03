<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'project.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelList.php');

/**
* Model class for holding lists of project records.
*
* @subpackage	Projects
*/
class JResearchModelProjectsList extends JResearchModelList{
	private $_ids = array();
	
	/**
	* Class constructor.
	*/
	function __construct(){
		parent::__construct();
		$this->_tableName = '#__jresearch_project';
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
		$db =& JFactory::getDBO();		
		if($memberId === null){		
			$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName); 	
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
	protected function _buildRawQuery(){
		$db =& JFactory::getDBO();
		$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName); 	
		$resultQuery .= $this->_buildQueryWhere($this->_onlyPublished).' '.$this->_buildQueryOrderBy();
		return $resultQuery;
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
			$ids = $db->loadResultArray();
			$this->_items = array();			
			foreach($ids as $id){
				$proj = new JResearchProject($db);
				$proj->load($id);
				$this->_items[] = $proj;
			}
			if($paginate)
				$this->updatePagination();
		}			
		return $this->_items;

	}

	/**
	* Returns an associative array with the information of all members and external authors.
	* @return array
	*/
	function getAllAuthors(){
		$db = JFactory::getDBO();
		$query = 'SELECT DISTINCT '.$db->nameQuote('author_name').' as id, '.$db->nameQuote('author_name').' as name FROM '.$db->nameQuote('#__jresearch_project_external_author').' UNION SELECT id, CONCAT_WS( \' \', firstname, lastname ) as name FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('published').' = '.$db->Quote('1');
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	
	/**
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy(){
		global $mainframe;
		$db =& JFactory::getDBO();
		//Array of allowable order fields
		$orders = array('title', 'published', 'start_date', 'id_research_area');
		
		$filter_order = $mainframe->getUserStateFromRequest('projectsfilter_order', 'filter_order', 'title');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('projectsfilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
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
		$db = & JFactory::getDBO();
		$filter_state = $mainframe->getUserStateFromRequest('projectsfilter_state', 'filter_state');
		$filter_search = $mainframe->getUserStateFromRequest('projectsfilter_search', 'filter_search');
		$filter_author = $mainframe->getUserStateFromRequest('projectsfilter_author', 'filter_author');		
		// prepare the WHERE clause
		$where = array();
		
		if(!$published){
			if($filter_state == 'P')
				$where[] = $db->nameQuote('published').' = 1 ';
			elseif($filter_state == 'U')
				$where[] = $db->nameQuote('published').' = 0 '; 	
		}else
			$where[] = $db->nameQuote('published').' = 1 ';
				
		if(!empty($filter_author)){
			$ids = $this->_getAuthorProjectsIds(trim($filter_author));			
			if(count($ids) > 0)
				$where[] = $db->nameQuote('id').' IN ('.implode(',', $ids).')';
			else
				$where[] = '0 = 1';
		}
		
		//Get id's
		if(count($this->_ids) > 0)
		{
			$orWhere = array();
			$allProjects = false;	//Boolean variable for checking if all projects will be returned
			
			foreach($this->_ids as $id)
			{
				if($id != 0)
				{
					$orWhere[] = $db->nameQuote('id').' = '.$id;
				}
				else 
				{
					//Id 0 indicateds to display all projects
					$allProjects = true;
					break;
				}
			}
			
			if(!$allProjects)
				$where[] = (count($where)) ? ' '.implode(' OR ', $orWhere).' ' : '';
		}
					
		if($filter_search = trim($filter_search)){
			$filter_search = JString::strtolower($filter_search);
			$filter_search = $db->getEscaped($filter_search);
			$where[] = 'LOWER('.$db->nameQuote('title').') LIKE '.$db->Quote('%'.$filter_search.'%');
		}
		
		return (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
			
	}

	/**
	* Returns the ids of the projects where the member has participated. 
	* @param $author Integer database id or author name depending if the collaborator is member
	* of the center or not.
	*/
	private function _getAuthorProjectsIds($author){
		$db = JFactory::getDBO();
		if(is_numeric($author)){
			$query = 'SELECT '.$db->nameQuote('id_project').' FROM '.$db->nameQuote('#__jresearch_project_internal_author').' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($author);
		}else{
			$query = 'SELECT '.$db->nameQuote('id_project').' FROM '.$db->nameQuote('#__jresearch_project_external_author').' WHERE '.$db->nameQuote('author_name').' LIKE '.$db->Quote($author);
		}
		$db->setQuery($query);
		return $db->loadResultArray();
	}

	/**
	 * Sets id's for specific list of projects
	 *
	 * @param array $ids Array of project id's
	 * @return bool Settings is successful or not
	 */
	public function setIds($ids)
	{
		if(is_array($ids))
		{
			$this->_ids = array();
			
			foreach($ids as $id)
			{
				$this->_ids[] = (int) $id;
			}
			
			return true;
		}
		
		return false;
	}
}
?>
