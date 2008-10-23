<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

class JResearchCooperation extends JTable
{
    public $id;
  	public $name;
  	public $image_url;
  	public $description;
  	public $url;
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

    function load($id)
    {
    	$result = parent::load($id);

    	return $result;
    }
    
    function check()
    {
    	$url_pattern = "!^((ht|f)tp(s?)\:\/\/|~/|/)?([\w]+:\w+@)?([a-zA-Z]{1}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?((/?\w+/)+|/?)(\w+\.[\w]{3,4})?((\?\w+=\w+)?(&\w+=\w+)*)?$!";
        
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