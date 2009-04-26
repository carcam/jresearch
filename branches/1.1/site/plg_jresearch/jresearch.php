<?php
/**
 * @version			$Id$
* @package		JResearch
* @subpackage	Plugins
 * @copyright		Copyright (C) 2008 Luis Galarraga.
 * @license			GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.event.plugin');


//JPlugin::loadLanguage( 'plg_search_jresearch' );



/**
 * JResearch Search Plugin Class. Allows to include JResearch records like projects, theses, 
 * publications, research areas and staff members in Joomla searches.
 *
 * @license    GNU/GPL
 */
class plgSearchJResearch extends JPlugin{

	/**
	 * Maximum number of items this plugin can retreive.
	 *
	 * @var int 
	 */
	private $limit;

	/**
	 * Class Constructor
	 *
	 * @param The object to observe
	 */
	function plgSearchJResearch(& $subject) {
        parent::__construct($subject);
        $this->limit = 0;	
    }
    
	/**
	 * Used to obtain the array of entity categories managed by this plugin.
	 * @return array An array of search areas
	 */
	function &onSearchAreas()
	{
		static $areas = array(
			'cooperations' => 'Cooperations',
			'facilities' => 'Facilities',
			'projects' => 'Research Projects',
			'publications' => 'Publications',
			'theses' => 'Degree Theses',
			'researchAreas' => 'Research Areas',
			'staff' => 'Staff Members'
		);
		return $areas;
	}
	
	/**
	* JResearch Related Information Search method. It includes information of research projects, 
	* publications, degree theses, research areas and staff members.
	*
	* The sql returns the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string mathcing option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	*/
	function onSearch( $text, $phrase='', $ordering='', $areas=null ){
		$results = array();
		$finalResult = array();
	
		if (is_array( $areas )) {
			if (!array_intersect( $areas, array_keys( $this->onSearchAreas() ) )) {
				return array();
			}
		}
	
		// load plugin params info
	 	$plugin =& JPluginHelper::getPlugin('search', 'jresearch');
	 	$pluginParams = new JParameter( $plugin->params );
	
		$this->limit = $pluginParams->def( 'search_limit', 50 );
	
		// If the search text is empty, return nothing
		$text = trim( $text );
		if ($text == '') {
			return array();
		}
	
		if(!isset($areas)){
			$results[] = $this->searchCooperations($text, $phrase, $ordering);
			$results[] = $this->searchFacilities($text, $phrase, $ordering);
			$results[] = $this->searchPublications($text, $phrase, $ordering);
			$results[] = $this->searchProjects($text, $phrase, $ordering);
			$results[] = $this->searchTheses($text, $phrase, $ordering);
			$results[] = $this->searchStaff($text, $phrase, $ordering);
			$results[] = $this->searchResearchAreas($text, $phrase, $ordering);		
		}else{
			foreach($areas as $area){
				if($area == 'cooperations')
					$results[] = $this->searchCooperations($text, $phrase, $ordering);
				elseif($area == 'facilities')
					$results[] = $this->searchFacilities($text, $phrase, $ordering);
				elseif($area == 'publications')
					$results[] = $this->searchPublications($text, $phrase, $ordering);
				elseif($area == 'projects')
					$results[] = $this->searchProjects($text, $phrase, $ordering);
				elseif($area == 'theses')
					$results[] = $this->searchTheses($text, $phrase, $ordering);
				elseif($area == 'staff')
					$results[] = $this->searchStaff($text, $phrase, $ordering);
				else
					$results[] = $this->searchResearchAreas($text, $phrase, $ordering);
			}
		}
	
		// Merge the results 
		foreach($results as $partial)
			$finalResult = array_merge($finalResult, $partial);
		
	
		return $finalResult;
	
	}

	/**
	* JResearch Research Areas Search method. 
	*
	* The returns the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string mathcing option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	*/
	private function searchResearchAreas($text, $phrase='', $ordering=''){	
		if($this->limit <= 0)
			return array();
	
		// Get the database object
		$db = &JFactory::getDBO();
	
		// Section name	
		$section = JText::_( 'Research Area' );
		switch ( $ordering ) {
			case 'alpha':
				$order = 'r.name ASC';
				break;
			case 'category':
			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'r.name DESC';
		}
		
		switch($phrase){
			case 'exact':
				$key = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$whereClause = "published = 1 AND (LOWER( description) LIKE $key OR LOWER( name ) LIKE $key)";
				break;
			case 'all':
			case 'any':
				// Get the words that compound the text
				$words = explode( ' ', $text );
				$whereClause = "published = 1 AND (";
				// Depending of the phrase we get a different behaviour
				$operator = ($phrase == 'all'? 'AND':'OR');
				$i = 0;
				$n = count($words);
				foreach($words as $word){
					$word = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$whereClause.= " (LOWER(description) LIKE $word OR LOWER( name ) LIKE $word) ";
					if($i <= $n-2)
						$whereClause .= $operator;
					$i++;
				}
				$whereClause .= ' )';
		}
		$section = $db->Quote($section, false);
		$query = "SELECT r.id AS id, r.name AS title, CONCAT_WS( '/', r.name, $section ) AS section, '' AS created, '2' AS browsernav, SUBSTRING_INDEX(r.description, '<hr id=\"system-readmore\" />', 1) AS text FROM #__jresearch_research_area r WHERE $whereClause ORDER BY $order";
		$results = $db->setQuery( $query, 0, $this->limit );
		$results = $db->loadObjectList();
	
		if(isset($results)){
			foreach($results as $key => $item){
				$results[$key]->href = "index.php?option=com_jresearch&view=researcharea&task=show&id=".$results[$key]->id;
			}
		}
		// We just reduce the limit
		$n = count($results);
		$this->limit -= $n;
		return $results;
	}

	/**
	* JResearch Projects Search method. 
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string mathcing option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	*/
	private function searchProjects($text, $phrase='', $ordering=''){
		$section = JText::_( 'Project' );
		// Get the database object
		$db = &JFactory::getDBO();
	
		if($this->limit <= 0)
			return array();
	
		switch ( $ordering ) {
			case 'alpha':
				$order = 'p.title ASC';
				break;
			case 'category':
				$order = 'r.id ASC, p.title ASC';
				break;
			case 'popular':
			case 'newest':
				$order = 'p.start_date DESC';
				break;
			case 'oldest':
				$order = 'p.start_date ASC';
				break;
			default:
				$order = 'p.title DESC';
		}
	
		switch($phrase){
			case 'exact':
				$key = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$whereClause = "p.id_research_area = r.id AND p.published = 1 AND ( LOWER( p.description ) LIKE $key OR LOWER( p.title ) LIKE $key)";
				break;
			case 'all':
			case 'any':
				// Get the words that compound the text
				$words = explode( ' ', $text );
				$whereClause = "p.id_research_area = r.id AND p.published = 1 AND (";
				// Depending of the phrase we get a different behaviour
				$operator = ($phrase == 'all'? 'AND':'OR');
				$i = 0;
				$n = count($words);
				foreach($words as $word){
					$word = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$whereClause.= " ( LOWER( p.description ) LIKE $word OR LOWER( p.title ) LIKE $word) ";
					if($i <= $n-2)
						$whereClause .= $operator;
					$i++;
				}
				$whereClause .= ' )';
		}
	
		$section = $db->Quote($section, false);
		$query = "SELECT p.id AS id, p.title AS title, CONCAT_WS( '/', r.name, $section ) AS section, '' AS created, '2' AS browsernav, SUBSTRING_INDEX(p.description, '<hr id=\"system-readmore\" />', 1) AS text FROM #__jresearch_project p INNER JOIN #__jresearch_research_area r WHERE $whereClause ORDER BY $order";
		
		$results = $db->setQuery( $query, 0, $this->limit );
		$results = $db->loadObjectList();

		if(isset($results)){
			foreach($results as $key => $item){
				$results[$key]->href = "index.php?option=com_jresearch&view=project&task=show&id=".$results[$key]->id;
			}
		}
		// We just reduce the limit
		$n = count($results);
		$this->limit -= $n;
		return $results;
	}

	/**
	* JResearch Publications Search method. 
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string mathcing option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	*/
	private function searchPublications($text, $phrase='', $ordering=''){
		if($this->limit <= 0)
			return array();
		
		$section = JText::_( 'Publication' );
		// Get the database object
		$db = &JFactory::getDBO();
	
		switch ( $ordering ) {
			case 'alpha':
				$order = 'p.title ASC';
				break;
			case 'category':
				$order = 'r.id ASC, p.title ASC';
				break;
			case 'newest':
				$order = 'p.year DESC, p.title ASC';
				break;
			case 'oldest':
				$order = 'p.year ASC, p.title ASC';
				break;
			case 'popular':
			default:
				$order = 'p.title DESC';
		}
	
		switch($phrase){
			case 'exact':
				$key = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$qtext = strtolower($db->Quote($text));
				$whereClause = "r.id = p.id_research_area AND p.published = 1 AND p.internal = 1 AND (LOWER( p.title ) LIKE $key OR LOWER( p.abstract ) LIKE $key OR LOWER( p.comments ) LIKE $key OR LOCATE($qtext, LOWER(p.keywords)) > 0)";
				break;
			case 'all':
			case 'any':
				// Get the words that compound the text
				$words = explode( ' ', $text );
				$whereClause = "r.id = p.id_research_area AND p.published = 1 AND p.internal = 1 AND (";
				// Depending of the phrase we get a different behaviour
				$operator = ($phrase == 'all'? 'AND':'OR');
				$i = 0;
				$n = count($words);
				foreach($words as $word){
					$unscapedWord = $word;
					$qtext = $db->Quote(strtolower($unscapedWord));
					$word = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$whereClause.= " (LOWER(p.title) LIKE $word OR LOWER( p.abstract ) LIKE $word OR LOWER( p.comments ) LIKE $word OR LOCATE($qtext, LOWER(p.keywords)) > 0)";
					if($i <= $n-2)
						$whereClause .= $operator;
					$i++;
				}
				$whereClause .= ' )';
		}
		$section = $db->Quote($section, false);
		$query = "SELECT p.id as id, p.title AS title, CONCAT_WS( '/', r.name, $section) AS section, '' AS created, '2' AS browsernav, CONCAT_WS('\n', p.abstract, p.comments) AS text FROM #__jresearch_publication p INNER JOIN #__jresearch_research_area r WHERE $whereClause ORDER BY $order";
		$db->setQuery( $query, 0, $this->limit );
		$results = $db->loadObjectList();

		if(isset($results)){
			foreach($results as $key => $item){
				$results[$key]->href = "index.php?option=com_jresearch&view=publication&task=show&id=".$results[$key]->id;
			}
		}
		// We just reduce the limit
		$n = count($results);
		$this->limit -= $n;
		return $results;
	}

	/**
	* JResearch Theses Search method. 
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string mathcing option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	*/
	private function searchTheses($text, $phrase='', $ordering=''){
		$section = JText::_( 'Thesis' );
		// Get database object
		$db = &JFactory::getDBO();
	
		if($this->limit <= 0)
			return array();
	
		switch ( $ordering ) {
			case 'alpha':
				$order = 't.title ASC';
				break;
			case 'category':
				$order = 'r.id ASC, t.title ASC';
				break;
			case 'newest':
			case 'oldest':
			case 'popular':
			default:
				$order = 't.title DESC';
		}
	
		switch($phrase){
			case 'exact':
				$key = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$whereClause = "t.id_research_area = r.id AND t.published =1 AND ( LOWER( t.title ) LIKE $key OR LOWER( t.description ) LIKE $key )";
				break;
			case 'all':
			case 'any':
				// Get the words that compound the text
				$words = explode( ' ', $text );
				$whereClause = "t.id_research_area = r.id AND t.published =1 AND (";
				// Depending of the phrase we get a different behaviour
				$operator = ($phrase == 'all'? 'AND':'OR');
				$i = 0;
				$n = count($words);
				foreach($words as $word){
					$word = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$whereClause.= " (LOWER(t.title) LIKE $word OR LOWER( t.title ) LIKE $word OR LOWER(t.description) like $word ) ";
					if($i <= $n-2)
						$whereClause .= $operator;
					$i++;
				}
				$whereClause .= ' )';
		}
	
		$section = $db->Quote($section, false);
		$query = "SELECT t.title AS title, CONCAT_WS( '/', r.name, $section ) AS section, '' AS created, '2' AS browsernav, SUBSTRING_INDEX(t.description, '<hr id=\"system-readmore\" />', 1) AS text FROM #__jresearch_thesis t INNER JOIN #__jresearch_research_area r WHERE $whereClause ORDER BY $order";
		$results = $db->setQuery( $query, 0, $this->limit );
		$results = $db->loadObjectList();
		
		
		if(isset($results)){
			foreach($results as $key => $item){
				$results[$key]->href = "#";
			}
		}
		// We just reduce the limit
		$n = count($results);
		$this->limit -= $n;
		return $results;
	
	}

	/**
	* JResearch Staff Member Search method. 
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string mathcing option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	*/
	private function searchStaff($text, $phrase='', $ordering=''){
		$section = JText::_( 'Staff Member' );
		$db = &JFactory::getDBO();
		switch ( $ordering ) {
			case 'alpha':
				$order = 'm.lastname ASC, m.name ASC';
				break;
			case 'category':
				$order = 'r.id ASC, m.lastname ASC, m.name ASC';
			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'm.lastname DESC';
		}
		
		switch($phrase){
			case 'exact':
				$key = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$whereClause = "m.id_research_area = r.id AND m.published = 1 AND (LOWER( m.firstname ) LIKE $key OR LOWER( m.lastname ) LIKE $key OR LOWER( m.description ) LIKE $key)";
				break;
			case 'all':
			case 'any':
				// Get the words that compound the text
				$words = explode( ' ', $text );
				$whereClause = "m.id_research_area = r.id AND m.published = 1 AND (";
				// Depending of the phrase we get a different behaviour
				$operator = ($phrase == 'all'? 'AND':'OR');
				$i = 0;
				$n = count($words);
				foreach($words as $word){
					$word = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$whereClause.= " (LOWER(m.firstname) LIKE $word OR LOWER( m.lastname ) LIKE $word OR LOWER(m.description) LIKE $word) ";
					if($i <= $n-2)
						$whereClause .= $operator;
					$i++;
				}
				$whereClause .= ' )';
		}
		$section = $db->Quote($section, false);
		$query = "SELECT m.id as id, CONCAT_WS(' ', m.firstname, m.lastname) AS title, CONCAT_WS( '/', r.name, $section ) AS section, '' AS created, '2' AS browsernav, m.description AS text FROM #__jresearch_member m INNER JOIN #__jresearch_research_area r WHERE $whereClause ORDER BY $order";
		$results = $db->setQuery( $query, 0, $this->limit );
		$results = $db->loadObjectList();
		
		if(isset($results)){
			foreach($results as $key => $item){
				$results[$key]->href = "index.php?option=com_jresearch&view=member&task=show&id=".$results[$key]->id;
			}
		}
		// We just reduce the limit
		$n = count($results);
		$this->limit -= $n;
		return $results;
	}
	
	/**
	* JResearch Cooperations Search method. 
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string matching option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	*/
	private function searchCooperations($text, $phrase='', $ordering='')
	{
		$section = JText::_( 'Cooperations' );
		$db = &JFactory::getDBO();
		switch ( $ordering ) {
			case 'alpha':
				$order = 'c.name ASC';
				break;
			case 'category':
			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'c.ordering ASC';
				break;
		}
		
		switch($phrase)
		{
			case 'exact':
				$key = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$whereClause = "c.published = 1 AND (LOWER(c.name) LIKE $key OR LOWER( c.url ) LIKE $key OR LOWER( c.description ) LIKE $key)";
				break;
			case 'all':
			case 'any':
				// Get the words that compound the text
				$words = explode( ' ', $text );
				$whereClause = "c.published = 1 AND (";
				
				// Depending of the phrase we get a different behaviour
				$operator = ($phrase == 'all'? 'AND':'OR');
				
				$i = 0;
				$n = count($words);
				foreach($words as $word)
				{
					$word = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$whereClause.= " (LOWER(c.name) LIKE $word OR LOWER( c.url ) LIKE $word OR LOWER(c.description) LIKE $word) ";
					if($i <= $n-2)
						$whereClause .= $operator;
					$i++;
				}
				$whereClause .= ' )';
		}
		
		$section = $db->Quote($section, false);
		$query = "SELECT c.id as id, c.name AS title, CONCAT_WS( '/', c.name, $section ) AS section, '' AS created, '2' AS browsernav, c.description AS text FROM #__jresearch_cooperations c WHERE $whereClause ORDER BY $order";
		$results = $db->setQuery( $query, 0, $this->limit );
		$results = $db->loadObjectList();
		
		if(isset($results)){
			foreach($results as $key => $item){
				$results[$key]->href = "index.php?option=com_jresearch&view=cooperation&task=show&id=".$results[$key]->id;
			}
		}
		// We just reduce the limit
		$n = count($results);
		$this->limit -= $n;
		return $results;
	}
	
	/**
	* JResearch Facilities Search method. 
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string matching option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	*/
	private function searchFacilities($text, $phrase='', $ordering='')
	{
		$section = JText::_( 'Facilities' );
		$db = &JFactory::getDBO();
		switch ( $ordering )
		{
			case 'alpha':
				$order = 'f.name ASC';
				break;
			case 'category':
				$order = 'r.id ASC, f.name ASC';
			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'f.ordering ASC';
		}
		
		switch($phrase){
			case 'exact':
				$key = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$whereClause = "f.id_research_area = r.id AND f.published = 1 AND (LOWER( f.name ) LIKE $key OR LOWER( f.description ) LIKE $key)";
				break;
			case 'all':
			case 'any':
				// Get the words that compound the text
				$words = explode( ' ', $text );
				$whereClause = "f.id_research_area = r.id AND f.published = 1 AND (";
				
				// Depending of the phrase we get a different behaviour
				$operator = ($phrase == 'all'? 'AND':'OR');
				
				$i = 0;
				$n = count($words);
				foreach($words as $word)
				{
					$word = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$whereClause.= " (LOWER(f.name) LIKE $word OR LOWER(f.description) LIKE $word) ";
					if($i <= $n-2)
						$whereClause .= $operator;
					$i++;
				}
				$whereClause .= ' )';
		}
		
		$section = $db->Quote($section, false);
		$query = "SELECT f.id as id, f.name AS title, CONCAT_WS( '/', r.name, $section ) AS section, '' AS created, '2' AS browsernav, f.description AS text FROM #__jresearch_facilities f INNER JOIN #__jresearch_research_area r WHERE $whereClause ORDER BY $order";
		$results = $db->setQuery( $query, 0, $this->limit );
		$results = $db->loadObjectList();
			
		if(isset($results)){
			foreach($results as $key => $item){
				$results[$key]->href = "index.php?option=com_jresearch&view=facility&task=show&id=".$results[$key]->id;
			}
		}
		// We just reduce the limit
		$n = count($results);
		$this->limit -= $n;
		return $results;
	}
	
	private function debugQuery($query){
		$test = fopen('debug', 'a+');
		fwrite($test, $query."\n");
		fclose($test);
	}
	

	
}
?>