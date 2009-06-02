<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Teams
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelList.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'team.php');

/**
* Model class for holding lists of teams records.
*
*/
class JResearchModelTeams extends JResearchModelList
{
	public function __construct()
	{
		parent::__construct();
		$this->_tableName = '#__jresearch_team';
	}
	
	/**
	* Returns an array of the items of an entity independently of its published state and
	* considering pagination issues.
	* @param 	$memberId Id Not used by this model subclass.
	* @param 	$onlyPublished If true, only published records will be considered in the query
	* @param 	$paginate If true, user state information will be considered.
	* @return 	array
	*/
	public function getData($memberId = null, $onlyPublished = false, $paginate = false)
	{
		if($memberId !== $this->_memberId || $onlyPublished !== $this->_onlyPublished || $this->_paginate !== $this->_paginate || empty($this->_items)){
			$this->_memberId = $memberId;
			$this->_onlyPublished = $onlyPublished;
			$this->_paginate = $paginate;					
			$this->_items = array();
			
			$db = &JFactory::getDBO();
			$query = $this->_buildQuery($memberId, $onlyPublished, $paginate);
	
			$db->setQuery($query);
			$ids = $db->loadResultArray();
			
			foreach($ids as $id)
			{				
				$team = new JResearchTeam($db);
				$team->load($id);
				$this->_items[] = $team;
			}
			
			if($paginate)
				$this->updatePagination();
		}		
			
		return $this->_items;

	}
	
	/**
	 * Gets hierarchical structure of teams
	 *
	 * @param int $memberId
	 * @param bool $onlyPublished
	 * @param bool $paginate
	 * @return array 
	 */
	public function getHierarchical($onlyPublished = false, $paginate = false)
	{
		//Set items
		$this->getData(null, $onlyPublished, $paginate);
		$children = array();
		
		foreach($this->_items as $row)
		{
			$pointer = $row->parent;
			$list = @$children[$pointer] ? $children[$pointer] : array();
			array_push($list, $row);
			$children[$pointer] = $list;
		}
		
		return $children;
	}
	
	
	/**
	* Returns the SQL used to get the data from teams table.
	* 
	* @param $memberId Not used by this model class.
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	*
	* @return string
	*/
	protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false )
	{		
		$db =& JFactory::getDBO();		
		$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName);
		$resultQuery .= $this->_buildQueryWhere($onlyPublished).' '.$this->_buildQueryOrderBy(); 	
		
		// Deal with pagination issues
		if($paginate)
		{
			$limit = (int)$this->getState('limit');
			if($limit != 0)
					$resultQuery .= ' LIMIT '.(int)$this->getState('limitstart').' , '.$this->getState('limit');
							
		}
		
		return $resultQuery;
	}

	/**
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy()
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		//Array of allowable order fields
		$orders = array('name', 'published', 'ordering', 'parent');
		
		$filter_order = $mainframe->getUserStateFromRequest('teamfilter_order', 'filter_order', 'name');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('teamfilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
		//Validate order direction
		if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
			$filter_order_Dir = 'ASC';
			
		//if order column is unknown, use the default
		if(!in_array($filter_order, $orders))
			$filter_order = $db->nameQuote('name');	
		
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	}	
	
	/**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere($published = false){
		global $mainframe, $option;
		$db = & JFactory::getDBO();
		$filter_state = $mainframe->getUserStateFromRequest('teamfilter_state', 'filter_state');
		$filter_search = $mainframe->getUserStateFromRequest('teamfilter_search', 'filter_search');
		
		// prepare the WHERE clause
		$where = array();
		
		if(!$published){
			if($filter_state == 'P')
				$where[] = $db->nameQuote('published').' = 1 ';
			elseif($filter_state == 'U')
				$where[] = $db->nameQuote('published').' = 0 '; 	
		}else
			$where[] = $db->nameQuote('published').' = 1 ';		
			
		if(($filter_search = trim($filter_search)))
		{
			$filter_search = JString::strtolower($filter_search);
			$filter_search = $db->getEscaped($filter_search);
			$where[] = 'LOWER('.$db->nameQuote('name').') LIKE '.$db->Quote('%'.$filter_search.'%');
		}
		
		return (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
			
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
	
//	/**
//	 * Ordering item
//	*/
//	function orderItem($item, $movement)
//	{
//		$db =& JFactory::getDBO();
//		$row = new JResearchMember($db);
//		$row->load($item);
//		
//		if (!$row->move($movement))
//		{
//			$this->setError($row->getError());
//			return false;
//		}
//
//		return true;
//	}
//	
//	/**
//	 * Set ordering
//	*/
//	function setOrder($items)
//	{
//		$db 		=& JFactory::getDBO();
//		$total		= count($items);
//		$row		= new JResearchMember($db);
//
//		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
//		JArrayHelper::toInteger($order);
//
//		// update ordering values
//		for( $i=0; $i < $total; $i++ )
//		{
//			$row->load( $items[$i] );
//			
//			$groupings[] = $row->former_member;
//			if ($row->ordering != $order[$i])
//			{
//				$row->ordering = $order[$i];
//				if (!$row->store())
//				{
//					$this->setError($row->getError());
//					return false;
//				}
//			} // if
//		} // for
//
//		// execute updateOrder
//		$groupings = array_unique($groupings);
//		foreach ($groupings as $group)
//		{
//			$row->reorder('former_member = '.(int) $group.' AND published >=0');
//		}
//
//		return true;
//	}
}
?>