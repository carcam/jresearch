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
* Model class for holding lists of project records.
*
*/
class JResearchModelProjects extends JResearchModelList{


    public function getItems(){
        if(!isset($this->_items)){
            $items = parent::getItems();
            if($items !== false){
				$this->_items = array();
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
        $mainframe = JFactory::getApplication('site');
        $params = $mainframe->getParams('com_jresearch');
        $columns = array();

        // Read those from configuration
        $filter_order = $params->get('projects_default_sorting', 'start_date');
        $filter_order_Dir = $params->get('projects_order', 'ASC');

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
        $user = JFactory::getUser();
            
        // prepare the WHERE clause
        $where = array();
        $where[] = $db->nameQuote('published').' = 1 ';
            
        $filter_year = $this->getState('com_jresearch.projects.filter_year');
        $filter_search = $this->getState('com_jresearch.projects.filter_search');
        $filter_author = $this->getState('com_jresearch.projects.filter_author');            
        $filter_area = $this->getState('com_jresearch.projects.filter_area');
		$filter_team = $this->getState('com_jresearch.projects.filter_team');
		$filter_status = $this->getState('com_jresearch.projects.filter_status');

        if(!empty($filter_status) && $filter_status != -1){
            $where[] = $db->nameQuote('status').' = '.$db->Quote($filter_status);
        }
		
		
        if($filter_year != null && $filter_year != -1 )
        	$where[] = 'YEAR('.$db->nameQuote('start_date').') >= '.$db->Quote($filter_year);


        if(($filter_search = trim($filter_search))){
        	$filter_search = $db->getEscaped($filter_search);
        	$where[] = 'MATCH('.$db->nameQuote('title').', '.$db->nameQuote('keywords').') AGAINST('.$db->Quote($filter_search).' IN BOOLEAN MODE)';
        }

        if(!empty($filter_author) && $filter_author != '-1'){
           	$where[] = $db->nameQuote('apa').'.'.$db->nameQuote('mid').' = '.$db->Quote($filter_author);
        }
            
		if(!empty($filter_area) && $filter_area != -1){
        	$where[] = 'ra.id_research_area = '.$db->Quote($filter_area);            	
        }
        
            
        if(!empty($filter_team) && $filter_team != -1){
        	$filter_team = $db->getEscaped($filter_team);
        	$where[] = 'LOWER('.$db->nameQuote('id_team').') LIKE '.$db->Quote('%'.$filter_team.'%');            	
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
        // Initialize variables.
        $mainframe = JFactory::getApplication('site');
        $params = $mainframe->getParams('com_jresearch');

        $this->setState('com_jresearch.projects.filter_search', $mainframe->getUserStateFromRequest($this->_context.'.filter_search', 'filter_search'));
        
		//My projects
    	$filter_show = $params->get('filter_show', 'all');		
    	$user = JFactory::getUser();
    	if($filter_show == "my" && !$user->guest)
    	{
    		//Only in this case, force the model (ignore the filters)	    	
    		$member = JTable::getInstance('Member', 'JResearch');
    		$member->bindFromUsername($user->username);
    		JRequest::setVar('filter_author', $member->id);    	 			
    	}    	
        
        $this->setState('com_jresearch.projects.filter_author', $mainframe->getUserStateFromRequest($this->_context.'.filter_author', 'filter_author'));        
        $this->setState('com_jresearch.projects.filter_year', $mainframe->getUserStateFromRequest($this->_context.'.filter_year', 'filter_year'));        
        $this->setState('com_jresearch.projects.filter_area', $mainframe->getUserStateFromRequest($this->_context.'.filter_area', 'filter_area'));        
        $this->setState('com_jresearch.projects.filter_team', $mainframe->getUserStateFromRequest($this->_context.'.filter_team', 'filter_team'));        
        $this->setState('com_jresearch.projects.filter_status', $mainframe->getUserStateFromRequest($this->_context.'.filter_status', 'filter_status'));
		parent::populateState();        
    }
	
}
?>