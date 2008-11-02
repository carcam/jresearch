<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

class JResearchFacility extends JTable
{
    public $id;
    public $id_research_area;
  	public $name;
  	public $image_url;
  	public $description;
  	public $checked_out;
  	public $checked_out_time;
  	public $published;
  	public $ordering;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function JResearchFacility(&$db)
    {
        parent::__construct('#__jresearch_facilities', 'id', $db);
    }
    
    function check()
    {
    	$url_pattern = "!^((ht|f)tp(s?)\:\/\/|~/|/)?([\w]+:\w+@)?([a-zA-Z]{1}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?((/?\w+/)+|/?)(\w+\.[\w]{3,4})?((\?\w+=\w+)?(&\w+=\w+)*)?$!";
        	
        return true;
    }
}
?>