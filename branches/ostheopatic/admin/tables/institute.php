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
  	public $institute_logo;
  	
  	/**
	 * @var string
	 */
  	public $street;  	
  	public $id_country;  	
  	public $woho_member;
  	public $hide;
    public $woho;
    public $street2;
  	public $state_province;  	
  	public $link_2_paper;
  	public $name_english;  	
    public $name2;  	
    public $contact_p;  	    
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
  	
  	public $ordering;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db){
        parent::__construct('#__jresearch_institute', 'id', $db);
    }
    
    
    function check(){
    	$url_pattern = '!^((mailto\:|(news|(ht|f)tp(s?))\://){1}\S+)$!';
    	if(empty($this->name)){
			$this->setError(JText::_('Please provide a name for the institute'));
        	return false;    		
    	}
    		
    	
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
    
	/**
	 * Returns the name of the country of the publication
	 * @return string
	 */
	public function getCountry(){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'country.php');
		$result = JResearchCountryHelper::getCountry($this->id_country);
		if($result != null)
			return $result['name'];
		else
			return null;	
	}
}
?>