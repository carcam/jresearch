<?php
/**
* @package		JResearch
* @subpackage	Frontend.Models
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
class JResearchModelPublications extends JResearchModelList{


        public function getItems(){
            if(!isset($this->_items)){
                $items = parent::getItems();
                if($items !== false){
					$this->_items = array();
                    foreach($items as $item){
                        $publication = $this->getTable('Publication', 'JResearch');
                        $publication->bind($item);
                        $this->_items[] = $publication;
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
            $query->from('#__jresearch_publication');
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
            $mainframe = JFactory::getApplication('site');
            $params = $mainframe->getParams();
            $columns = array();

            // Read those from configuration
            $filter_order = $params->get('publications_default_sorting', 'year');
            $filter_order_Dir = $params->get('publications_order', 'ASC');

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

            // prepare the WHERE clause
            $where = array();
            $where[] = $db->nameQuote('published').' = 1 ';
            $where[] = $db->nameQuote('id').' > 1 ';

            return $where;
	}
}
?>