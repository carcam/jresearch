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
    
    public static function format2JSON($keywords) {
        $items = array();
        foreach ($keywords as $keyword) {
            $items[] = '{ "label" : "'. $keyword. '", "value" : "'.$keyword. '"} '; 
        }
        $output = '['. implode(',', $items). ']';
        return $output;
    }
}
