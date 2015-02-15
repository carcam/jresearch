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
jresearchimport('models.modelList', 'jresearch.admin');
jresearchimport('tables.researcharea', 'jresearch.admin');

/**
* Model class for holding lists of research areas records.
*
*/
class JResearchAdminModelResearchAreas extends JResearchAdminModelList{	

    public function getItems(){
        if(!isset($this->_items)){
            $items = parent::getItems();
            if($items !== false){
                $this->_items = array();
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

        $filter_order = $this->getState('com_jresearch.researchareas.filter_order');
        $filter_order_Dir = $this->getState('com_jresearch.researchareas.filter_order_Dir');

        //Validate order direction
        if($filter_order_Dir != 'asc' && $filter_order_Dir != 'desc') {
            $filter_order_Dir = 'asc';
        }

        if(!in_array($filter_order, $orders))
            $filter_order = 'ordering';        

        $columns[] = $filter_order.' '.$filter_order_Dir;

        return $columns;
    }	

    /**
    * Build the WHERE part of a query
    */
    private function _buildQueryWhere(){
        $db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();
        $filter_state = $this->getState('com_jresearch.researchareas.filter_state');
        $filter_search = $this->getState('com_jresearch.researchareas.filter_search');

        // prepare the WHERE clause
        $where = array();

        if($filter_state == 'P')
            $where[] = $db->quoteName('published').' = 1 ';
        elseif($filter_state == 'U')
            $where[] = $db->quoteName('published').' = 0 ';

        if(($filter_search = trim($filter_search))){
            $filter_search = JString::strtolower($filter_search);
            $filter_search = $db->getEscaped($filter_search);
            $where[] = 'LOWER('.$db->quoteName('name').') LIKE '.$db->Quote('%'.$filter_search.'%');
        }

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
    protected function populateState($ordering = NULL, $direction = NULL){
        $app = JFactory::getApplication();
        $this->setState($this->_context.'.filter_order', $app->getUserStateFromRequest($this->_context . '.filter_order', 'filter_order', 'ordering'));
        $this->setState($this->_context.'.filter_order_Dir', $app->getUserStateFromRequest($this->_context . '.filter_order_Dir', 'filter_order_Dir', 'asc'));        		
        $this->setState($this->_context.'.filter_search', $app->getUserStateFromRequest($this->_context . '.filter_search', 'filter_search'));
        $this->setState($this->_context.'.filter_state', $app->getUserStateFromRequest($this->_context . '.filter_state', 'filter_state'));

        parent::populateState();
    }
    
    public function getTable() {
        return JTable::getInstance('Researcharea', 'JResearch');
    }
}
?>