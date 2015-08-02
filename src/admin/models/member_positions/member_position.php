<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the project model.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.application.component.modelform');

jresearchimport('tables.member_position', 'jresearch.admin');

class JResearchAdminModelMember_position extends JModelAdmin {
    
    public function getTable() {
        return JTable::getInstance('Member_position', 'JResearch');
    }
    
    /**
     * Method to get the data.
     *
     * @access      public
     * @return      array of string
     * @since       1.0
     */
    public function &getData()
    {
        if (empty($this->data))
        {
            $app = JFactory::getApplication();
            $data = JRequest::getVar('jform');
            if (empty($data))
            {
                // For new items
                $selected = JRequest::getVar('cid', 0, '', 'array');
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__jresearch_member_position');
                $query->where('id = ' . (int)$selected[0]);
                $db->setQuery((string)$query);
                $data = $db->loadAssoc();
            }

            if (empty($data))
            {
                // Check the session for previously entered form data.
                $data = $app->getUserState('com_jresearch.edit.member_position.data', array());
            }

            // Store the state as an array of values
            $app->setUserState('com_jresearch.edit.member_position.data', $data);
            $this->data = $data;
        }

        return $this->data;
    }

    /**
     * Method to get the HelloWorld form.
     *
     * @access      public
     * @return      mixed   JForm object on success, false on failure.
     * @since       1.0
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_jresearch.member_position', 'member_position', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }


    /**
     * Method to save a record
     *
     * @access      public
     * @return      boolean True on success
     */
    function save() {
        $app = JFactory::getApplication();
        $data =& $this->getData();
        $row =& $this->getTable('Member_position', 'JResearch');

        if (!$row->save($data))
        {
            $this->setError($row->getError());
            return false;
        }

        $app->setUserState('com_jresearch.edit.member_position.data', $data);

        return true;
    }

    /**
     * Publishes the set of selected items
     */
    function publish(){
        $selected = JRequest::getVar('cid', 0, '', 'array');
        $position = JTable::getInstance('Member_position', 'JResearch');
        $result = $position->publish($selected, 1); 
        if(!$result) { 
            $this->setError($position->getError());
        }            
        return $result;
    }

    /**
     * Unpublishes the set of selected items
     */
    function unpublish(){
       $selected = JRequest::getVar('cid', 0, '', 'array');
       $position = JTable::getInstance('Member_position', 'JResearch');
       $result = $position->publish($selected, 0);
       if(!$result) { 
           $this->setError($position->getError());
       }
       return $result;
    }

    /**
     * 
     * Returns the number of removed items based on the 
     * selected items
     */
    function delete(){
       $n = 0;
       $selected =JRequest::getVar('cid', 0, '', 'array');
       $position = JTable::getInstance('Member_position', 'JResearch');
       $user = JFactory::getUser();
       foreach($selected as $id){
            $position->load($id);
            if(!$position->isCheckedOut($user->get('id'))){	
                if($position->delete($id)){
                    $n++;
                }
            }
       }

       return $n;           
    }


    function checkin($pk=null){
        $data = &$this->getData();

        if(!empty($data)){
            // Database processing
            $row = &$this->getTable('Member_position', 'JResearch');
            $row->bind($data);
            if (!$row->checkin())
            {
                $this->setError($row->getError());
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the model data store in the user state as a table
     * object
     */
    public function getItem(){
        $row = $this->getTable('Member_position', 'JResearch');
        $data =& $this->getData();
        $row->bind($data);
        return $row;
    }
}
?>