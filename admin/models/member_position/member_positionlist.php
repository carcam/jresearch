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
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member_position.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelList.php');

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
	 * @see JResearchModelList::_countTotalItems()
	 *
	 * @return string
	 */
	protected function _countTotalItems()
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
				$position->bind($row);
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
		$db = JFactory::getDBO();
		
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
	
	private function _buildQueryOrderBy()
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		//Array of allowable order fields
		$orders = array('position', 'published');
		
		$filter_order = $mainframe->getUserStateFromRequest('member_positionfilter_order', 'filter_order', 'position');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('member_positionfilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
		//Validate order direction
		if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
			$filter_order_Dir = 'ASC';
		//if order column is unknown, use the default
		if(!in_array($filter_order, $orders))
			$filter_order = $db->nameQuote('position');	
		
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	}
}
?>