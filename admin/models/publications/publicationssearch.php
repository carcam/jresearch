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
class JResearchModelPublicationsSearch extends JResearchModelList{
	
	
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
	protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false){		
		$db =& JFactory::getDBO();		
		if($memberId === null){	
			$resultQuery = 'SELECT '.$db->nameQuote('id').' FROM '.$db->nameQuote($this->_tableName); 	
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
	* Returns the items that match a publications search. 
	*
	* @return 	array
	*/
	public function getData($memberId = null, $onlyPublished = false, $paginate = false){
		if($this->_items != null){
			$this->_items = array();		
			$db = JFactory::getDBO();
			$query = $this->_buildQuery();
			$db->setQuery($query);
			$ids = $db->loadResultArray();
			foreach($ids as $id){
				$pub = JResearchPublication::getById($id);
				$this->_items[] = $pub;
			}
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
		
		$db = JFactory::getDBO();
		//Obtention of search key
		$key = JRequest::getVar('key');
		$keyfield0 = JRequest::getVar('keyfield0');

		$whereKeyClause = array();
		$escapedKey = $db->Quote( '%'.$db->getEscaped( $key, true ).'%', false );
		$whereKeyClause['title_word'] = "LOWER(title) LIKE $escapedKey";
		$whereKeyClause['abstract_word'] = "LOWER(abstract) LIKE $escapedKey";
		$whereKeyClause['keywords'] = "LOCATE($escapedKey, LOWER(keywords)) > 0";
		if($keyfield0 == 'all'){
			$where[] = '('.explode(' OR ', $whereKeyClause).')';
		}else{
			$where[] = $whereKeyClause[$keyfield0];
		}
		
		// prepare the WHERE clause
		$where[] = $db->nameQuote('published').' = 1 ';
		$where[] = $db->nameQuote('internal').' = 1 ';
			
		// operators
		$op1 = JRequest::getVar('op1');		
		$op2 = JRequest::getVar('op2');		
		$op3 = JRequest::getVar('op3');		
					
		$key1 = JRequest::getVar('key1');
		$key2 = JRequest::getVar('key2');
		$key3 = JRequest::getVar('key3');		
		
		$keyfield1 = JRequest::getVar('keyfield1');
		$keyfield2 = JRequest::getVar('keyfield2');
		$keyfield3 = JRequest::getVar('keyfield3');		
		$whereAdditionals = '';
		
		if(!empty($key1)){
			$where1 = array();
			$whereKeyClause1 = array();
			$escapedKey1 = $db->Quote( '%'.$db->getEscaped( $key1, true ).'%', false );
			$whereKeyClause1['title_word'] = "LOWER(title) LIKE $escapedKey1";
			$whereKeyClause1['abstract_word'] = "LOWER(abstract) LIKE $escapedKey1";
			$whereKeyClause1['keywords'] = "LOCATE($escapedKey1, LOWER(keywords)) > 0";
			if($keyfield1 == 'all'){
				$whereAdditionals .= ' '.$op1.' ('.explode(' OR ', $whereKeyClause1).')';
			}else{
				$whereAdditionals .= ' '.$op1.' '.$whereKeyClause1[$keyfield1];
			}
		}
		
		if(!empty($key2)){
			$whereKeyClause2 = array();
			$escapedKey2 = $db->Quote( '%'.$db->getEscaped( $key2, true ).'%', false );
			$whereKeyClause2['title_word'] = "LOWER(title) LIKE $escapedKey2";
			$whereKeyClause2['abstract_word'] = "LOWER(abstract) LIKE $escapedKey2";
			$whereKeyClause2['keywords'] = "LOCATE($escapedKey2, LOWER(keywords)) > 0";
			if($keyfield2 == 'all'){
				$whereAdditionals .= ' '.$op2.' ('.explode(' OR ', $whereKeyClause2).')';
			}else{
				$whereAdditionals .= ' '.$op2.' '.$whereKeyClause2[$keyfield2];
			}
			
		}

		if(!empty($key3)){
			$whereKeyClause3 = array();
			$escapedKey3 = $db->Quote( '%'.$db->getEscaped( $key3, true ).'%', false );
			$whereKeyClause3['title_word'] = "LOWER(title) LIKE $escapedKey3";
			$whereKeyClause3['abstract_word'] = "LOWER(abstract) LIKE $escapedKey3";
			$whereKeyClause3['keywords'] = "LOCATE($escapedKey3, LOWER(keywords)) > 0";
			if($keyfield3 == 'all'){
				$whereAdditionals .= ' '.$op3.' ('.explode(' OR ', $whereKeyClause3).')';
			}else{
				$whereAdditionals .= ' '.$op3.' '.$whereKeyClause3[$keyfield3];
			}
			
		}		
		
		if(!empty($whereAdditionals)){
			$where[] = $whereAdditionals;
		}

		$with_abstract = JRequest::getVar('with_abstract');
		if($with_abstract == 'on'){
			$where[] = "NOT ISNULL(abstract) AND NOT abstract = ''";
		}
		
		$pubtype = JRequest::getVar('pubtype');
		if($pubtype != '0'){
			$where[] = 'pubtype = '.$db->nameQuote($pubtype);			
		}
		
		$language = JRequest::getVar('language');
		if($language != '0'){
			$where[] = 'id_language = '.$db->nameQuote($language);			
		}
		
		$status = JRequest::getVar('status');
		if($status != '0'){
			$where[] = 'status = '.$db->nameQuote($language);			
		}
		
		$from_year = JRequest::getInt('from_year');
		$from_month = JRequest::getInt('from_month');
		$from_day = JRequest::getInt('from_day');
		$to_year = JRequest::getInt('to_year');
		$to_month = JRequest::getInt('to_month');		
		$to_day = JRequest::getInt('to_day');
		
		$fromDate = new JDate();
		if(empty($from_month) || empty($from_day)){
			
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
		require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'publications.php');
		foreach($result as $key => $author){
			$components = JResearchPublicationsHelper::getAuthorComponents($author['name']);
			$value = (isset($components['von'])?$components['von'].' ':'').$components['lastname'].', '.$components['firstname'].(isset($components['jr'])?' '.$components['jr']:'');
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