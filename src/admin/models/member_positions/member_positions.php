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
jresearchimport('models.modelList', 'jresearch.admin');
use Joomla\Utilities\ArrayHelper;

/**
* Model class for holding lists of project records.
*
* @subpackage	Member_Positions
*/
class JResearchAdminModelMember_positions extends JResearchAdminModelList
{
	
    public function getItems(){
    	if(!isset($this->_items)){
        	$items = parent::getItems();
            if($items !== false){
            	$this->_items = array();
                foreach($items as $item){
                	$position = $this->getTable('Member_position', 'JResearch');
                    $position->bind($item);
                    $this->_items[] = $position;
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
        $query->from('#__jresearch_member_position');
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
        $orders = array('position', 'published');
        $columns = array();

        $filter_order = $this->getState('com_jresearch.member_positions.filter_order');
        $filter_order_Dir = $this->getState('com_jresearch.member_positions.filter_order');
                
        //Validate order direction
        if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
            $filter_order_Dir = 'ASC';

        if(!in_array($filter_order, $orders))
          	$filter_order = 'position';        
                    
        $columns[] = $filter_order.' '.$filter_order_Dir;

        return $columns;
	}	
	
	/**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere(){
        $db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();        
		$filter_state = $this->getState('com_jresearch.member_positions.filter_state');
        $filter_search = $this->getState('com_jresearch.member_positions.filter_search');

        // prepare the WHERE clause
        $where = array();

        if($filter_state == 'P')
        	$where[] = $db->quoteName('published').' = 1 ';
        elseif($filter_state == 'U')
            $where[] = $db->quoteName('published').' = 0 ';

        if(($filter_search = trim($filter_search))){
        	$filter_search = JString::strtolower($filter_search);
            $filter_search = $db->getEscaped($filter_search);
            $where[] = 'LOWER('.$db->quoteName('position').') LIKE '.$db->Quote('%'.$filter_search.'%');
        }

        return $where;			
	}
	
	/**
	 * Ordering item
	*/
	function orderItem($item, $movement)
	{
		$db = JFactory::getDBO();
        $row = JTable::getInstance('Member_position', 'JResearch');
        $actions = JResearchAccessHelper::getActions();

        if(!$actions->get('core.manage')){
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
    function setOrder($items) {
        $actions = JResearchAccessHelper::getActions();		
        if(!$actions->get('core.manage')){
            $this->setError(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $item));        	
            return false;
        }
		
		
        $db = JFactory::getDBO();
        $jinput = JFactory::getApplication()->input;        
        $total = count($items);
        $row = JTable::getInstance('Member_position', 'JResearch');

        $order = $jinput->post->get('order', array(), 'ARRAY');
        ArrayHelper::toInteger($order);

        // update ordering values
        for( $i=0; $i < $total; $i++ ){
            $row->load( $items[$i] );

            if ($row->ordering != $order[$i]){
                $row->ordering = $order[$i];
                if (!$row->store()){
                    return false;
                }
            }
        } // for

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
    protected function populateState($ordering = NULL, $direction = NULL) {
    	$mainframe = JFactory::getApplication();
    	$this->setState('com_jresearch.member_positions.filter_search', $mainframe->getUserStateFromRequest($this->_context.'.filter_search', 'filter_search'));
    	$this->setState('com_jresearch.member_positions.filter_state', $mainframe->getUserStateFromRequest($this->_context.'.filter_state', 'filter_state'));
    	$this->setState('com_jresearch.member_positions.filter_order', $mainframe->getUserStateFromRequest($this->_context.'.filter_order', 'filter_order', 'ordering'));
    	$this->setState('com_jresearch.member_positions.filter_order_Dir', $mainframe->getUserStateFromRequest($this->_context.'.filter_order_Dir', 'filter_order_Dir', 'ASC'));
    
    	parent::populateState($ordering, $direction);
    }
    
}
?>