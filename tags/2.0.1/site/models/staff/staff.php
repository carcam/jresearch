<?php
/**
* @package		JResearch
* @subpackage	Models
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

jresearchimport('models.modellist', 'jresearch.site');

/**
* Model class for holding lists of research areas records.
*
*/
class JResearchModelStaff extends JResearchModelList{


    public function getItems(){
	   if(!isset($this->_items)){
	   		$items = parent::getItems();
	        if($items !== false){
		        $this->_items = array();
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

        $query->select('m.*');
        $query->from('#__jresearch_member m');
        $query->leftJoin('#__jresearch_member_position mp ON m.position = mp.id AND mp.published = 1');
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
        $params = $mainframe->getParams();
        $columns = array();

        // Read those from configuration
        $filter_order0 = $params->get('staff_sort_criteria0', null);
        $filter_order1 = $params->get('staff_sort_criteria1', 'm.lastname');
        $filter_order_Dir1 = $params->get('staff_order1', 'ASC');
        $filter_order2 = $params->get('staff_sort_criteria2', 'm.ordering');
        $filter_order_Dir2 = $params->get('staff_order2', 'ASC');
        $groupByFormer = $params->get('staff_former_grouping', 0);

        //Validate order direction
        if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
        	$filter_order_Dir = 'ASC';
        	
        if($groupByFormer == '1')
        	$columns[] = 'former_member DESC';

        if(!empty($filter_order0))	
            $columns[] = $filter_order0.' ASC';
                
        $columns[] = $filter_order1.' '.$filter_order_Dir1;
        $columns[] = $filter_order2.' '.$filter_order_Dir2;

        return $columns;
	}

    /**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere(){
    	$db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();

        // prepare the WHERE clause
        $where = array();
        $where[] = 'm.published = 1';
        
        $filter_former_member = $this->getState('com_jresearch.staff.filter_former');
        if($filter_former_member == 'only_current')
        	$where[] = 'former_member = 0';
        else if($filter_former_member == 'only_former')
        	$where[] = 'former_member = 1';	

        return $where;
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
    protected function populateState() {
        // Initialize variables.
        $mainframe = JFactory::getApplication('site');
        $params = $mainframe->getParams('com_jresearch');
        $this->setState('com_jresearch.staff.filter_former', $params->get('staff_filter', 'all'));
		parent::populateState();        
    }
}
?>