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
		$limit = (int)$this->getState('limit');
		$resultQuery .= ' LIMIT '.(int)$this->getState('limitstart').' , '.$this->getState('limit');					
		
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
		$items = array();
		$db = JFactory::getDBO();
		$query = $this->_buildQuery();
		$db->setQuery($query);
		$ids = $db->loadResultArray();
		foreach($ids as $id){
			$pub = JResearchPublication::getById($id);
			$items[] = $pub;
		}
		$this->updatePagination();
	
		return $items;

	}
	
	/**
	* Build the ORDER part of a query.
	*/
	private function _buildQueryOrderBy(){		
		global $mainframe;
		$output = '';
		$first_filter = $mainframe->getUserStateFromRequest('publicationssearchorder_by1', 'order_by1', 'date_descending');
		$second_filter = $mainframe->getUserStateFromRequest('publicationssearchorder_by2', 'order_by2', 'title');		
		
		switch($first_filter){
			case 'date_descending':
				$first_clause = 'year DESC, month DESC, day DESC';
				break;
			case 'date_ascending':
				$first_clause = 'year ASC, month ASC, day ASC';
				break;
			case 'title': default:
				$first_clause = 'title ASC';				
				break;	
					
		}
		
		switch($second_filter){
			case 'date_descending':
				$second_clause = 'year DESC, month DESC, day DESC';
				break;
			case 'date_ascending':
				$second_clause = 'year ASC, month ASC, day ASC';
				break;
			case 'title': default:
				$second_clause = 'title ASC';				
				break;	
					
		}
		
		//Validate order direction	
		return ' ORDER BY '.$first_clause.($first_clause != $second_clause? ', '.$second_clause:'');
	}	
	
	/**
	* Build the WHERE part of a query
	*/
	private function _buildQueryWhere(){
		global $mainframe;
		
		$db = JFactory::getDBO();
		//Obtention of search key		
		$key = $mainframe->getUserStateFromRequest('publicationssearchkey', 'key');
		$keyfield0 = $mainframe->getUserStateFromRequest('publicationssearchkeyfield0', 'keyfield0', 'all');

		$whereKeyClause = array();
		$escapedKey = $db->Quote( '%'.$db->getEscaped( strtolower($key), true ).'%', false );
		$quotedKey = $db->Quote($db->getEscaped( strtolower($key), true ));
		$whereKeyClause['title_word'] = "LOWER(title) LIKE $escapedKey";
		$whereKeyClause['heading_word'] = "LOWER(headings) LIKE $escapedKey";
		$whereKeyClause['abstract_word'] = "LOWER(abstract) LIKE $escapedKey";
		$whereKeyClause['keywords'] = "LOCATE($quotedKey, LOWER(keywords)) > 0";
		$ids = $this->_getAuthorPublicationIds(trim($key));			
		if(count($ids) > 0)
			$whereKeyClause['author_name'] = $db->nameQuote('id').' IN ('.implode(',', $ids).')';
		else
			$whereKeyClause['author_name'] = '0 = 1';	
			
		if($keyfield0 == 'all'){
			$where[] = '('.implode(' OR ', $whereKeyClause).')';
		}else{
			$where[] = $whereKeyClause[$keyfield0];			
		}
		
		
		
		// prepare the WHERE clause
		$where[] = $db->nameQuote('published').' = '.$db->Quote(1);
		$where[] = $db->nameQuote('internal').' = '.$db->Quote(1);
			
		// operators
		$op1 = $mainframe->getUserStateFromRequest('publicationssearchop1', 'op1');	
		$op2 = $mainframe->getUserStateFromRequest('publicationssearchop2', 'op2');	
		$op3 = $mainframe->getUserStateFromRequest('publicationssearchop3', 'op3');;		
					
		$key1 = $mainframe->getUserStateFromRequest('publicationssearchkey1', 'key1');
		$key2 = $mainframe->getUserStateFromRequest('publicationssearchkey2', 'key2');
		$key3 = $mainframe->getUserStateFromRequest('publicationssearchkey3', 'key3');		
		
		$keyfield1 = $mainframe->getUserStateFromRequest('publicationssearchkeyfield1', 'keyfield1');
		$keyfield2 = $mainframe->getUserStateFromRequest('publicationssearchkeyfield2', 'keyfield2');
		$keyfield3 = $mainframe->getUserStateFromRequest('publicationssearchkeyfield3', 'keyfield3');		
		$whereAdditionals = '';
		
		if(!empty($key1)){
			$where1 = array();
			$whereKeyClause1 = array();
			$escapedKey1 = $db->Quote( '%'.$db->getEscaped( strtolower($key1), true ).'%', false );
			$quotedKey1 = $db->Quote($db->getEscaped( strtolower($key1), true ));			
			$whereKeyClause1['title_word'] = "LOWER(title) LIKE $escapedKey1";
			$whereKeyClause1['abstract_word'] = "LOWER(abstract) LIKE $escapedKey1";
			$whereKeyClause1['heading_word'] = "LOWER(headings) LIKE $escapedKey1";
			$whereKeyClause1['keywords'] = "LOCATE($quotedKey1, LOWER(keywords)) > 0";
			$ids1 = $this->_getAuthorPublicationIds(trim($key1));			

			if(count($ids1) > 0)
				$whereKeyClause1['author_name'] = $db->nameQuote('id').' IN ('.implode(',', $ids1).')';
			else
				$whereKeyClause1['author_name'] = '0 = 1';	
				
			$op1 = ($op1 == 'not')? 'and '.$op1:$op1;	
			if($keyfield1 == 'all'){
				$whereAdditionals .= ' '.$op1.' ('.implode(' OR ', $whereKeyClause1).')';
			}else{
				$whereAdditionals .= ' '.$op1.' '.$whereKeyClause1[$keyfield1];
			}
		}
		
		if(!empty($key2)){
			$whereKeyClause2 = array();
			$escapedKey2 = $db->Quote( '%'.$db->getEscaped( strtolower($key2), true ).'%', false );
			$quotedKey2 = $db->Quote($db->getEscaped( strtolower($key2), true ));		
			$whereKeyClause2['title_word'] = "LOWER(title) LIKE $escapedKey2";
			$whereKeyClause2['abstract_word'] = "LOWER(abstract) LIKE $escapedKey2";
			$whereKeyClause2['keywords'] = "LOCATE($quotedKey2, LOWER(keywords)) > 0";
			$whereKeyClause2['heading_word'] = "LOWER(headings) LIKE $escapedKey2";			
			$ids2 = $this->_getAuthorPublicationIds(trim($key2));			
			if(count($ids2) > 0)
				$whereKeyClause2['author_name'] = $db->nameQuote('id').' IN ('.implode(',', $ids2).')';
			else
				$whereKeyClause2['author_name'] = '0 = 1';			
			
			$op2 = ($op2 == 'not')? 'and '.$op2:$op2;				
			if($keyfield2 == 'all'){
				$whereAdditionals .= ' '.$op2.' ('.implode(' OR ', $whereKeyClause2).')';
			}else{
				$whereAdditionals .= ' '.$op2.' '.$whereKeyClause2[$keyfield2];
			}
			
		}

		if(!empty($key3)){
			$whereKeyClause3 = array();
			$escapedKey3 = $db->Quote( '%'.$db->getEscaped( strtolower($key3), true ).'%', false );
			$quotedKey3 = $db->Quote($db->getEscaped( strtolower($key3), true ));		
			$whereKeyClause3['title_word'] = "LOWER(title) LIKE $escapedKey3";
			$whereKeyClause3['abstract_word'] = "LOWER(abstract) LIKE $escapedKey3";
			$whereKeyClause3['keywords'] = "LOCATE($quotedKey3, LOWER(keywords)) > 0";
			$whereKeyClause3['heading_word'] = "LOWER(headings) LIKE $escapedKey3";			
			
			$op3 = ($op3 == 'not')? 'and '.$op3:$op3;			
			if($keyfield3 == 'all'){
				$whereAdditionals .= ' '.$op3.' ('.implode(' OR ', $whereKeyClause3).')';
			}else{
				$whereAdditionals .= ' '.$op3.' '.$whereKeyClause3[$keyfield3];
			}
			
		}		
		

		$with_abstract = $mainframe->getUserStateFromRequest('publicationssearchwith_abstract', 'with_abstract');
		if($with_abstract == 'on'){
			$where[] = "NOT ISNULL(abstract)";
		}
		
		$pubtype = $mainframe->getUserStateFromRequest('publicationssearchpubtype', 'pubtype');
		if($pubtype != '0' && $pubtype != null){
			$where[] = 'pubtype = '.$db->Quote($pubtype);			
		}
		
		$language = $mainframe->getUserStateFromRequest('publicationssearchlanguage', 'language');;
		if($language != '0' && $language != null){
			$where[] = 'id_language = '.$db->Quote($language);			
		}
		
		$status = $mainframe->getUserStateFromRequest('publicationssearchkeystatus', 'status');
		if($status != '0' && $status != null){
			$where[] = 'status = '.$db->Quote($status);			
		}
		
		$from_year = $mainframe->getUserStateFromRequest('publicationssearchfrom_year', 'from_year');
		$from_month = $mainframe->getUserStateFromRequest('publicationssearchfrom_month', 'from_month');
		$from_day = $mainframe->getUserStateFromRequest('publicationssearchfrom_day', 'from_day');
		$to_year = $mainframe->getUserStateFromRequest('publicationssearchfrom_year', 'to_year');
		$to_month = $mainframe->getUserStateFromRequest('publicationssearchfrom_month', 'to_month');		
		$to_day = $mainframe->getUserStateFromRequest('publicationssearchto_day', 'to_day');
		$date_field = $mainframe->getUserStateFromRequest('publicationssearchdatefield', 'date_field');
		
		if(!empty($from_year)){
			if(empty($from_month)){
				$from_month = 1;
			}
			
			if(empty($from_day)){
				$from_day = 1;
			}
			$fromDate = new JDate(mktime(0,0,0, $from_month, $from_day, $from_year));
			if($date_field == 'publication_date')
				$where[] = " CONVERT(CONCAT_WS('-', '$from_year', $from_month', '$from_day'), DATE) <= ".$fromDate.toMySQL();	
			else
				$where[] = " created >= ".$db->Quote($fromDate->toMySQL());		
		}
		
		if(!empty($to_year)){
			if(empty($to_month)){
				$to_month = 1;
			}		
			if(empty($to_day)){
				$to_day = 1;
			}
			$toDate = new JDate(mktime(0,0,0,$to_month, $to_day, $to_year));
			if($date_field == 'publication_date')
				$where[] = " CONVERT(CONCAT_WS('-', '$to_year', $to_month', '$to_day'), DATE) >= ".$toDate.toMySQL();
			else
				$where[] = " created <= ".$db->Quote($toDate->toMySQL());		
		}		
		
		$recommended = $mainframe->getUserStateFromRequest('publicationssearchrecommended', 'recommended');
		if($recommended == 'on')
			$where[] = ' recommended = '.$db->Quote(1);
		
		return (count($where)) ? ' WHERE '.implode(' AND ', $where).' '.$whereAdditionals : '';
			
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