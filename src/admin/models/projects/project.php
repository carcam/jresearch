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

/**
* Model class for holding a single project record.
*
* @subpackage	Projects
*/
class JResearchAdminModelProject extends JModelAdmin{
    /**
    * @var array data
    */
    protected $data = null;

    public function getTable($name = 'Project', $prefix = 'JResearch', $options = array()) {
        return JTable::getInstance('Project', $prefix);
    }
    
    /**
    * Method to get the data.
    *
    * @access      public
    * @return      array of string
    * @since       1.0
    */
    public function &getData(){
        if (empty($this->data)){
            $app = JFactory::getApplication();
            $jinput = JFactory::getApplication()->input;
                    
            $data = $jinput->get('jform');
            if (empty($data)) {
                $selected = $jinput('cid', array(), 'ARRAY');
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('`#__jresearch_project`');
                $query->where('id = ' . (int)$selected[0]);
                $db->setQuery((string)$query);
                $data = $db->loadAssoc();
            }

            if (empty($data)){
                // Check the session for previously entered form data.
                $data = $app->getUserState('com_jresearch.edit.project.data', array());
            }

            //Once the data is retrieved, time to fix it
            if(isset($data['id_research_area']) && is_string($data['id_research_area'])){
                $data['id_research_area'] = explode(',', $data['id_research_area']);
            }

            $app->setUserState('com_jresearch.edit.project.data', $data);
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
        $form = $this->loadForm('com_jresearch.project', 'project', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }
    
    /**
     * Routines for the processing of non-trivial fields
     * @param type $data
     * @param type $row
     */
    function _processFields(&$data, $row) {
        // Make sure Joomla! does not strip off HTML markup
        $jinput = JFactory::getApplication()->input;
        $form = $jinput->get('jform', array(), 'RAW');        
        $data['description'] = $form['description'];
        
        $data['files'] = JResearchUtilities::processAttachments($data, 'projects');
        
        if($data['resethits'] == 1){
            $data['hits'] = 0;
        }else{
            $omittedFields[] = 'hits';			    	
        }
        
        //Alias generation
        if(empty($data['alias'])){
            $data['alias'] = JResearchUtilities::alias($data['title']);
        }
        
        if(isset($data['resethits']) && $data['resethits'] == 1){
            $data['hits'] = 0;
        }else{
            $omittedFields[] = 'hits';			    	
        }

        //Alias generation
        if(empty($data['alias'])){
            $data['alias'] = JFilterOutput::stringURLSafe($data['title']);
        }

        //Checking of research areas
        if(!empty($data['id_research_area'])){
            if(in_array('1', $data['id_research_area'])){
                $data['id_research_area'] = '1';
            }else{
                $data['id_research_area'] = implode(',', $data['id_research_area']);
            }
        }else{
            $data['id_research_area'] = '1';
        }        
    }

    /**
     * Method to save a record
     *
     * @access      public
     * @return      boolean True on success
     */
    function save()
    {
        $app = JFactory::getApplication();
        jresearchimport('helpers.projects', 'jresearch.admin');
        jresearchimport('helpers.jresearchutilities', 'jresearch.admin');
        $params = JComponentHelper::getParams('com_jresearch');
        $omittedFields = array();

        $data =& $this->getData();                
        $row =& $this->getTable('Project', 'JResearch');


        $this->_processFields($data, $row);


        if (!$row->save($data, '', $omittedFields))
        {
            //Since the save routine modifies the array data
            JRequest::setVar('jform', $data);                	
            $this->setError($row->getError());
            return false;
        }

        $data['id'] = $row->id;
        $app->setUserState('com_jresearch.edit.project.data', $data);

        return true;
    }


    /**
     * Publishes the set of selected items
     */
    function publish(&$pks, $value = 1) {
        $jinput = JFactory::getApplication()->input;
        $selected = $jinput->get('cid', array(), 'ARRAY');
        $project = JTable::getInstance('Project', 'JResearch');           
        $allOk = true;
        $user = JFactory::getUser();
        foreach($selected as $id) {
           $action = JResearchAccessHelper::getActions('project', $id);
           if($action->get('core.projects.edit.state')){
                $v = $project->publish(array($id), 1, $user->get('id'));
                $allOk = $allOk && $v;
                if(!$allOk) $this->setError($project->getError());
           }else{
                 $allOk = false;
                 $this->setError(new JException(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $id)));
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
        $project = JTable::getInstance('Project', 'JResearch');
        $user = JFactory::getUser();           
        $allOk = true;
        foreach($selected as $id){
            $action = JResearchAccessHelper::getActions('project', $id);           	
            if($action->get('core.projects.edit.state')){
                $allOk = $allOk && $project->publish(array($id), 0, $user->get('id'));
                if(!$allOk) $this->setError($project->getError());	    	       
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
    function delete(&$pks) {
        $n = 0;
        $jinput = JFactory::getApplication()->input;
        $selected = $jinput->get('cid', array(), 'ARRAY');
        $project = JTable::getInstance('Project', 'JResearch');
        $user = JFactory::getUser();           
        foreach($selected as $id){
            $actions = JResearchAccessHelper::getActions('project', $id);
            if($actions->get('core.projects.delete')){           	
                $project->load($id);
                if(!$project->isCheckedOut($user->get('id'))) {	
                    if($project->delete($id)) {
                        $n++;
                    }
                }
            }else {
                $this->setError(new JException(JText::sprintf('JRESEARCH_DELETE_ITEM_NOT_ALLOWED', $id)));
            }
        }

        return $n;           
    }

            /**
     * Returns the model data store in the user state as a table
     * object
     */
    public function getItem($pk = NULL) {
        $row = $this->getTable('Project', 'JResearch');
        $data =& $this->getData();
        $row->bind($data);
        return $row;
    }

    /**
     * Returns the items whose ids are contained in the
     * url "cid" parameter
     * 
     */        
    public function getItems(){        
        $jinput = JFactory::getApplication()->input;
        $cid = $jinput->get('cid', array(), 'ARRAY');
        $result = array();
        foreach($cid as $id){
            $proj = JTable::getInstance('Project', 'JResearch');
            $proj->load($id);
            $result[] = $proj;
        }

        return $result;
    }

}
?>
