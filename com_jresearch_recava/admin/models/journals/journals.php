<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Financiers
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'financier.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelList.php');

/**
* Model class for holding lists of research areas records.
*
*/
class JResearchModelJournals extends JResearchModelList
{
	
	/**
	* Class constructor
	*/	
	function __construct(){
		parent::__construct();
		$this->_tableName = '#__jresearch_journals';
	}
	
	/**
	* Returns an array of ALL the items of an entity independently of its published state. Useful for the 
	* backend functionality.
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
			$result = $db->loadAssocList();

			$this->_items = array();
			foreach($result as $row){				
				$journal = JTable::getInstance('Journal', 'JResearch');
				$journal->bind($row);
				$this->_items[] = $journal;
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
	protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false){
		$db =& JFactory::getDBO();		
		
		$resultQuery = 'SELECT * FROM '.$db->nameQuote($this->_tableName); 
		
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
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy(){
		global $mainframe;
		$db =& JFactory::getDBO();
		//Array of allowable order fields
		$orders = array('title', 'published');
		
		$filter_order = $mainframe->getUserStateFromRequest('journalsfilter_order', 'filter_order', 'title');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('journalsfilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
		//Validate order direction
		if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
			$filter_order_Dir = 'ASC';
			
		//if order column is unknown, use the default
		if(!in_array($filter_order, $orders))
			$filter_order = $db->nameQuote('title');	
		
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	}	
	
		/**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere($published = false){
		global $mainframe;
		$db = & JFactory::getDBO();
		$filter_state = $mainframe->getUserStateFromRequest('journalsfilter_state', 'filter_state');
		$filter_search = $mainframe->getUserStateFromRequest('journalsfilter_search', 'filter_search');
		
		// prepare the WHERE clause
		$where = array();
		
		if(!$published){
			if($filter_state == 'P')
				$where[] = $db->nameQuote('published').' = 1 ';
			elseif($filter_state == 'U')
				$where[] = $db->nameQuote('published').' = 0 '; 	
		}else
			$where[] = $db->nameQuote('published').' = 1 AND '.$db->nameQuote('id').' > 1 ';		
					
		if(($filter_search = trim($filter_search))){
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
}
?>