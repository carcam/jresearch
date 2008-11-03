<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	MtM
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the members of the month model
*/
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'mdm.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelList.php');

/**
* Model class for holding lists of mdm records.
*
* @subpackage		JResearch
*/
class JResearchModelMdmList extends JResearchModelList
{
	/**
	* Class constructor.
	*/
	function __construct()
	{
		parent::__construct();
		$this->_tableName = '#__jresearch_mdm';
	}
	
	/**
	* Returns the SQL used to get the data from projects table.
	* 
	* @param $memberId If non null, it represents the id of a staff member and the method returns
	* only those items of the member's authoring.
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	*
	* @return string
	*/
	protected function _buildQuery($memberId=null, $onlyPublished = false, $paginate = false )
	{		
		$db =& JFactory::getDBO();
				
		$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName); 	
		
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

	protected function _buildRawQuery()
	{
		$db =& JFactory::getDBO();
		
		$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName); 	
		$resultQuery .= $this->_buildQueryWhere($this->_onlyPublished).' '.$this->_buildQueryOrderBy();
		
		return $resultQuery;
	}
		
	/**
	* Returns an array of ALL the items of an entity independently of its published state considering
	* pagination parameters. 
	*
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	* @return 	array
	*/
	public function getData($memberId = null, $onlyPublished = false, $paginate = false)
	{
		if($onlyPublished !== $this->_onlyPublished || $this->_paginate !== $this->_paginate || empty($this->_items))
		{
			$this->_onlyPublished = $onlyPublished;
			$this->_paginate = $paginate;					
			$this->_items = array();
			
			$db = &JFactory::getDBO();
			$query = $this->_buildQuery(null, $onlyPublished, $paginate);
	
			$db->setQuery($query);
			$ids = $db->loadResultArray();
			
			$this->_items = array();
			
			if(count($ids) > 0)
				foreach($ids as $id)
				{
					$mdm = new JResearchMdm($db);
					$mdm->load($id);
					$this->_items[] = $mdm;
				}
			
			if($paginate)
				$this->updatePagination();
		}	
				
		return $this->_items;
	}
	
	/**
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy()
	{
		global $mainframe;
		
		$db =& JFactory::getDBO();
		
		//Array of allowable order fields
		$orders = array('month', 'published');
		
		$filter_order = $mainframe->getUserStateFromRequest('mdmfilter_order', 'filter_order', 'month');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('mdmfilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
		//Validate order direction
		if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
			$filter_order_Dir = 'ASC';
			
		//if order column is unknown, use the default
		if(!in_array($filter_order, $orders))
			$filter_order = $db->nameQuote('published');	
		
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	}	
	
	/**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere($published = false)
	{
		global $mainframe;
		
		$db = & JFactory::getDBO();
		
		// prepare the WHERE clause
		$where = array();
		
		if(!$published)
		{
			$filter_state = $mainframe->getUserStateFromRequest('mdmfilter_state','filter_state');
			
			if($filter_state == 'P')
				$where[] = $db->nameQuote('published').' = 1 ';
			elseif($filter_state == 'U')
				$where[] = $db->nameQuote('published').' = 0 '; 	
		}
		else
			$where[] = $db->nameQuote('published').' = 1 ';
				
		
		return (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
	}
}
?>