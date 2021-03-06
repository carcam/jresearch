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

$lang = JFactory::getLanguage();
$lang->load('com_jresearch');

define('COOPERATIONS', JText::_('JRESEARCH_COOPERATIONS'));
define('FACILITIES', JText::_('JRESEARCH_FACILITIES'));
define('PROJECTS', JText::_('JRESEARCH_PROJECTS'));
define('THESES', JText::_('JRESEARCH_THESES'));
define('PUBLICATIONS', JText::_('JRESEARCH_PUBLICATIONS'));
define('RESEARCHAREAS', JText::_('JRESEARCH_RESEARCH_AREAS'));
define('STAFF', JText::_('JRESEARCH_STAFF'));


/**
 * JResearch Search Plugin Class. Allows to include JResearch records like projects, theses, 
 * publications, research areas and staff members in Joomla searches.
 *
 * @license    GNU/GPL
 */
class plgSearchJResearch_Search extends JPlugin{
	
    /**
     * Search limit 
     */
    private $limit;

    /**
     * Used to obtain the array of entity categories managed by this plugin.
     * @return array An array of search areas
     */
    function onContentSearchAreas()
    {
        static $areas = array(
                'projects' => PROJECTS,
                'publications' => PUBLICATIONS,
                'researchAreas' => RESEARCHAREAS,
                'staff' => STAFF
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
    function onContentSearch( $text, $phrase='', $ordering='', $areas=null ){
        $results = array();
        $finalResult = array();

        if (is_array( $areas )) {
            if (!array_intersect( $areas, array_keys( $this->onContentSearchAreas() ) )) {
                return array();
            }
        }

        // load plugin params info
        $pluginParams = $this->params;
        $this->limit = $pluginParams->def( 'search_limit', 50 );

        // If the search text is empty, return nothing
        $text = trim( $text );
        if ($text == '') {
            return array();
        }

        if(!isset($areas)){
            $results[] = $this->searchPublications($text, $phrase, $ordering);
            $results[] = $this->searchProjects($text, $phrase, $ordering);
            $results[] = $this->searchStaff($text, $phrase, $ordering);
            $results[] = $this->searchResearchAreas($text, $phrase, $ordering);		
        }else{
            foreach($areas as $area){
                if($area == 'publications')
                    $results[] = $this->searchPublications($text, $phrase, $ordering);
                elseif($area == 'projects')
                    $results[] = $this->searchProjects($text, $phrase, $ordering);
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
        $jinput = JFactory::getApplication()->input;        
        if($this->limit <= 0)
            return array();

        // Get the database object
        $db = JFactory::getDBO();

        // Section name	
        $section = JText::_( 'JRESEARCH_RESEARCH_AREA' );
        switch ( $ordering ) {
            case 'alpha':
                $order = 'r.name ASC';
                break;
            case 'newest':
                $order = 'r.created DESC';
                break;
            case 'oldest':
                $order = 'r.created ASC';
                break;
            default:
                $order = 'r.name ASC';
        }

        $key = $db->Quote(strtolower($text), true );
        switch($phrase){
            case 'exact':
                $whereClause = "published = 1 AND (LOWER(name) like $key OR MATCH(name, description) AGAINST ($key))";
                break;
            case 'all':
                $allKey = $db->Quote(preg_replace('/(\\s+|^)/', " +", $text), true);
                $whereClause = "published = 1 AND MATCH(name, description) AGAINST (".$allKey." IN BOOLEAN MODE )";
                break;
            case 'any':
                $whereClause = "published = 1 AND MATCH(name, description) AGAINST (".$key." IN BOOLEAN MODE )";
        }
        $section = $db->Quote($section, false);
        $query = "SELECT r.id AS id, '' AS metadesc, '' AS metakey, r.name AS title, CONCAT_WS( '/', r.name, $section ) AS section, '' AS created, '2' AS browsernav, SUBSTRING_INDEX(r.description, ' ', 100) AS text FROM #__jresearch_research_area r WHERE $whereClause ORDER BY $order";
        $db->setQuery( $query, 0, $this->limit );
        $results = $db->loadObjectList();
        $itemId = $jinput->getInt('Itemid');
        if(isset($results)){
            foreach($results as $key => $item){
                $results[$key]->href = "index.php?option=com_jresearch&view=researcharea&task=show&id=".$results[$key]->id.'&Itemid='.$itemId;
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
    private function searchProjects($text, $phrase='', $ordering='') {
        $jinput = JFactory::getApplication()->input;        
        $section = JText::_( 'JRESEARCH_PROJECT' );
        // Get the database object
        $db = JFactory::getDBO();

        if($this->limit <= 0)
                return array();

        switch ( $ordering ) {
            case 'alpha':
                $order = 'p.title ASC';
                break;
            case 'category':
                $order = 'r.name ASC';
                break;
            case 'popular':
                $order = 'p.hits DESC';
                break;
            case 'newest':
                $order = 'p.start_date DESC, p.created DESC';
                break;
            case 'oldest':
                $order = 'p.start_date ASC, p.created ASC';
                break;
            default:
                $order = 'p.title ASC';
        }

        $key = $db->Quote(strtolower($text), true);		
        switch($phrase){
            case 'exact':
                $whereClause = "p.published = 1 AND MATCH( p.title, p.description, p.keywords ) AGAINST ($key)";
                break;
            case 'all':
                $allKey = $db->Quote(preg_replace('/(\\s+|^)/', " +", $text), true);
                $whereClause = "p.published = 1 AND MATCH( p.title, p.description, p.keywords ) AGAINST ($allKey IN BOOLEAN MODE)";				
                break;
            case 'any':
                $whereClause = "p.published = 1 AND MATCH( p.title, p.description, p.keywords ) AGAINST ($key IN BOOLEAN MODE)";
                break;
        }

        $section = $db->Quote($section, false);
        $query = "SELECT DISTINCT p.id AS id, '' AS metadesc, p.keywords AS metakey, p.title AS title, CONCAT_WS( '/', r.name, $section ) AS section, 
        p.created AS created, '2' AS browsernav,  SUBSTRING_INDEX(p.description, '<hr id=\"system-readmore\" />', 1) AS text FROM 
        #__jresearch_project p LEFT JOIN #__jresearch_project_research_area pr ON p.id = pr.id_project 
        LEFT JOIN #__jresearch_research_area r ON r.id = pr.id_research_area WHERE $whereClause ORDER BY $order";

        $db->setQuery( $query, 0, $this->limit );
        $results = $db->loadObjectList();
        $itemId = $jinput->getInt('Itemid', 0);
        if(isset($results)){
            foreach($results as $key => $item){
                $results[$key]->href = "index.php?option=com_jresearch&view=project&task=show&id=".$results[$key]->id.'&Itemid='.$itemId;
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
    private function searchPublications($text, $phrase='', $ordering='') {
        $jinput = JFactory::getApplication()->input;        
        if($this->limit <= 0)
            return array();

        $section = JText::_( 'JRESEARCH_PUBLICATION' );
        // Get the database object
        $db = JFactory::getDBO();

        switch ( $ordering ) {
            case 'alpha':
                $order = 'p.title ASC';
                break;
            case 'category':
                $order = 'p.title ASC';
                break;
            case 'newest':
                $order = 'p.year DESC, p.created DESC';
                break;
            case 'oldest':
                $order = 'p.year ASC, p.created ASC';
                break;
            case 'popular':
                $order = 'p.hits DESC';
                break;
            default:
                $order = 'p.title DESC';
        }

        //Array of publications ids where the author has participated.
        $whereArray = array();
        $authorsCondition = 'apa.member_name LIKE '.$db->quote('%'.strtolower($text).'%', true);		
        $publishedCondition = "p.published = 1 AND p.internal = 1";
        switch($phrase){
            case 'exact':
                $key = $db->quote(strtolower($text), true);                
                $whereArray[] = "pk.keyword LIKE $key";
                $whereArray[] = "MATCH(title, abstract) AGAINST ($key)";
                $whereArray[] = $authorsCondition;
                break;
            case 'all':				
                $key = $db->quote(preg_replace('/(\\s+|^)/', " +", strtolower($text)), true);
                $whereArray[] = "MATCH(pk.keyword) AGAINST ($key IN BOOLEAN MODE)";
                $whereArray[] = "MATCH(title, abstract) AGAINST (".$key." IN BOOLEAN MODE)";
                $whereArray[] = $authorsCondition;
                break;				
            case 'any':
                $key = $db->quote(strtolower($text), true);
                $whereArray[] = "MATCH(pk.keyword) AGAINST ($key IN BOOLEAN MODE)";
                $whereArray[] = "MATCH(title, abstract) AGAINST (".$key." IN BOOLEAN MODE)";
                $whereArray[] = $authorsCondition;
                break;	
        }

        $whereDisjunctiveClause = implode(' OR ', $whereArray);        
        $whereClause = $publishedCondition.' AND ('.$whereDisjunctiveClause.')';
        $section = $db->Quote($section, false);
        $query = "SELECT DISTINCT p.id as id, '' AS metadesc, p.keywords AS metakey, p.title AS title, $section AS section, 
        p.created AS created, '2' AS browsernav, CONCAT_WS('\n', p.abstract) AS text 
        FROM #__jresearch_publication p LEFT JOIN #__jresearch_all_publication_authors AS apa ON p.id = apa.pid 
        LEFT JOIN #__jresearch_publication_keyword pk ON p.id = pk.id_publication 
        WHERE $whereClause ORDER BY $order";


        $db->setQuery( $query, 0, $this->limit );
        $results = $db->loadObjectList();
        $itemId = $jinput->getInt('Itemid');
        if(isset($results)){
            foreach($results as $key => $item){
                $results[$key]->href = JRoute::_("index.php?option=com_jresearch&view=publication&task=show&id=".$results[$key]->id.'&Itemid='.$itemId);
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
        $jinput = JFactory::getApplication()->input;        
        $section = JText::_( 'JRESEARCH_MEMBER' );
        $db = JFactory::getDBO();
        switch ( $ordering ) {
            case 'alpha':				
                $order = 'm.lastname ASC, m.name ASC';
                break;
            case 'category':
                $order = 'r.name ASC';
                break;
            case 'newest':
                $order = 'r.created DESC';
                break;				
            case 'oldest':
                $order = 'r.created ASC';
                break;				
            default:
                $order = 'm.lastname ASC';
        }

        $key = $db->Quote(strtolower($text), true);
        $publishedCondition = "m.published = 1";
        $disjunctiveClauses = array();
        $disjunctiveClauses[] = "LOWER( m.firstname ) LIKE $key";
        $disjunctiveClauses[] = "LOWER( m.lastname ) LIKE $key";
        $disjunctiveClauses[] = "CONCAT_WS(' ', LOWER(m.lastname), LOWER(m.firstname)) LIKE $key";
        $disjunctiveClauses[] = "CONCAT_WS(' ', LOWER(m.firstname), LOWER(m.lastname)) LIKE $key";
        switch($phrase){
            case 'exact':                
                $disjunctiveClauses[] = "MATCH(m.description) AGAINST($key)";
                break;
            case 'all':
                $allKey = $db->Quote(preg_replace('/(\\s+|^)/', " +", strtolower($text)), true);
                $disjunctiveClauses[] = "MATCH(m.description) AGAINST($allKey IN BOOLEAN MODE)";
                break;
            case 'any':
                $disjunctiveClauses[] = "MATCH(m.description) AGAINST($key IN BOOLEAN MODE)";
                break;
        }
        
        $whereClause = $publishedCondition.' AND '.implode(' OR ', $disjunctiveClauses);
        $section = $db->Quote($section, false);
        $query = "SELECT DISTINCT m.id as id, CONCAT_WS(' ', m.firstname, m.lastname) AS title, '' AS metadesc, '' AS metakey, CONCAT_WS( '/', r.name, $section ) AS section, 
        m.created AS created, '2' AS browsernav, m.description AS text FROM #__jresearch_member m 
        LEFT JOIN #__jresearch_member_research_area mr ON m.id = mr.id_member 
        LEFT JOIN #__jresearch_research_area r ON r.id = mr.id_research_area WHERE $whereClause ORDER BY $order";
        $db->setQuery( $query, 0, $this->limit );
        $results = $db->loadObjectList();
        $itemId = $jinput->getInt('Itemid');
        if(isset($results)){
            foreach($results as $key => $item){
                $results[$key]->href = "index.php?option=com_jresearch&view=member&task=show&id=".$results[$key]->id.'&Itemid='.$itemId;
            }
        }
        // We just reduce the limit
        $n = count($results);
        $this->limit -= $n;
        return $results;
    }
}
?>