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
            $params = $mainframe->getParams('com_jresearch');
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
            $where[] = $db->nameQuote('internal').' = 1 ';            
            $where[] = $db->nameQuote('id').' > 0 ';
            
            $filter_year = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_year', 'filter_year');
            $filter_search = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_search', 'filter_search');
            $filter_pubtype = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_pubtype', 'filter_pubtype');
            $filter_author = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_author', 'filter_author');            
            $filter_area = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_area', 'filter_area');
			$filter_team = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_team', 'filter_team');

            if($filter_year != null && $filter_year != -1 )
                  $where[] = $db->nameQuote('year').' = '.$db->Quote($filter_year);


            if(($filter_search = trim($filter_search))){
                  $filter_search = $db->getEscaped($filter_search);
        		  $where[] = '(MATCH('.$db->nameQuote('title').', '.$db->nameQuote('keywords').') AGAINST('.$db->Quote($filter_search).' IN BOOLEAN MODE)';
            }

            if($filter_pubtype){
                 $where[] = $db->nameQuote('pubtype').' = '.$db->Quote($filter_pubtype);
            }

            if(!empty($filter_author) && $filter_author != -1){
                  $filter_author = $db->getEscaped($filter_author);            	
        		  $where[] = 'LOWER('.$db->nameQuote('authors').') LIKE '.$db->Quote('%'.$filter_author.'%');            	
            }
            
            if(!empty($filter_area) && $filter_area != -1){
        		  $where[] = 'LOWER('.$db->nameQuote('id_research_area').') LIKE '.$db->Quote('%'.$filter_area.'%');            	
            }
            
            if(!empty($filter_team) && $filter_team != -1){
        		  $where[] = 'LOWER('.$db->nameQuote('id_team').') LIKE '.$db->Quote('%'.$filter_team.'%');            	
            }
            

            return $where;
	}
}
?>