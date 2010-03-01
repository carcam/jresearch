<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelList.php');

/**
* Model class for holding lists of publication records.
*
*/
class JResearchModelPublicationsList extends JResearchModelList{
	
	
	/**
	* Class constructor.
	*/
	function __construct(){
		parent::__construct();
		$this->_tableName = '#__jresearch_publication';
	}
	
	/**
	* Returns the SQL used to get the data from publications table.
	* 
	* @pàram $memberId If non null, it represents the id of a staff member and the method returns
	* only those items of the member's authoring.
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	*
	* @return string
	*/
	protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false ){		
		$db =& JFactory::getDBO();		
		if($memberId === null){	
			$resultQuery = 'SELECT * FROM '.$db->nameQuote($this->_tableName); 	
		}else{
			$resultQuery = '';
		}
		// Deal with pagination issues
		$resultQuery .= $this->_buildQueryWhere($onlyPublished).' '.$this->_buildQueryOrderBy();		
		if($paginate){
			$limit = (int)$this->getState('limit');
			if($limit != 0)
					$resultQuery .= ' LIMIT '.(int)$this->getState('limitstart').' , '.$this->getState('limit');					
		}
		
		return $resultQuery;
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
		
		//@todo Add id_author comparison
		
		return $result;
	}
	
	/**
	 * Returns the internal publications developed by members of the specified team.
	 *
	 * @param int $teamId
	 * @return array
	 */
	private function _getTeamPublicationIds($teamId, $count=0){
		$db = JFactory::getDBO();
		
		$id_staff_member = $db->nameQuote('id_staff_member');
		$team_member = $db->nameQuote('#__jresearch_team_member');
		$id_publication = $db->nameQuote('id_publication');
		$pub_internal_author = $db->nameQuote('#__jresearch_publication_internal_author');
		$teamValue = $db->Quote($teamId);
		$id_team = $db->nameQuote('id_team');
		$id_member = $db->nameQuote('id_member');
		
		$query = "SELECT DISTINCT $id_publication FROM $pub_internal_author, $team_member WHERE $team_member.$id_team = $teamValue "
				 ." AND $pub_internal_author.$id_staff_member = $team_member.$id_member";
				 
		if($count > 0)
		{
			$query .= " LIMIT 0,$count";
		}
		
		$db->setQuery($query);
		return $db->loadResultArray();
	}

	/**
	* Like method _buildQuery, but it does not consider LIMIT clause.
	* 
	* @return string SQL query.
	*/	
	protected function _buildRawQuery(){
		$db =& JFactory::getDBO();
		$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName); 	
		$resultQuery .= $this->_buildQueryWhere($this->_onlyPublished).' '.$this->_buildQueryOrderBy();		
		return $resultQuery;
	}
	

	/**
	* Returns an array of ALL the items of an entity independently of its published state considering
	* pagination parameters. 
	*
	* @pàram $memberId If non null, it represents the id of a staff member and the method returns
	* only those items of the member's authoring.
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	* @param $teamId If not null, the method only return those items authored by members of the 
	* specified team.
	* @return 	array
	*/
	public function getData($memberId = null, $onlyPublished = false, $paginate = false){
	
		if($memberId !== $this->_memberId || $onlyPublished !== $this->_onlyPublished || $this->_paginate !== $this->_paginate || empty($this->_items)){
			$this->_memberId = $memberId;
			$this->_onlyPublished = $onlyPublished;
			$this->_paginate = $paginate;					
			$this->_items = array();
			
			$db = &JFactory::getDBO();
			$query = $this->_buildQuery($memberId, $onlyPublished, $paginate);
			$db->setQuery($query);
			$result = $db->loadAssocList();
			foreach($result as $item){
				$pub = JTable::getInstance("Publication", "JResearch");
				$pub->bind($item, array(), true);
				$this->_items[] = $pub;
			}
			
			if($paginate)
				$this->updatePagination();
		}	
		return $this->_items;

	}
	
	/**
	 * Gets data by team id
	 *
	 * @param int $teamId
	 * @return array
	 */
	public function getDataByTeamId($teamId, $count=0)
	{
		$model = JModel::getInstance('Team', 'JResearchModel');
		$team = $model->getItem($teamId);
		$pubs = array();
		
		if(!empty($team))
		{
			$ids = $this->_getTeamPublicationIds($team->id, intval($count));
			
			foreach($ids as $id)
			{
				$pubs[] = JResearchPublication::getById($id);
			}
		}
		
		return $pubs;
	}
	
	/**
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy(){
		global $mainframe;
		$modelKey = JRequest::getVar('modelkey', '');
		
		$db =& JFactory::getDBO();
		//Array of allowable order fields
		$orders = array('title', 'published', 'year', 'citekey', 'pubtype', 'id_research_area');
		
		$filter_order = $mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_order', 'filter_order', 'title');
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_order_Dir', 'filter_order_Dir', 'ASC'));
		
		//Validate order direction
		if($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
			$filter_order_Dir = 'ASC';	
		//if order column is unknown, use the default
		if($filter_order == 'type')
			$filter_order = $db->nameQuote('pubtype');
		elseif($filter_order == 'alphabetical' || !in_array($filter_order, $orders))	
			$filter_order = $db->nameQuote('title');	
		
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', '.$db->nameQuote('created').' DESC';
	}	
	
	/**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere($published = false){
		global $mainframe;
		
		$db = & JFactory::getDBO();
		$modelKey = JRequest::getVar('modelkey', 'default');
				
		$filter_state = $mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_state', 'filter_state');
		$filter_year = $mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_year', 'filter_year');
		$filter_search = $mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_search', 'filter_search');
		$filter_pubtype = $mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_pubtype', 'filter_pubtype');
		$filter_area = $mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_area', 'filter_area');
		$filter_author = $mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_author', 'filter_author');
		$filter_team = $mainframe->getUserStateFromRequest($modelKey.'publicationsfilter_team', 'filter_team');

		// prepare the WHERE clause
		$where = array();
		if(!$published){
			if($filter_state == 'P')
				$where[] = $db->nameQuote('published').' = 1 ';
			elseif($filter_state == 'U')
				$where[] = $db->nameQuote('published').' = 0 '; 	
		}else
			$where[] = $db->nameQuote('published').' = 1 ';
		
			
		if($filter_year != null && $filter_year != -1 )
			$where[] = $db->nameQuote('year').' = '.$db->Quote($filter_year);
		
					
		if(($filter_search = trim($filter_search))){
			$filter_search = JString::strtolower($filter_search);
			$filter_search = $db->getEscaped($filter_search);
			$where[] = 'LOWER('.$db->nameQuote('title').') LIKE '.$db->Quote('%'.$filter_search.'%');
		}
		
		if($filter_pubtype){
			$where[] = $db->nameQuote('pubtype').' = '.$db->Quote($filter_pubtype);
		}
		
		if($filter_area){
			$where[] = $db->nameQuote('id_research_area').' = '.$db->Quote($filter_area);
		}

		
		if(!empty($filter_author) && $filter_author != -1){
			$ids = $this->_getAuthorPublicationIds(trim($filter_author));			
			if(count($ids) > 0)
				$where[] = $db->nameQuote('id').' IN ('.implode(',', $ids).')';
			else
				$where[] = '0 = 1';	
		}
		
		if(!empty($filter_team)){
			if($filter_team > 0){
				$tmids = $this->_getTeamPublicationIds(trim($filter_team));
				if(count($tmids) > 0)
					$where[] = $db->nameQuote('id').' IN ('.implode(',', $tmids).')';
				else
					$where[] = '0 = 1';
			}					
		}
		
		if(!$mainframe->isAdmin()){
			$where[] = $db->nameQuote('internal').' = '.$db->Quote('1');
		}

		
		return (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
			
	}

	/**
	* Returns an associative array with the information of all members and external authors.
	* @return array
	*/
	function getAllAuthors(){
		$db = JFactory::getDBO();
		$query = 'SELECT DISTINCT '.$db->nameQuote('author_name').' as id, '.$db->nameQuote('author_name').' as name FROM '.$db->nameQuote('#__jresearch_publication_external_author').' UNION SELECT id, CONCAT_WS( \' \', firstname, lastname ) as name FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('published').' = '.$db->Quote('1');
		$db->setQuery($query);
		$result =  $db->loadAssocList();
		$mdresult = array();
		$name = array();
		// First, bring them to the form lastname, firstname.
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'publications.php');
		foreach($result as $key => $author){
			$components = JResearchPublicationsHelper::getAuthorComponents($author['name']);
			$value = (isset($components['von'])?$components['von'].' ':'').$components['lastname'].(isset($components['firstname'])?', '.$components['firstname']:'').(isset($components['jr'])?' '.$components['jr']:'');
			$mdresult[] = array('id'=>$author['id'], 'name'=>$value);
			$name[$key] = $value;
			
		}
		array_multisort($name, SORT_ASC, $mdresult);
		return $mdresult;
	}

	/**
	 * Returns an array with the items related to the prefix sent as parameter.
	 * @param string $prefix Search key
	 * @param string $criteria It can be all|keywords|title|year|authors|citekey. Depending on this value
	 * the prefix will be compared against an specific field of the publication.
	 * 
	 * @return array Array of public records that match the search
	 */
	public function getItemsByPrefix($prefix, $criteria, $limitstart = 0, $limit = 10){		
		$db = JFactory::getDBO();
		$records = array();
		$finalQuery = null;
		
		if($prefix == '')
			return $records;

		$newprefix = $db->Quote( '%'.$db->getEscaped( strtolower($prefix), true ).'%', false );
		$prefixscp = $db->Quote(strtolower($prefix), false); 
		$publicationTable = $db->nameQuote('#__jresearch_publication');
		$staffTable = $db->nameQuote('#__jresearch_member');
		$internalAuthorsTable = $db->nameQuote('#__jresearch_publication_internal_author');
		$externalAuthorsTable = $db->nameQuote('#__jresearch_publication_external_author');
		$p = $db->nameQuote('p');
		$em = $db->nameQuote('em');
		$im = $db->nameQuote('im');
		$m = $db->nameQuote('m');
		$id_publication = $db->nameQuote('id_publication');
		$id = $db->nameQuote('id');
		$firstname = $db->nameQuote('firstname');
		$lastname = $db->nameQuote('lastname');
		$id_staff_member = $db->nameQuote('id_staff_member');
		$pubtype = $db->nameQuote('pubtype');
		$authorname = $db->nameQuote('author_name');
		$pu = $db->nameQuote('published');

		$whereKeywords = " LOCATE($prefixscp, LOWER(".$db->nameQuote('keywords').")) > 0";
		$whereTitle = " LOWER(".$db->nameQuote('title').") LIKE $newprefix";
		$whereYear = " ".$db->nameQuote('year')." = $prefixscp";
		$whereCitekey = " LOWER(".$db->nameQuote('citekey').") LIKE $newprefix";
		$published = $db->nameQuote('published').' = '.$db->Quote(1);


		switch($criteria){
			case 'all': case 'authors':
				break;
			case 'keywords':
				$query = "SELECT $id, $pubtype FROM $publicationTable WHERE".$whereKeywords." AND ".$published;
				break;
			case 'title':
				$query = "SELECT $id, $pubtype FROM $publicationTable WHERE ".$whereTitle." AND ".$published;
				break;
			case 'year':
				$query = "SELECT $id, $pubtype FROM $publicationTable WHERE".$whereYear." AND ".$published;
				break;
			case 'citekey':
				$query = "SELECT $id, $pubtype FROM $publicationTable WHERE".$whereCitekey." AND ".$published;
				break;			
		}
		
		// If %% is sent, so ignore criteria, just return all available items
		if($prefix != '%%'){
			if($criteria == 'authors'){
				$finalQuery = "SELECT DISTINCT $p.$id, $p.$pubtype  FROM $publicationTable $p, $externalAuthorsTable em"
				." WHERE $published AND $em.$id_publication = $p.$id AND LOWER($em.$authorname) LIKE $newprefix"
				." UNION SELECT $p.$id, $p.$pubtype FROM $publicationTable $p, $internalAuthorsTable $im, $staffTable $m"
				." WHERE $p.$id = $im.$id_publication AND $p.$pu = 1 AND $im.$id_staff_member = $m.$id"
				." AND (LOWER($m.$firstname) LIKE $newprefix OR LOWER($m.$lastname) LIKE $newprefix) ";
			}else if($criteria == 'all'){
				$finalQuery = "SELECT id, pubtype FROM $publicationTable WHERE (".$whereCitekey." OR ".$whereKeywords." OR ".$whereYear." OR ".$whereTitle.") AND $published";
			}else{
				$finalQuery = $query;
			}
		}else{
			$finalQuery = 'SELECT '.$db->nameQuote('id').', '.$db->nameQuote('pubtype').' FROM '.$publicationTable.' WHERE '.$db->nameQuote('published').' = '.$db->Quote(1); 
		}
		
		$finalQuery .= " LIMIT $limitstart, $limit";
		
		$db->setQuery($finalQuery);
		$result = $db->loadAssocList();
		if($result){
			foreach($result as $r){
				$pub = JResearchPublication::getSubclassInstance($r['pubtype']);
				$pub->load($r['id']);
				$records[] = $pub;
			}
		}
		return $records;
	}
	
	/**
	 * Returns the average value of the indicated field for the data returned by the 
	 * model. Items with null values in the specified field are not considered in the 
	 * average. This function assumes method getData was previously invoked and always
	 * return a float even if the data values are integers.
	 *
	 * @param string $fieldname The name of the field used for the average. If the field 
	 * does not correspond to a numeric value, the results are neither predictable nor
	 * trustable.
	 * @param boolean $ignoreZeros If true, items with zero values are not considered in 
	 * the average.
	 * @param float Calculated average. null if there is no data to analyze.
	 */
	public function getAverage($fieldname, $ignoreZeros=true){
		$result = 0.0;
		$n = 0;
		if(empty($this->_items))
			return null;
			
		foreach($this->_items as $item){
			if(isset($item->$fieldname)){
				$value = (float)trim($item->$fieldname);				
				if($value === 0.0){
					if(!$ignoreZeros){
						$result += $value;
						$n++;
					}
				}else{
					$result += $value;
					$n++;
				}
			}
		}
		
		if($n == 0)
			return null;
			
		return $result / $n;
	}
}
?>
