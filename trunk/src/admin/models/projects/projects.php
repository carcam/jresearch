<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport( 'joomla.application.component.model' );

require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'models'.'/'.'modelList.php');

/**
* Model class for holding lists of project records.
*
* @subpackage	Projects
*/
class JResearchAdminModelProjects extends JResearchAdminModelList{

		/**
		 * Returns the items seen in the backend
		 */
        public function getItems(){
            if(!isset($this->_items)){
                $items = parent::getItems();
            	$db = JFactory::getDBO();
                if($items !== false){
                    foreach($items as $item){
                        $project = $this->getTable('Project', 'JResearch');
                        $project->bind($item);
                        $this->_items[] = $project;
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

            $query->select('DISTINCT proj.*');
            $query->from('#__jresearch_project proj');
            $query->leftJoin('#__jresearch_project_researcharea AS ra ON proj.id = ra.id_project');
            $query->leftJoin('#__jresearch_all_project_authors AS apa ON proj.id = apa.pid');
                        
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
            $orders = array('title', 'published', 'ra.id', 'start_date', 'status', 'apa.member_name', 'ordering');
            $columns = array();

            $filter_order = $this->getState($this->_context.'.filter_order');
            $filter_order_Dir = strtoupper($this->getState($this->_context.'.filter_order_Dir'));
            
            //Validate order direction
            if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
                $filter_order_Dir = 'ASC';
            
            if(!in_array($filter_order, $orders))
            	$filter_order = 'start_date';        
                
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

            $filter_state = $this->getState('com_jresearch.projects.filter_state');            
            $filter_status = $this->getState('com_jresearch.projects.filter_status');
            $filter_start_date = $this->getState('com_jresearch.projects.filter_start_date');
            $filter_search = $this->getState('com_jresearch.projects.filter_search');
            $filter_author = $this->getState('com_jresearch.projects.filter_author');
            $filter_area = $this->getState('com_jresearch.projects.filter_area');                  
            
            if($filter_state == 'P')
                $where[] = $db->nameQuote('published').' = 1 ';
            elseif($filter_state == 'U')
                $where[] = $db->nameQuote('published').' = 0 ';

            if(!empty($filter_area) && $filter_area != -1){
        		$where[] = $db->nameQuote('ra').'.'.$db->Quote('id_research_area').'='.$db->Quote($filter_area);            	
        	}
            
            // prepare the WHERE clause
            if(!empty($filter_status) && $filter_status != -1){
                $where[] = $db->nameQuote('status').' = '.$db->Quote($filter_status);
            }

            if(!empty($filter_year) && $filter_year != -1 ){
            	$mysqlStartDate = $filter_year.'-01-01';
            	$mysqlEndDate = $filter_year.'-12-31';
                $where[] = $db->nameQuote('start_date').' BETWEEN '.$db->Quote($mysqlStartDate).' AND '.$db->Quote($mysqlEndDate);
            }

            if(($filter_search = trim($filter_search))){
                  $filter_search = JString::strtolower($filter_search);
                  $filter_search = $db->getEscaped($filter_search);
        		  $where[] = 'MATCH(title, description) AGAINST ('.$db->Quote($db->getEscaped($filter_search, true)).' IN BOOLEAN MODE)';
            }
            
            if(!empty($filter_author) && $filter_author != '-1'){
            	$where[] = $db->nameQuote('apa').'.'.$db->nameQuote('mid').' = '.$db->Quote($filter_author);
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
    protected function populateState() {    	
    	$mainframe = JFactory::getApplication();
        $this->setState('com_jresearch.projects.filter_search', $mainframe->getUserStateFromRequest($this->_context.'.filter_search', 'filter_search'));
        $this->setState('com_jresearch.projects.filter_author', $mainframe->getUserStateFromRequest($this->_context.'.filter_author', 'filter_author'));        
        $this->setState('com_jresearch.projects.filter_start_date', $mainframe->getUserStateFromRequest($this->_context.'.filter_year', 'filter_start_date'));        
        $this->setState('com_jresearch.projects.filter_area', $mainframe->getUserStateFromRequest($this->_context.'.filter_area', 'filter_area'));                
        $this->setState('com_jresearch.projects.filter_status', $mainframe->getUserStateFromRequest($this->_context.'.filter_status', 'filter_status'));
        $this->setState('com_jresearch.projects.filter_state', $mainframe->getUserStateFromRequest($this->_context.'.filter_state', 'filter_state'));                		       
        $this->setState('com_jresearch.projects.filter_order', $mainframe->getUserStateFromRequest($this->_context.'.filter_order', 'filter_order', 'projects'));                		        
        $this->setState('com_jresearch.projects.filter_order_Dir', $mainframe->getUserStateFromRequest($this->_context.'.filter_order_Dir', 'filter_order_Dir', 'DESC'));        
        parent::populateState();        
    }		
	
}
?>