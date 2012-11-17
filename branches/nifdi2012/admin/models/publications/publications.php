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
                $db = JFactory::getDBO();
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

            $query->select('DISTINCT pub.*');
            $query->from('#__jresearch_publication pub');
            $query->leftJoin('#__jresearch_publication_researcharea AS ra ON pub.id = ra.id_publication');
            $query->leftJoin('#__jresearch_all_publication_authors AS apa ON pub.id = apa.pid');
            
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

            $filter_order = $this->getState($this->_context.'.filter_order');
            $filter_order_Dir = strtoupper($this->getState($this->_context.'.filter_order_Dir'));
            
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
            $where = array();
            $db = JFactory::getDBO();

            $filter_state = $this->getState('com_jresearch.publications.filter_state');
            $filter_year = $this->getState('com_jresearch.publications.filter_year');
            $filter_search = $this->getState('com_jresearch.publications.filter_search');
            $filter_pubtype = $this->getState('com_jresearch.publications.filter_pubtype');
            $filter_author = $this->getState('com_jresearch.publications.filter_author');
            $filter_area = $this->getState('com_jresearch.publications.filter_area');                  
            $filter_supertype = $this->getState('com_jresearch.publications.filter_supertype');
            
            if(!empty($filter_area) && $filter_area != -1){
        		$where[] = 'ra.id_research_area = '.$db->Quote($filter_area);            	
        	}
            
            // prepare the WHERE clause
            if($filter_state == 'P')
                  $where[] = $db->nameQuote('published').' = 1 ';
            elseif($filter_state == 'U')
                  $where[] = $db->nameQuote('published').' = 0 ';

            if($filter_year != null && $filter_year != -1 )
                  $where[] = $db->nameQuote('year').' = '.$db->Quote($filter_year);


            if(($filter_search = trim($filter_search))){
                  $filter_search = JString::strtolower($filter_search);
                  $filter_search = $db->getEscaped($filter_search);
        		  $where[] = 'MATCH(title, keywords, abstract) AGAINST ('.$db->Quote($filter_search, true).' IN BOOLEAN MODE)';
            }
                  
            if(!empty($filter_pubtype) && $filter_pubtype != '-1'){
                 $where[] = $db->nameQuote('pubtype').' = '.$db->Quote($filter_pubtype);
            }
            
		    if(!empty($filter_supertype) && $filter_supertype != 'all'){
                 $where[] = $db->nameQuote('supertype').' = '.$db->Quote($filter_supertype);
            }

            if(!empty($filter_author) && $filter_author != '-1'){
            	$where[] = $db->nameQuote('apa').'.'.$db->nameQuote('mid').' = '.$db->Quote($filter_author);
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
    	$mainframe = JFactory::getApplication();
        $this->setState('com_jresearch.publications.filter_search', $mainframe->getUserStateFromRequest($this->_context.'.filter_search', 'filter_search'));
        $this->setState('com_jresearch.publications.filter_state', $mainframe->getUserStateFromRequest($this->_context.'.filter_state', 'filter_state'));        
        $this->setState('com_jresearch.publications.filter_author', $mainframe->getUserStateFromRequest($this->_context.'.filter_author', 'filter_author'));        
        $this->setState('com_jresearch.publications.filter_year', $mainframe->getUserStateFromRequest($this->_context.'.filter_year', 'filter_year'));        
        $this->setState('com_jresearch.publications.filter_area', $mainframe->getUserStateFromRequest($this->_context.'.filter_area', 'filter_area'));                
        $this->setState('com_jresearch.publications.filter_pubtype', $mainframe->getUserStateFromRequest($this->_context.'.filter_pubtype', 'filter_pubtype'));        
        $this->setState('com_jresearch.publications.filter_team', $mainframe->getUserStateFromRequest($this->_context.'.filter_team', 'filter_team'));        
        $this->setState('com_jresearch.publications.filter_order', $mainframe->getUserStateFromRequest($this->_context.'.filter_order', 'filter_order', 'year'));        
        $this->setState('com_jresearch.publications.filter_order_Dir', $mainframe->getUserStateFromRequest($this->_context.'.filter_order_Dir', 'filter_order_Dir', 'DESC'));
        $this->setState('com_jresearch.publications.filter_supertype', $mainframe->getUserStateFromRequest($this->_context.'.filter_supertype', 'filter_supertype'));                        
		
        parent::populateState();        
    }		
		
}
?>
