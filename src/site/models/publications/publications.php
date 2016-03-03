<?php
/**
* @package		JResearch
* @subpackage	Frontend.Models
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('_JEXEC') or die( 'Restricted access' );

jresearchimport('models.modellist', 'jresearch.site');

/**
* Model class for holding lists of publication records.
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

        $query->select('DISTINCT pub.*');
        $query->from('#__jresearch_publication pub');
        $query->leftJoin('#__jresearch_publication_research_area AS ra ON pub.id = ra.id_publication');
        $query->leftJoin('#__jresearch_all_publication_authors AS apa ON pub.id = apa.pid');
            
        if(!empty($whereClauses))
            $query->where($whereClauses);

        $query->order($orderColumns);
        echo $query;
        return $query;
    }


    /**
    * Build the ORDER part of a query.
    */
    private function _buildQueryOrderBy(){
        //Array of allowable order fields
        $mainframe = JFactory::getApplication('site');
        $params = $mainframe->getParams('com_jresearch');

        $filter_order = $params->get('publications_default_sorting', 'year');
        $filter_order_Dir = $params->get('publications_order', 'ASC');

        //Validate order direction
        if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
            $filter_order_Dir = 'ASC';

        $columns[] = $filter_order.' '.$filter_order_Dir;
        if($filter_order == 'year'){
            //Consider the month information            	            	
            $columns[] = "STR_TO_DATE(month, '%M') $filter_order_Dir";
            $columns[] = "STR_TO_DATE(day, '%d') $filter_order_Dir";
        }

        $columns[] = 'created DESC';            

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
        $where[] = $db->quoteName('published').' = 1 ';
        $where[] = $db->quoteName('internal').' = 1 ';            
        $where[] = $db->quoteName('id').' > 0 ';

        $filter_year = $this->getState('com_jresearch.publications.filter_year');
        $filter_search = $this->getState('com_jresearch.publications.filter_search');
        $filter_pubtype = $this->getState('com_jresearch.publications.filter_pubtype');
        $filter_author = $this->getState('com_jresearch.publications.filter_author');            
        $filter_area = $this->getState('com_jresearch.publications.filter_area');
        $filter_pubtype_exclude = $this->setState('com_jresearch.publications.filter_pubtype_exclude');

        if($filter_year != null && $filter_year != -1 ){
            $year_values = explode(',', $filter_year);
            if (is_array($year_values) && !empty($year_values)) {
                $quotedValues = array();
                foreach ($year_values as $year) {
                    if ($year == 'current') {
                        $quotedValues[] = $db->Quote(date("Y"));
                    } else {
                        $quotedValues[] = $db->Quote($year);
                    } 
                }                    
                $inString = '('.implode(',', $quotedValues).')';
                $where[] = $db->quoteName('year').' IN '.$inString;
            }
        }

        if(($filter_search = trim($filter_search))){
            $where[] = 'MATCH('.$db->quoteName('title').', '.$db->quoteName('keywords').') AGAINST('.$db->Quote($filter_search).' IN BOOLEAN MODE)';
        }

        if($filter_pubtype && $filter_pubtype != 'all'){
            $where[] = $db->quoteName('pubtype').' = '.$db->Quote($filter_pubtype);
        } else if (!empty($filter_pubtype_exclude)) {
            $excludedTypes = explode(',', $filter_pubtype_exclude);
            $cleanExcludedTypes = array();
            foreach ($excludedTypes as $exType) {
                $cleanExcludedTypes[] = $db->Quote(trim($exType));
            }
            $where[] = $db->quoteName('pubtype').' NOT IN ('. implode(',', $cleanExcludedTypes).')';
        }

        if(!empty($filter_author) && $filter_author != '-1'){
            $where[] = $db->quoteName('apa').'.'.$db->quoteName('mid').' = '.$db->Quote($filter_author);
        }

        if(!empty($filter_area) && $filter_area != -1){
            $where[] = 'ra.id_research_area = '.$db->Quote($filter_area);            	
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
    protected function populateState($ordering = null, $direction = null) {
        // Initialize variables.
        $mainframe = JFactory::getApplication('site');
        $params = $mainframe->getParams('com_jresearch');

        $this->setState('com_jresearch.publications.filter_search', $mainframe->getUserStateFromRequest($this->_context.'.filter_search', 'filter_search'));
        
        //My publications
    	$filter_show = $params->get('filter_show', 'all');		
    	$user = JFactory::getUser();
    	if ($filter_show == "my" && !$user->guest) {
            //Only in this case, force the model (ignore the filters)	    	
            $member = JTable::getInstance('Member', 'JResearch');
            $member->bindFromUsername($user->username);
            JRequest::setVar('filter_author', $member->id);    	 			
    	}

        $filter_year_value = $params->get('filter_year');
        if (!empty($filter_year_value)) {
            JRequest::setVar('filter_year', $filter_year_value);
        }
        
        $this->setState('com_jresearch.publications.filter_author', $mainframe->getUserStateFromRequest($this->_context.'.filter_author', 'filter_author'));        
        $this->setState('com_jresearch.publications.filter_year', $mainframe->getUserStateFromRequest($this->_context.'.filter_year', 'filter_year'));        
        $this->setState('com_jresearch.publications.filter_area', $mainframe->getUserStateFromRequest($this->_context.'.filter_area', 'filter_area'));        
        
        $filter_pubtype = $params->get('filter_pubtype', 'all');    	    	
        if($filter_pubtype != 'all'){
            JRequest::setVar('filter_pubtype', $filter_pubtype);
        }
        
        $this->setState('com_jresearch.publications.filter_pubtype', $mainframe->getUserStateFromRequest($this->_context.'.filter_pubtype', 'filter_pubtype'));
        $this->setState('com_jresearch.publications.filter_pubtype_exclude', $params->get('filter_pubtype_exclude', null));
        parent::populateState();        
    }
}
?>
