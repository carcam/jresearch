<?php
/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2009 Florian Prinz.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

jresearchimport('tables.table', 'jresearch.admin');

class JResearchMember_position extends JResearchTable 
{
	
    public $position;

    public $ordering;

    /**
     * 
     * Constructor
     * @param JDatabase $db
     */
    public function __construct(&$db){
        parent::__construct('#__jresearch_member_position', 'id', $db);
    }

    /**
     * @return string Returns position name
     */
    public function __toString()
    {
        return $this->position;
    }
    
    public function store($updateNulls = false) {
        $this->ordering = parent::getNextOrder();
        $result = false;
        try {
            $result = parent::store($updateNulls);   
        } catch (RuntimeException $ex) {
            $this->setError(parent::getError().' '.$ex->getMessage());
        }
        return $result;
    }
}
?>