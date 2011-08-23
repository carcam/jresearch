<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Member_Positions
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'modelList.php');

/**
* Model class for holding lists of project records.
*
* @subpackage	Member_Positions
*/
class JResearchModelMember_positionList extends JResearchModelList
{
	/**
	* Class constructor.
	*/
	function __construct()
	{
		parent::__construct();
		$this->_tableName = '#__jresearch_member_position';
	}
	
	/**
	 * @see JResearchModelList::_buildQuery()
	 *
	 * @param unknown_type $memberId
	 * @param If $onlyPublished
	 * @param If $paginate
	 * @return string
	 */
	protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false)
	{
		$db =& JFactory::getDBO();		
		if($memberId === null)
		{		
			$resultQuery = 'SELECT * FROM '.$db->nameQuote($this->_tableName); 	
		}
		else
		{
			$resultQuery = '';
		}
		// Deal with pagination issues
		if($paginate)
		{
			$resultQuery .= $this->_buildQueryWhere($onlyPublished).' '.$this->_buildQueryOrderBy();
			$limit = (int)$this->getState('limit');
			
			if($limit != 0)
				$resultQuery .= ' LIMIT '.(int)$this->getState('limitstart').' , '.$this->getState('limit');		
		}
		
		return $resultQuery;
	}
	
	/**
	 * @see JResearchModelList::_buildCountQuery()
	 *
	 * @return string
	 */
	protected function _buildCountQuery()
	{
		$db =& JFactory::getDBO();
		$resultQuery = 'SELECT count(*) FROM '.$db->nameQuote($this->_tableName); 	
		$resultQuery .= $this->_buildQueryWhere($this->_onlyPublished).' '.$this->_buildQueryOrderBy();
		return $resultQuery;
	}
	
	/**
	 * @see JResearchModelList::getData()
	 *
	 * @param Id $memberId
	 * @param If $onlyPublished
	 * @param If $paginate
	 * @return array
	 */
	public function getData($memberId = null, $onlyPublished = false, $paginate = false)
	{
		if($memberId !== $this->_memberId || $onlyPublished !== $this->_onlyPublished || $this->_paginate !== $this->_paginate || empty($this->_items))
		{
			$this->_memberId = $memberId;
			$this->_onlyPublished = $onlyPublished;
			$this->_paginate = $paginate;					
			$this->_items = array();
			
			$db = &JFactory::getDBO();
			$query = $this->_buildQuery($memberId, $onlyPublished, $paginate);
	
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			$this->_items = array();	
			foreach($rows as $row)
			{
				$position = JTable::getInstance('Member_position', 'JResearch');
				$position->bind($row, array());
				$this->_items[] = $position;
			}
			if($paginate)
				$this->updatePagination();
		}
			
		return $this->_items;
	}

	private function _buildQueryWhere($published)
	{
		global $mainframe;
		
		$filter_state = $mainframe->getUserStateFromRequest('member_positionfilter_state', 'filter_state');
		$db = & JFactory::getDBO();
		
		$where = array();
		
		if(!$published)
		{
			if($filter_state == 'P')
				$where[] = $db->nameQuote('published').' = 1 ';
			elseif($filter_state == 'U')
				$where[] = $db->nameQuote('published').' = 0 '; 	
		}
		else
			$where[] = $db->nameQuote('published').' = 1 ';
			
		return (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
	}

	public function getPublishedPositions(){

		$db = JFactory::getDBO();
		$db->setQuery('SELECT * FROM #__jresearch_member_position WHERE published = 1 ORDER BY ordering ASC');
		$result = $db->loadAssocList();
		$positions = array();		
		foreach($result as $row){
			$position = JTable::getInstance('Member_position', 'JResearch');
			$position->bind($row);
			$positions[] = $position;
		}

		return $positions;	
	}
	
	private function _buildQueryOrderBy()
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		//Array of allowable order fields
		$orders = array('position', 'published', 'ordering');
		
		$filter_order = $mainframe->getUserStateFromRequest('member_positionfilter_order', 'filter_order', 'ordering');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('member_positionfilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
		//Validate order direction
		if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
			$filter_order_Dir = 'ASC';
		//if order column is unknown, use the default
		if(!in_array($filter_order, $orders))
			$filter_order = $db->nameQuote('ordering');	
		
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	}

	/**
	 * Ordering item
	*/
	function orderItem($item, $movement)
	{
		$db =& JFactory::getDBO();
		$row = JTable::getInstance('Member_position', 'JResearch');
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
		$row		= JTable::getInstance('Member_position', 'JResearch');

		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for( $i=0; $i < $total; $i++ )
		{
			$row->load( $items[$i] );
			
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

		return true;
	}
	
}
?>
