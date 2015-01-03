<?php
/**
* @package		JResearch
* @subpackage	Tables
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Base class for J!Research tables. Implements common routines for J!Research
* table classes
*/

defined('_JEXEC') or die( 'Restricted access' );

jresearchimport('joomla.access.rules');

class JResearchTable extends JTable{
    /**
     * Integer database id
     *
     * @var int
     */
    public $id;

    /**
    * @var boolean
    */
    public $published;


    /**
     * Overloaded bind function
     *
     * @param	array		$hash named array
     *
     * @return	null|string	null is operation was satisfactory, otherwise returns an error
     * @see		JTable:bind
     * @since	1.5
     */
    public function bind($array, $ignore = ''){
        // Bind the rules.
        if (is_array($array) && isset($array['rules'])){
            $rules = new JRules($array['rules']);
            $this->setRules($rules);
        }elseif(is_object($array) && isset($array->rules)){
            $rules = new JRules($array->rules);
            $this->setRules($rules);			
        }

        return parent::bind($array, $ignore);
    }

    /**
     * Get the parent asset id for the record
     *
     * @return	int
     * @since	1.6
     */
    protected function _getAssetParentId(JTable $table = NULL, $id = NULL){
        // Initialise variables.
        $assetId = null;
        $db = $this->getDbo();
        $query	= $db->getQuery(true);
        $query->select('id');
        $query->from('#__assets');
        $query->where('name = '.$db->Quote('com_jresearch'));

        // Get the asset id from the database.
        $db->setQuery($query);
        if ($result = $db->loadResult()) {
                $assetId = (int) $result;			
        }
        // Return the asset id.
        if ($assetId) {
                return $assetId;
        } else {
                return parent::_getAssetParentId($table, $id);
        }
    }

    /**
     * Method to return the title to use for the asset table.
     *
     * @return	string
     * @since	1.6
     */
    protected function _getAssetTitle()
    {
        return $this->__toString();
    }

    /**
     * String representation
     */
    public function __toString(){
        return ''.$id;
    }

    /**
     * Method to compute the default name of the asset.
     * The default name is in the form `table_name.id`
     * where id is the value of the primary key of the table.
     *
     * @return	string
     * @since	1.6
     */
    protected function _getAssetName()
    {
        $k = $this->_tbl_key;
        return 'com_jresearch.table.'.(int) $this->$k;
    }
}