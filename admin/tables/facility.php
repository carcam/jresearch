<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

class JResearchFacility extends JTable
{
    /**
     * ID
     *
     * @var int
     */
    public $id;
    
    /**
     * ID for research area
     *
     * @var int
     */
    public $id_research_area;
    
    /**
	 * String for alias
	 *
	 * @var string
	 */
	public $alias;
  	
	/**
	 * 
	 * Facility name
	 * @var string
	 */
	public $name;
  	
  	/**
	 * 
	 * Team sponsoring the research area
	 * @var int
	 */
	public $id_team;
  	
  	
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