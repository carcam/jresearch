<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Journals
* @copyright	Copyright (C) 2010 Luis Galárraga.
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
  	
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__jresearch_journals', 'id', $db);
    }
    
    function check()
    {
    	$number_pattern = '/^[0-9]+$/';
    	
        if(!empty($this->impact_factor))
        {
        	if(!preg_match($number_pattern, $this->impact_factor))
        	{
        		$this->setError(JText::_('Please provide an integer value for impact factor ('.$this->url.')'));
        		return false;
        	}
        }
        	
        return true;
    }
}
?>
