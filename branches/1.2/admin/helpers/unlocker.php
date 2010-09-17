<?php
/**
 * @package JResearch
 * @subpackage Helpers
 * @author Luis Galárraga
 */


/**
 * This class is aimed to scan entity items that have been locked by users
 * long time ago preventing others to edit the information.
 *
 * @author Luis Galárraga
 */
class JResearchUnlockerHelper {

    /**
     * Scans items in database and unlocks them if they have been locked more than one day
     * ago and their authors are not logged.
     *
     * @param string $type The type of items to scan: publications, projects, theses,..
     */
    public static function unlockItems($type){
       $db = JFactory::getDBO();
       $user = JFactory::getUser();
       $yesterday = time() - (24 * 60 * 60);
       $onedayAgo = new JDate($yesterday);

       $query = 'UPDATE '.$db->nameQuote('#__jresearch_'.$type).' SET '.$db->nameQuote('checked_out_time').' = '.$db->Quote('0000-00-00 00:00:00')
               .', '.$db->nameQuote('checked_out').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('checked_out').' != '.$db->Quote($user->id).' AND '
               .$db->nameQuote('checked_out_time').' <= '.$db->Quote($onedayAgo->toMySQL());
       $db->setQuery($query);

       $db->query();

    }
}
?>
