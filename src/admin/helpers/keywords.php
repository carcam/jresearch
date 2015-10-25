<?php
/**
 * @package JResearch
 * @subpackage Helpers
 * @author Luis Galárraga
 * @license	GNU/GPL
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Helper function for J!Research plugins related functionalities.
 *
 */

class JResearchKeywordsHelper {
    
    /**
     * Returns all the keywords that start with the given prefix.
     * @param type $prefix
     */
    public static function allKeywords($prefix = null) {
        $db = JFactory::getDbo();
        if ($prefix !== null) {
            $db->setQuery('SELECT keyword FROM #__jresearch_keyword WHERE keyword LIKE '
                    .$db->q($prefix.'%'));
        } else {
            $db->setQuery('SELECT keyword FROM #__jresearch_keyword');
        }
        
        return $db->loadColumn();
    }
    
    /**
     * Return a JSON representation for the set of given keywords.
     * @param type $keywords
     * @return string
     */
    public static function format2JSON($keywords) {
        $items = array();
        foreach ($keywords as $keyword) {
            $items[] = '{ "label" : "'. $keyword. '", "value" : "'.$keyword. '"} '; 
        }
        $output = '['. implode(',', $items). ']';
        return $output;
    }
    
    /**
     * Returns an array of keywords associated to the record types provided as
     * arguments in the input array. 
     * @param type $types An array with entity types such as 'publications' or
     * 'projects'. If the array is empty, the method returns keywords from all 
     * types of entities.
     * @return associate array of the form keyword => relevance. The relevance 
     * is the number of items to which the keyword is associated in the database.
     * 
     */
    public static function getKeywordsByRelevance($types = array()) {
        $result = array();
        $db = JFactory::getDbo();
        if (empty($types) || (array_search('publications', $types) !== FALSE 
                && array_search('projects', $types) !== FALSE)) {
            $query = "SELECT kw.keyword as keyword, count(R.id) as relevance FROM "
                    . $db->quoteName('#__jresearch_keyword')." kw, "
                    . "(SELECT id_publication as id, keyword FROM "
                    . $db->quoteName('f3x96_jresearch_publication_keyword')
                    . " UNION SELECT id_project as id, keyword FROM "
                    . $db->quoteName('f3x96_jresearch_project_keyword')
                    . ") as R WHERE R.keyword = kw.keyword GROUP BY kw.keyword";
        } else if (array_search('publications', $types) !== FALSE) {
            $query = "SELECT kw.keyword as keyword, count(pubkw.id_publication) "
                    . "as relevance FROM "
                    . $db->quoteName('f3x96_jresearch_keyword'). " " 
                    . "kw, ".$db->quoteName('f3x96_jresearch_publication_keyword')." "
                    . "pubkw WHERE pubkw.keyword = kw.keyword GROUP BY kw.keyword";            
        } else if (array_search('projects', $types) !== FALSE){
            $query = "SELECT kw.keyword as keyword, count(pjkw.id_project) "
                    . "as relevance FROM "
                    . $db->quoteName('f3x96_jresearch_keyword'). " " 
                    . "kw, ".$db->quoteName('f3x96_jresearch_project_keyword')." "
                    . "pjkw WHERE pjkw.keyword = kw.keyword GROUP BY kw.keyword";                        
        } else {
            return $result;
        }
        
        $db->setQuery($query);
        $partialResult = $db->loadAssocList();
        foreach ($partialResult as $assoc) {
            $result[$assoc['keyword']] = intval($assoc['relevance']);
        }
        
        return $result;
    }
} 
