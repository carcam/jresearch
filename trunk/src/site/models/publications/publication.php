<?php
/**
* @package		JResearch
* @subpackage	
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

jresearchimport('models.modelitem', 'jresearch.site');

/**
* Model class for holding a single publication record.
*
*/
class JResearchModelPublication extends JResearchModelItem{
    
        /**
         * Returns the model data store in the user state as a table
         * object
         */
        public function getItem(){
            if(!isset($this->_row)){
                $row = $this->getTable('Publication', 'JResearch');
                if($row->load(JRequest::getInt('id'))){
                    if($row->published && $row->internal)
                        return $row;
                    else
                        return false;
                }else
                    return false;                
            }

            return $this->_row;
        }
}
?>