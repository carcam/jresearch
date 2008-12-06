<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelList.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');

/**
* Model class for holding lists of members records.
*
*/
class JResearchModelStaff extends JResearchModelList{
	/**
	 * Private field for checking if we need all members,
	 * only former members or all members except former members
	 * @var int 0=all,1=only former, -1=all except former
	 * @todo NOT NEEDED IF WE MAKE A FORMER_MEMBER FILTERING VARIABLE!
	*/
	private $_former = 0;
	
	public function __construct(){
		parent::__construct();
		$this->_tableName = '#__jresearch_member';
		
	}	
	
	public function setFormer($former)
	{
		$this->_former = (int) $former;
	}
	
	/**
	* Returns an array of the items of an entity independently of its published state and
	* considering pagination issues and authoring.
	* backend functionality.
	* @param 	$memberId Id Not used by this model subclass.
	* @param 	$onlyPublished If true, only published records will be considered in the query
	* @param 	$paginate If true, user state information will be considered.
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
				$member = new JResearchMember($db);
				$member->load($id);
				$this->_items[] = $member;
			}
			
			if($paginate)
				$this->updatePagination();
		}		
			
		return $this->_items;

	}
	
	/**
	* Returns the SQL used to get the data from publications table.
	* 
	* @pàram $memberId Not used by this model class.
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	*
	* @return string
	*/
	protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false ){		
		$db =& JFactory::getDBO();		
		$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName);
		$resultQuery .= $this->_buildQueryWhere($onlyPublished).' '.$this->_buildQueryOrderBy(); 	
		
		// Deal with pagination issues
		if($paginate){
			$limit = (int)$this->getState('limit');
			if($limit != 0)
					$resultQuery .= ' LIMIT '.(int)$this->getState('limitstart').' , '.$this->getState('limit');
							
		}
		
		return $resultQuery;
	}
	
	/**
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy(){
		global $mainframe;
		$db =& JFactory::getDBO();
		//Array of allowable order fields
		$orders = array('lastname', 'published', 'id_research_area', 'ordering');
		
		$filter_order = $mainframe->getUserStateFromRequest('stafffilter_order', 'filter_order', 'ordering');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('stafffilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
		//Validate order direction
		if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
			$filter_order_Dir = 'ASC';
			
		//if order column is unknown, use the default
		if(!in_array($filter_order, $orders))
			$filter_order = $db->nameQuote('ordering');	
		
		return ' ORDER BY former_member ASC, '.$filter_order.' '.$filter_order_Dir;
	}	
	
		/**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere($published = false){
		global $mainframe, $option;
		$db = & JFactory::getDBO();
		$filter_state = $mainframe->getUserStateFromRequest('stafffilter_state', 'filter_state');
		$filter_search = $mainframe->getUserStateFromRequest('stafffilter_search', 'filter_search');

		
		// prepare the WHERE clause
		$where = array();
		
		if(!$published){
			if($filter_state == 'P')
				$where[] = $db->nameQuote('published').' = 1 ';
			elseif($filter_state == 'U')
				$where[] = $db->nameQuote('published').' = 0 '; 	
		}else
			$where[] = $db->nameQuote('published').' = 1 ';		

		//Added former member for where clause
		if($this->_former != 0)
		{
			if($this->_former > 0)
				$where[] = $db->nameQuote('former_member').' = 1 ';
			elseif($this->_former < 0)
				$where[] = $db->nameQuote('former_member').' = 0 ';
		}
			
		if($filter_search = trim($filter_search)){
			$filter_search = JString::strtolower($filter_search);
			$filter_search = $db->getEscaped($filter_search);
			$where[] = 'LOWER('.$db->nameQuote('lastname').') LIKE '.$db->Quote('%'.$filter_search.'%');
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

	/**
	 * Ordering item
	*/
	function orderItem($item, $movement)
	{
		$db =& JFactory::getDBO();
		$row =& new JResearchMember($db);
		$row->load($item);
		
		if (!$row->move($movement))
		{
			$this->setError($row->getError());
			return false;
		}

		return true;
	}
	
	/**
	 * Set ordering
	*/
	function setOrder($items)
	{
		$db 		=& JFactory::getDBO();
		$total		= count($items);
		$row		=& new JResearchMember($db);

		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for( $i=0; $i < $total; $i++ )
		{
			$row->load( $items[$i] );
			
			$groupings[] = $row->former_member;
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store())
				{
					$this->setError($row->getError());
					return false;
				}
			} // if
		} // for

		// execute updateOrder
		$groupings = array_unique($groupings);
		foreach ($groupings as $group)
		{
			$row->reorder('former_member = '.(int) $group.' AND published >=0');
		}

		return true;
	}
}
?>
