<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Institutes
* @copyright	Copyright (C) 2009 Florian Prinz.
* @license		GNU/GPL
* Table for handling institutes
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

class JResearchInstitute extends JTable
{
	/**
	 * @var int
	 */
    public $id;
    
    /**
	 * @var string
	 */
	public $alias;
    
	/**
	 * @var string
	 */
  	public $name;
  	
  	/**
	 * @var string
	 */
  	public $comment;
  	
  	/**
	 * @var string
	 */
  	public $logo_url;
  	
  	/**
	 * @var string
	 */
  	public $street;
  	/**
	 * @var string
	 */
  	public $place;
  	/**
	 * @var string
	 */
  	public $zip;
  	
  	/**
	 * @var string
	 */
  	public $phone;
  	/**
	 * @var string
	 */
  	public $fax;
  	/**
	 * @var string
	 */
  	public $email;
  	
  	/**
	 * @var bool
	 */
  	public $recognized;
  	
  	/**
	 * @var bool
	 */
  	public $fore_member;

  	/**
	 * @var string
	 */
  	public $url;
  	public $checked_out;
  	public $checked_out_time;
  	/**
	 * @var bool
	 */
  	public $published;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function JResearchInstitute(&$db)
    {
        parent::__construct('#__jresearch_institutes', 'id', $db);
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