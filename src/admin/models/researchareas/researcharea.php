<?php
/**
* @version	$Id$
* @package	JResearch
* @subpackage	ResearchAreas
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license	GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


/**
* Model class for holding a single research area record.
*
*/
class JResearchAdminModelResearchArea extends JModelAdmin{

    public function getTable($name = 'Researcharea', $prefix = 'JResearch', $options = array()) {
        return JTable::getInstance($name, $prefix);
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
        if (empty($this->data)) {
            $app = JFactory::getApplication();
            $jinput = JFactory::getApplication()->input;
            $data = $jinput->get('jform', array(), 'ARRAY');
            if (empty($data)) {
                // For new items
                $selected = $jinput->get('cid', array(0), 'ARRAY');
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__jresearch_research_area');
                $query->where('id = ' . (int)$selected[0]);
                $db->setQuery((string)$query);
                $data = $db->loadAssoc();
            }

            if (empty($data)) {
                // Check the session for previously entered form data.
                $data = $app->getUserState('com_jresearch.edit.researcharea.data', array());
            }

            // Store the state as an array of values
            $app->setUserState('com_jresearch.edit.researcharea.data', $data);
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
    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_jresearch.researcharea', 'researcharea', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }


    /**
     * Method to save a record
     *
     * @access      public
     * @return      boolean True on success
     */
    function save($data) {
        $app = JFactory::getApplication();

        $data =& $this->getData();
        $jinput = JFactory::getApplication()->input;
        $form = $jinput->get('jform', array(), 'RAW');
        $data['description'] = $form['description'];
        //Alias generation
        if(empty($data['alias'])){
            $data['alias'] = JFilterOutput::stringURLSafe($data['name']);
        }

        $row =& $this->getTable('Researcharea', 'JResearch');

        if (!$row->save($data)) {
            $this->setError($row->getError());
            return false;
        }

        $data['id'] = $row->id;
        $app->setUserState('com_jresearch.edit.researcharea.data', $data);

        return true;
    }

    /**
     * Publishes the set of selected items
     */
    function publish(&$pks, $value = 1) {
        $jinput = JFactory::getApplication()->input;
        $selected = $jinput->get('cid', array(), 'ARRAY');
        $area = JTable::getInstance('Researcharea', 'JResearch');           
        $user = JFactory::getUser();
        $allOk = true;
        foreach($selected as $id){
            $action = JResearchAccessHelper::getActions('researcharea', $id);           	
            if($action->get('core.researchareas.edit')){
                $allOk = $allOk && $area->publish(array($id), 1, $user->get('id'));
                if(!$allOk) { 
                    $this->setError($area->getError());	    	       
                }
            }else{
                $allOk = false;
                $this->setError(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $id));           	   	   
            }
        }

        return $allOk;
    }

    /**
     * Unpublishes the set of selected items
     */
    function unpublish(){
        $jinput = JFactory::getApplication()->input;
        $selected = $jinput->get('cid', array(), 'ARRAY');
        $area = JTable::getInstance('Researcharea', 'JResearch');           
        $allOk = true;
        $user = JFactory::getUser();
        foreach($selected as $id){
           $action = JResearchAccessHelper::getActions('researcharea', $id);           	
           if($action->get('core.researchareas.edit')){
                $allOk = $allOk && $area->publish(array($id), 0, $user->get('id'));
                if(!$allOk) { 
                    $this->setError($area->getError());
                }
           }else{
                $allOk = false;
                $this->setError(new JException(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $id)));           	   	   
           }
        }

        return $allOk;
    }

    /**
     * 
     * Returns the number of removed items based on the 
     * selected items
     */
    function delete(&$pks){
        $n = 0;
        $jinput = JFactory::getApplication()->input;
        $selected = $jinput->get('cid', array(), 'ARRAY');
        $area = JTable::getInstance('Researcharea', 'JResearch');
        $user = JFactory::getUser();
        foreach($selected as $id){
            $area->load($id);
            $action = JResearchAccessHelper::getActions('researcharea', $id);
            if($action->get('core.researchareas.delete')){                
                if($id > 1){                	
                    if(!$area->isCheckedOut($user->get('id'))){	
                        if($area->delete($id)){
                            $n++;
                        }
                    }
                }else{
                    $this->setError(JText::_('JRESEARCH_UNCATEGORIZED_AREA_NEITHER_UNPUBLISHED_NOR_REMOVED'));	                	
                }
            }else{
                $this->setError(JText::sprintf('JRESEARCH_DELETE_ITEM_NOT_ALLOWED', $id));           			
            }
        }

        return $n;           
    }

        
    function checkin($pk = NULL){
        $data = &$this->getData();

        if(!empty($data)){
            // Database processing
            $row = &$this->getTable('Researcharea', 'JResearch');
            $row->bind($data);
            if (!$row->checkin()) {
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
    public function getItem($pk = null){
        $row = $this->getTable('Researcharea', 'JResearch');
        $data =& $this->getData();
        $row->bind($data);
        return $row;
    }
}
?>
