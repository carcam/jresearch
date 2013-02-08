<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jresearchimport('models.modelList', 'jresearch.admin');

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
                        $member->bind($item);
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
            $orders = array('lastname', 'published', 'ordering', 'former_member');
            $columns = array();

            $filter_order = $this->getState($this->_context.'.filter_order');
            $filter_order_Dir = $this->getState($this->_context.'.filter_order_Dir');

            //Validate order direction
            if($filter_order_Dir != 'asc' && $filter_order_Dir != 'desc')
                    $filter_order_Dir = 'asc';

            $columns[] = $filter_order.' '.$filter_order_Dir;

            return $columns;
	}

        /**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere(){
            $db = JFactory::getDBO();
            $mainframe = JFactory::getApplication();
            $filter_state = $this->getState($this->_context.'.filter_state');
            $filter_search = $this->getState($this->_context.'.filter_search');
            $filter_former_member = $this->getState($this->_context.'.filter_former');
            $filter_area = $this->getState($this->_context.'.filter_area');            

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
		$db = JFactory::getDBO();
        $row = JTable::getInstance('Member', 'JResearch');
        $actions = JResearchAccessHelper::getActions();

        if(!$actions->get('core.staff.edit.state')){
        	$this->setError(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $item));        	
        	return false;
        }
        
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
		$actions = JResearchAccessHelper::getActions();		
        if(!$actions->get('core.staff.edit.state')){
        	$this->setError(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $item));        	
        	return false;
        }
		
		
        $db 		= JFactory::getDBO();
        $total		= count($items);
        $row		= JTable::getInstance('Member', 'JResearch');

        $order		= JRequest::getVar( 'order', array(), 'post', 'array' );
        JArrayHelper::toInteger($order);

        // update ordering values
        for( $i=0; $i < $total; $i++ ){
        	$row->load( $items[$i] );

            $groupings[] = $row->former_member;
            if ($row->ordering != $order[$i]){
				$row->ordering = $order[$i];
                if (!$row->store()){
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
	
	/**
    * Method to auto-populate the model state.
    *
    * This method should only be called once per instantiation and is designed
    * to be called on the first call to the getState() method unless the model
    * configuration flag to ignore the request is set.
    *
    * @return      void
    */
	protected function populateState(){
		$app = JFactory::getApplication();
		$this->setState($this->_context.'.filter_order', $app->getUserStateFromRequest($this->_context . '.filter_order', 'filter_order', 'ordering'));
        $this->setState($this->_context.'.filter_order_Dir', $app->getUserStateFromRequest($this->_context . '.filter_order_Dir', 'filter_order_Dir', 'asc'));        		
        $this->setState($this->_context.'.filter_search', $app->getUserStateFromRequest($this->_context . '.filter_search', 'filter_search'));
        $this->setState($this->_context.'.filter_state', $app->getUserStateFromRequest($this->_context . '.filter_state', 'filter_state'));
        $this->setState($this->_context.'.filter_former', $app->getUserStateFromRequest($this->_context . '.filter_former', 'filter_former'));        
    	$this->setState($this->_context.'.filter_area',$app->getUserStateFromRequest($this->_context . '.filter_area', 'filter_area'));
        
        parent::populateState();
	}
}
?>
