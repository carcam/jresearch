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
	
	function __toString()
	{
		return $this->name;
	}
	
	function check()
    {
    	$url_pattern = "/^(ftp|http|https|ftps):\/\/([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}|localhost|\d{1,3}(\.\d{1,3}){3})(:\d{2,5})?(([0-9]{1,5})?\/.*)?$/i";
        
       	if(!preg_match($url_pattern, $this->url) && !empty($this->url))
       	{
       		$this->setError(JText::_('Please provide a valid URL ('.$this->url.')'));
       		return false;
       	}
        	
        return true;
    }
}

?>