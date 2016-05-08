<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport('joomla.application.component.modelform');


/**
* Model class for holding a single publication record.
*
*/
class JResearchAdminModelPublication extends JModelForm{

    /**
     * @var array data
     */
    protected $data = null;

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
            $jinput = JFactory::getApplication()->input;
            $data = $jinput->get('jform', array(), 'ARRAY');
            if (empty($data)) {
                $selected = $jinput->get('cid', array(0), 'ARRAY');
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select all fields from the hello table.
                $query->select('*');
                $query->from('`#__jresearch_publication`');
                $query->where('id = ' . (int)$selected[0]);
                $db->setQuery((string)$query);
                $data = $db->loadAssoc();
            }
            if (empty($data))
            {
                // Check the session for previously entered form data.
                $data = $app->getUserState('com_jresearch.edit.publication.data', array());
            }

            //Once the data is retrieved, time to fix it
            if(isset($data['id_research_area']) && is_string($data['id_research_area'])){
                    $data['id_research_area'] = explode(',', $data['id_research_area']);
            }

            $app->setUserState('com_jresearch.edit.publication.data', $data);
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
        $jinput = JFactory::getApplication()->input;
        $pubtype = $jinput->get('pubtype', 'article');
        $form = $this->loadForm('com_jresearch.'.$pubtype, $pubtype, array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }
    
    /**
     * It implements common routines for the processing of non-trivial fields
     * such as research areas, authors and file uploads
     */
    private function _processFields(&$data, $row) {
        $app = JFactory::getApplication();
        jresearchimport('helpers.publications', 'jresearch.admin');
        $jinput = JFactory::getApplication()->input;
        $type = $jinput->get('change_type', '-1');
        if ($type != '-1') {
            $data['pubtype'] = $type;
        }
        
        $data['files'] = JResearchUtilities::processAttachments($data, 'publications');

        if(isset($data['resethits']) && $data['resethits'] == 1){
            $data['hits'] = 0;
        }else{
            $omittedFields[] = 'hits';			    	
        }

        //Citekey generation
        if(empty($data['citekey'])){
            $data['citekey'] = JResearchPublicationsHelper::generateCitekey($data);
        }

        //Alias generation
        if(empty($data['alias'])){
            $data['alias'] = JResearchUtilities::alias($data['title']);
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
        jresearchimport('helpers.publications', 'jresearch.admin');
        $omittedFields = array();

        $data =& $this->getData();                
        $row =& $this->getTable('Publication', 'JResearch');

        $this->_processFields($data, $row);
        
        if (!$row->save($data, '', $omittedFields))
        {
            //Since the save routine modifies the array data
            JRequest::setVar('jform', $data);                	
            $this->setError($row->getError());
            return false;
        }
        
        /**
         * Changes the type of a publication based on the request
         * arguments
         * 
         */
        function changeType(){
			$app = JFactory::getApplication();
			jresearchimport('helpers.publications', 'jresearch.admin');
			$params = JComponentHelper::getParams('com_jresearch');
			$omittedFields = array();
                
            $data =& $this->getData();                
            $row =& $this->getTable('Publication', 'JResearch');

        $data['id'] = $row->id;
        $app->setUserState('com_jresearch.edit.publication.data', $data);

        return true;
    }

    /**
     * Changes the type of a publication based on the request
     * arguments
     * 
     */
    function saveAsCopy(){
        $app = JFactory::getApplication();
        jresearchimport('helpers.publications', 'jresearch.admin');
        $omittedFields = array();

        $data =& $this->getData();                
        $row =& $this->getTable('Publication', 'JResearch');

        $this->_processFields($data, $row);
        unset($data['id']);
        $data['title'] = $data['title'].' ( '.JText::_('JRESEARCH_COPY').')';
        $data['citekey'] = $data['citekey'].'-copy-'.rand();
        if (!$row->save($data, '', $omittedFields))
        {
            //Since the save routine modifies the array data
            JRequest::setVar('jform', $data);                	
            $this->setError($row->getError());
            return false;
        }

        $data['id'] = $row->id;
        $app->setUserState('com_jresearch.edit.publication.data', $data);

        return true;        	
    }

    /**
     * Publishes the set of selected items
     */
    function publish(){
        $jinput = JFactory::getApplication()->input;
        $selected = $jinput->get('cid', array(), 'ARRAY');
        $publication = JTable::getInstance('Publication', 'JResearch');           
        $allOk = true;
        $user = JFactory::getUser();
        foreach($selected as $id){
            $action = JResearchAccessHelper::getActions('publication', $id);           	
            if($action->get('core.publications.edit.state')){
                $allOk = $allOk && $publication->publish(array($id), 1, $user->get('id'));
                if(!$allOk) $this->setError($publication->getError());
            }else{
                $allOk = false;
                $this->setError(new JException(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $id)));           	   	   
            }

        return $allOk;

    }

    /**
     * Unpublishes the set of selected items
     */
    function unpublish(){
        $jinput = JFactory::getApplication()->input;
        $selected = $jinput->get('cid', array(), 'ARRAY');
        $publication = JTable::getInstance('Publication', 'JResearch');
        $user = JFactory::getUser();           
        $allOk = true;
        foreach($selected as $id){
            $action = JResearchAccessHelper::getActions('publication', $id);           	
            if($action->get('core.publications.edit.state')){
                $allOk = $allOk && $publication->publish(array($id), 0, $user->get('id'));
                if(!$allOk) $this->setError($publication->getError());	    	       
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
    function delete(){
        $n = 0;
        $jinput = JFactory::getApplication()->input;
        $selected = $jinput->get('cid', array(), 'ARRAY');
        $publication = JTable::getInstance('Publication', 'JResearch');
        $user = JFactory::getUser();           
        foreach($selected as $id){
            $actions = JResearchAccessHelper::getActions('publication', $id);
            if($actions->get('core.publications.delete')){           	
                $publication->load($id);
                if(!$publication->isCheckedOut($user->get('id'))){	
                    if($publication->delete($id)){
                        $n++;
                    }
                }
            }else{
                $this->setError(new JException(JText::sprintf('JRESEARCH_DELETE_ITEM_NOT_ALLOWED', $id)));
            }
        }

        return $n;           
    }

            /**
     * Returns the model data store in the user state as a table
     * object
     */
    public function getItem(){
        $row = $this->getTable('Publication', 'JResearch');
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
            $pub = JTable::getInstance('Publication', 'JResearch');
            $pub->load($id);
            $result[] = $pub;
        }

        return $result;
    }

    /**
     * Sets the internal status of the selected publications to the 
     * value sent as argument
     * @param bool $value
     */
    public function setInternalValue($value){
        $publication = $this->getTable('Publication', 'JResearch');
        $jinput = JFactory::getApplication()->input;
        $cid = $jinput->get('cid', array(), 'ARRAY');
        $user = JFactory::getUser();
        $n = 0;
        foreach($cid as $id){
            $actions = JResearchAccessHelper::getActions('publication', $id);
            if($actions->get('core.publications.edit.state')){
                if($publication->toggleInternal(array($id), $value, $user->get('id'))){
                    $n++;
                }
            }else{
                $this->setError(new JException(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $id)));
            }
        }

        return $n;
    }

    /**
     * Sets the internal status of the selected publications to the 
     * value sent as argument
     * @param bool $value
     */
    public function toggleInternal(){
        $publication = $this->getTable('Publication', 'JResearch');
        $jinput = JFactory::getApplication()->input;
        $cid = $jinput->get('cid', array(), 'ARRAY');
        if(!empty($cid)){
            $actions = JResearchAccessHelper::getActions('publication', $cid[0]);
            if($actions->get('core.publications.edit.state')){
                $publication->load($cid[0]);
                if(!empty($publication->id)){
                    $user = JFactory::getUser();
                    if(!$publication->isCheckedOut($user->get('id'))){
                        $publication->internal = !$publication->internal;	
                        return $publication->store();
                    }
                }
            }
        }
        return false;
    }

    function checkin($pk=null){
        $data = &$this->getData();

        if(!empty($data)){
            // Database processing
            $row = &$this->getTable('Publication', 'JResearch');
            $row->bind($data);
            if (!$row->checkin())
            {
                $this->setError($row->getError());
                return false;
            }
        }

        return true;
    }    
}
?>
