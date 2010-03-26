<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* Table for handling cooperations
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

class JResearchCooperation extends JTable
{
	/**
	 * Database integer id
	 *
	 * @var int
	 */
    public $id;
    /**
     * Cooperation name
     *
     * @var string
     */
  	public $name;
  	/**
  	 * Cooperation Image URL
  	 *
  	 * @var string
  	 */
  	public $image_url;
  	/**
  	 * Cooperation description
  	 *
  	 * @var string
  	 */
  	public $description;
  	/**
  	 * Cooperation URL
  	 *
  	 * @var string
  	 */
  	public $url;
  	
  	//Interchanges
  	public $person_name;
  	public $receptor;
  	public $emisor;
  	public $start_date;
  	public $end_date;
  	public $type_ic;
  	
  	public $checked_out;
  	public $checked_out_time;
  	public $published;
  	public $ordering;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function JResearchCooperation(&$db)
    {
        parent::__construct('#__jresearch_cooperations', 'id', $db);
    }
    
    function check()
    {
    	$url_pattern = '!^((mailto\:|(news|(ht|f)tp(s?))\://){1}\S+)$!';
    	//$url_pattern = "!^((ht|f)tp(s?)\:\/\/|~/|/)?([\w]+:\w+@)?([a-zA-Z]{1}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?((/?\w+/)+|/?)(\w+\.[\w]{3,4})?((\?\w+=\w+)?(&\w+=\w+)*)?$!";
    	
        if(!empty($this->url))
        {
        	if(!preg_match($url_pattern, $this->url))
        	{
        		$this->setError(JText::_('Please provide a valid URL ('.$this->url.')'));
        		return false;
        	}
        }
        else 
        {
        	$this->setError(JText::_('Please provide a valid URL'));
        	return false;
        }
        	
        return true;
    }
}
?>