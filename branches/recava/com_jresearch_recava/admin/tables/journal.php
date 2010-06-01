<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Journals
* @copyright	Copyright (C) 2010 Luis Gal�rraga.
* @license		GNU/GPL
* Table for handling journals
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

class JResearchJournal extends JTable
{
	/**
	 * Database integer id
	 *
	 * @var int
	 */
    public $id;

    /**
     * Journal title
     *
     * @var string
     */
    public $title;

  	/**
  	 * Cooperation Image URL
  	 *
  	 * @var string
  	 */
    public $impact_factor;

  	
    public $checked_out;
    public $checked_out_time;
    public $published;

    private $history;
  	
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__jresearch_journals', 'id', $db);
        $this->history = array();
    }
    
    function check()
    {
    	$number_pattern = '/^([0-9]+[.][0-9]*|[0-9]*[.][0-9]+|[0-9]+)$/';
    	
        if(!empty($this->impact_factor))
        {
        	if(!preg_match($number_pattern, $this->impact_factor))
        	{
        		$this->setError(JText::_('Please provide a valid number for impact factor ('.$this->title.')'));
        		return false;
        	}
        }
        	
        return true;
    }
    
    function addHistory($entry){
        $this->history[$entry['year']] = $entry['impact_factor'];
    }

    function getHistory(){
        return $this->history;
    }
    
    function store($updateNulls){
        parent::store($updateNulls);
        $db = JFactory::getDBO();

        // First remove any previous history
        $query = 'DELETE FROM '.$db->nameQuote('#__jresearch_journal_history').' WHERE '
                .$db->nameQuote('id_journal').' = '.$db->Quote($this->id);

        $db->setQuery($query);
        if(!$db->query()){
            $this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
            return false;
        }
        
        // Save the history
        $tableName = $db->nameQuote('#__jresearch_journal_history');
        $idJournalField = $db->nameQuote('id_journal');
        $yearField = $db->nameQuote('year');
        $factorField = $db->nameQuote('impact_factor');
        foreach($this->history as $yearValue => $factorValue){
            $yearValue = $db->Quote($yearValue);
            $factorValue = $db->Quote($factorValue);
            $insertInternalQuery = "INSERT INTO $tableName($idJournalField, $yearField, $factorField) VALUES ($this->id, $yearValue ,$factorValue)";
            $db->setQuery($insertInternalQuery);
            if(!$db->query()){
                $this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
                return false;
            }

        }

        return true;
    }

    function load($oid){
        $result = parent::load($oid);
        $this->_loadHistory();
        return $result;
    }

    private function _loadHistory(){
        $db = JFactory::getDBO();

        $query = 'SELECT year, impact_factor FROM '.$db->nameQuote('#__jresearch_journal_history')
                .' WHERE '.$db->nameQuote('id_journal').' = '.$db->Quote($this->id)
                .' ORDER BY year DESC';

        $db->setQuery($query);
        $result = $db->loadAssocList();
        foreach($result as $entry){
            $this->history[$entry['year']] = $entry['impact_factor'];
        }

    }


}
?>
