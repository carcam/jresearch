<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'modelList.php');

/**
* Model class for holding lists of publication records.
*
*/
class JResearchAdminModelPublications extends JResearchAdminModelList{

		/**
		 * Returns the items seen in the backend
		 */
        public function getItems(){
            if(!isset($this->_items)){
                $items = parent::getItems();

                if($items !== false){
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
            $mainframe = JFactory::getApplication();
            $orders = array('title', 'published', 'internal', 'year', 'citekey', 'pubtype');
            $columns = array();

            $filter_order = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_order', 'filter_order', 'year');
            $filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest('com_jresearch.publications.filter_order_Dir', 'filter_order_Dir', 'DESC'));
            
            //Validate order direction
            if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
                $filter_order_Dir = 'ASC';
            
            if(!in_array($filter_order, $orders))
            	$filter_order = 'year';        
                
            $columns[] = $filter_order.' '.$filter_order_Dir;
			$columns[] = 'created DESC';            

            return $columns;
		}
			
		/**
		* Build the WHERE part of a query
		*/
		private function _buildQueryWhere(){
           	$mainframe = JFactory::getApplication();

            $db = JFactory::getDBO();

            $filter_state = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_state', 'filter_state');
            $filter_year = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_year', 'filter_year');
            $filter_search = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_search', 'filter_search');
            $filter_pubtype = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_pubtype', 'filter_pubtype');
            $filter_author = $mainframe->getUserStateFromRequest('com_jresearch.publications.filter_author', 'filter_author');            

            // prepare the WHERE clause
            $where = array();
            if($filter_state == 'P')
                  $where[] = $db->nameQuote('published').' = 1 ';
            elseif($filter_state == 'U')
                  $where[] = $db->nameQuote('published').' = 0 ';

            if($filter_year != null && $filter_year != -1 )
                  $where[] = $db->nameQuote('year').' = '.$db->Quote($filter_year);


            if(($filter_search = trim($filter_search))){
                  $filter_search = JString::strtolower($filter_search);
                  $filter_search = $db->getEscaped($filter_search);
        		  $where[] = '(LOWER('.$db->nameQuote('title').') LIKE '.$db->Quote('%'.$filter_search.'%')." OR LOCATE(".$db->Quote($filter_search).", LOWER(keywords)) > 0)";
            }

            if($filter_pubtype){
                 $where[] = $db->nameQuote('pubtype').' = '.$db->Quote($filter_pubtype);
            }


            if(!empty($filter_author) && $filter_author != -1){
                    $ids = $this->_getAuthorPublicationIds(trim($filter_author));
                    if(count($ids) > 0)
                            $where[] = $db->nameQuote('id').' IN ('.implode(',', $ids).')';
                    else
                            $where[] = '0 = 1';
            }


            return $where;
		
		}        
	
	
	
		/**
		* Returns the ids of the publications where the author has participated. 
		* @param $author Integer database id or author name depending if the author is member
		* of the center or not.
		*/
		private function _getAuthorPublicationIds($author){
            $db = JFactory::getDBO();
            if(is_numeric($author)){
                $query = 'SELECT '.$db->nameQuote('id_publication').' FROM '.$db->nameQuote('#__jresearch_publication_internal_author').' WHERE '.$db->nameQuote('id_staff_member').' = '.$db->Quote($author);
            }else{
                $query = 'SELECT '.$db->nameQuote('id_publication').' FROM '.$db->nameQuote('#__jresearch_publication_external_author').' WHERE '.$db->nameQuote('author_name').' LIKE '.$db->Quote($author);
            }
            $db->setQuery($query);

            $result = $db->loadResultArray();
            return $result;
		}
}
?>
