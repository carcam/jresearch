<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'modelList.php');

/**
* Model class for holding lists of research areas records.
*
*/
class JResearchAdminModelResearchAreas extends JResearchAdminModelList{	


        public function getItems(){
            if(!isset($this->_items)){
                $items = parent::getItems();

                if($items !== false){
                    foreach($items as $item){
                        $area = $this->getTable('Researcharea', 'JResearch');
                        $area->bind($item);
                        $this->_items[] = $area;
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
            $query->from('#__jresearch_research_area');
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
            $orders = array('name', 'published', 'ordering');
            $columns = array();

            $filter_order = $mainframe->getUserStateFromRequest('com_jresearch.researchAreas.filter_order', 'filter_order', 'ordering');
            $filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('com_jresearch.researchAreas.filter_order_Dir', 'filter_order_Dir', 'ASC'));

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
            $filter_state = $mainframe->getUserStateFromRequest('com_jresearch.researchAreas.filter_state', 'filter_state');
            $filter_search = $mainframe->getUserStateFromRequest('com_jresearch.researchAreas.filter_search', 'filter_search');

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

            return $where;			
	}
}
?>
