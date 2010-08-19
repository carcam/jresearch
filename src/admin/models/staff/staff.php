<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelList.php');

/**
* Model class for holding lists of members records.
*
*/
class JResearchAdminModelStaff extends JResearchAdminModelList{
		

      public function getItems(){
            if(!isset($this->_items)){
                $items = parent::getItems();

                if($items !== false){
                    foreach($items as $item){
                        $member = $this->getTable('Member', 'JResearch');
                        $area->bind($item);
                        $this->_items[] = $member;
                    }
                }else{
                    return $items;
                }
            }

            return $this->_items;
        }


        protected function getListQuery() {
            // Create a new query object.
            $db = JFactory::getDBO();
            $whereClauses = $this->_buildQueryWhere();
            $orderColumns = $this->_buildQueryOrderBy();
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from('#__jresearch_member');
            if(!empty($whereClauses))
                $query->where($whereClauses);

            $query->order($orderColumns);
            return $query;
        }


	/**
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy(){
            //Array of allowable order fields
            $mainframe = JFactory::getApplication();
            $orders = array('lastname', 'published', 'ordering');
            $columns = array();

            $filter_order = $mainframe->getUserStateFromRequest('com_jresearch.staff.filter_order', 'filter_order', 'ordering');
            $filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('com_jresearch.staff.filter_order_Dir', 'filter_order_Dir', 'ASC'));

            //Validate order direction
            if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
                    $filter_order_Dir = 'ASC';

            $columns[] = $filter_order.' '.$filter_order_Dir;

            return $columns;
	}

        /**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere(){
            $db = JFactory::getDBO();
            $mainframe = JFactory::getApplication();
            $filter_state = $mainframe->getUserStateFromRequest('com_jresearch.staff.filter_state', 'filter_state');
            $filter_search = $mainframe->getUserStateFromRequest('com_jresearch.staff.filter_search', 'filter_search');
            $filter_former_member = $mainframe->getUserStateFromRequest('com_jresearch.staff.filter_former_member', 'filter_former_member');

            // prepare the WHERE clause
            $where = array();

            if($filter_state == 'P')
                $where[] = $db->nameQuote('published').' = 1 ';
            elseif($filter_state == 'U')
                $where[] = $db->nameQuote('published').' = 0 ';

            if(($filter_search = trim($filter_search))){
                $filter_search = JString::strtolower($filter_search);
                $filter_search = $db->getEscaped($filter_search);
                $where[] = 'LOWER('.$db->nameQuote('name').') LIKE '.$db->Quote('%'.$filter_search.'%');
            }

            //Added former member for where clause
            if($filter_former != 0)
            {
                if($filter_former > 0)
                        $where[] = $db->nameQuote('former_member').' = 1 ';
                elseif($filter_former < 0)
                        $where[] = $db->nameQuote('former_member').' = 0 ';
            }


            return $where;
	}
	/**
	 * Ordering item
	*/
	function orderItem($item, $movement)
	{
		$db =& JFactory::getDBO();
		$row = new JResearchMember($db);
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
		$row		= new JResearchMember($db);

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
