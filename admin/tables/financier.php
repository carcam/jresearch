<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Financiers
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* Table for handling financiers
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

class JResearchFinancier extends JTable
{
	public $id;
  	public $name;
  	public $url;
  	public $checked_out;
  	public $checked_out_time;
  	public $published;
	
	function __construct(&$db)
	{
		parent::__construct ('#__jresearch_financier', 'id', $db);
	}
	
	function check()
    {
    	$url_pattern = "!^((ht|f)tp(s?)\:\/\/|~/|/)?([\w]+:\w+@)?([a-zA-Z]{1}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?((/?\w+/)+|/?)(\w+\.[\w]{3,4})?((\?\w+=\w+)?(&\w+=\w+)*)?$!";
        
       	if(!preg_match($url_pattern, $this->url) && !empty($this->url))
       	{
       		$this->setError(JText::_('Please provide a valid URL ('.$this->url.')'));
       		return false;
       	}
        	
        return true;
    }
}

?>