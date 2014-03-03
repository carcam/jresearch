<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the cooperations model.
*/

jimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'models'.'/'.'modelList.php');
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'tables'.'/'.'cooperation.php');
class JResearchModelCooperations extends JResearchModelList
{
    public function __construct()
    {
            parent::__construct();
            $this->_tableName = '#__jresearch_cooperations';
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
                $ids = $db->loadResultArray();
                $this->_items = array();

                foreach($ids as $id)
                {
                        $coop = new JResearchCooperation($db);
                        $coop->load($id);
                        $this->_items[] = $coop;
                }

                if($paginate)
                        $this->updatePagination();
        }

        return $this->_items;
    }

    /**
     * Returns categories for existing cooperations with id, title and image
     * @return array Keys are 'id', 'title' and 'image'
     */
    public function getCategories()
    {
        $db = JFactory::getDBO();

        //Select categories from existing cooperations
        $sql = 'SELECT DISTINCT jc.catid AS cid, title, image FROM '.$db->nameQuote('#__jresearch_cooperations').' AS jc LEFT JOIN '.$db->nameQuote('#__categories').' AS c ON jc.catid = c.id WHERE jc.catid != 0 AND jc.published=1';

        $db->setQuery($sql);
        return $db->loadObjectList();
    }

    /**
    * Returns the SQL used to get the data from publications table.
    *
    * @param $memberId Not used by this model class.
    * @param $onlyPublished If true, returns only published items.
    * @param $paginate If true, the method considers pagination user parameters
    *
    * @return string
    */
    protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false)
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
        $orders = array('name', 'published', 'ordering');
        $Itemid = JRequest::getVar('Itemid');
        $filter_order = $mainframe->getUserStateFromRequest('coopsfilter_order'.$Itemid, 'filter_order', 'ordering');
        $filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('coopsfilter_order_Dir'.$Itemid, 'filter_order_Dir', 'ASC'));

        //Validate order direction
        if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
                $filter_order_Dir = 'ASC';

        //if order column is unknown, use the default
        if(!in_array($filter_order, $orders))
                $filter_order = $db->nameQuote('ordering');

        return ' ORDER BY catid,'.$filter_order.' '.$filter_order_Dir;
    }

    /**
    * Build the WHERE part of a query
    */
    private function _buildQueryWhere($published = false){
        global $mainframe;
        $db = & JFactory::getDBO();
        $Itemid = JRequest::getVar('Itemid');
        $filter_state = $mainframe->getUserStateFromRequest('coopsfilter_state'.$Itemid, 'filter_state');
        $filter_search = $mainframe->getUserStateFromRequest('coopsfilter_search'.$Itemid, 'filter_search');
        $filter_category = $mainframe->getUserStateFromRequest('coopsfilter_category'.$Itemid, 'filter_category');

        // prepare the WHERE clause
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

        if($filter_category)
        {
                $where[] = $db->nameQuote('catid').' = '.$filter_category;
        }

        if(($filter_search = trim($filter_search)))
        {
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
    protected function _buildCountQuery(){
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
        $row = new JResearchCooperation($db);
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
        $row		= new JResearchCooperation($db);
        $groupings  = array();

        $order		= JRequest::getVar( 'order', array(), 'post', 'array' );
        JArrayHelper::toInteger($order);

        // update ordering values
        for( $i=0; $i < $total; $i++ )
        {
                $row->load( $items[$i] );
                $groupings[] = $row->catid;

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

        //Ordering for groups
        $groupings = array_unique($groupings);
        foreach($groupings as $group)
        {
                $row->reorder('catid='.$group);
        }

        return true;
    }
}
?>