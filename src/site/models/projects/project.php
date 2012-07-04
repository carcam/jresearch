<?php
/**
* @package		JResearch
* @subpackage	Frontend.Models
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

jresearchimport('models.modelform', 'jresearch.site');

/**
* Model class for holding a single project record.
*
*/
class JResearchModelProject extends JResearchModelForm{
	
    /**
     * Returns the model data store in the user state as a table
     * object
     */
    public function getItem(){
        if(!isset($this->_row)){
            $row = $this->getTable('Project', 'JResearch');
             if($row->load(JRequest::getInt('id'))){
                 if($row->published)
                     $this->_row = $row;
                else
                    return false;
            }else
                return false;                
         }

        return $this->_row;
    }
}
?>