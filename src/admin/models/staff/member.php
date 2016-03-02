<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.modelform' );

/**
* Model class for holding a single member record.
*
*/
class JResearchAdminModelMember extends JModelAdmin{
    /**
    * @var array data
    */
    protected $data = null;

    public function getTable($name = 'Member', $prefix = 'JResearch', $options = array())  {
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
                $query->from('#__jresearch_member');
                $query->where('id = ' . (int)$selected[0]);
                $db->setQuery((string)$query);
                $data = $db->loadAssoc();
            }

            if (empty($data))
            {
                // Check the session for previously entered form data.
                $data = $app->getUserState('com_jresearch.edit.member.data', array());
            }
            
           //Once the data is retrieved, time to fix it
            if(isset($data['id_research_area']) && is_string($data['id_research_area'])){
            	$data['id_research_area'] = explode(',', $data['id_research_area']);
            }
            

            // Store the state as an array of values
            $app->setUserState('com_jresearch.edit.member.data', $data);
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
        $form = $this->loadForm('com_jresearch.member', 'member', array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }


    /**
     * Method to save a record
     *
     * @access      public
     * @return      boolean True on success
     */
    function save($data)
    {
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_jresearch');
        $form = JRequest::getVar('jform', '', 'REQUEST', 'array', JREQUEST_ALLOWHTML);
        $data = &$this->getData();
        $data['description'] = $form['description'];
        $row = $this->getTable('Member', 'JResearch');

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
        
		//Time to upload the file
        $delete = $data['delete_files_0'];
    	if($delete == 'on'){
            if(!empty($data['old_files_0'])){
                $filetoremove = JRESEARCH_COMPONENT_ADMIN.DS.$params->get('files_root_path', 'files').DS.'staff'.DS.$row->files;
                $data['files'] = '';
                @unlink($filetoremove);
            }
        }
	    		    
    	$files = JRequest::getVar('jform', array(), 'FILES');
            if(!empty($files['name']['file_files_0'])){	    	
            $data['files'] = JResearchUtilities::uploadDocument($files, 'file_files_0', $params->get('files_root_path', 'files').DS.'staff');
    	}			

        // Bind the form fields to the hello table
        if (!$row->save($data, '' ,array('username')))
        {
            JRequest::setVar('jform', $data);
            $this->setError($row->getError());
            return false;
        }
        
        $data['id'] = $row->id;
        $app->setUserState('com_jresearch.edit.member.data', $data);

        return true;
    }

    /**
     * Publish a set of items
     */        
    function publish(&$pks, $value = 1) {
       $selected = JRequest::getVar('cid', 0, '', 'array');
       $member = JTable::getInstance('Member', 'JResearch');           
       $allOk = true;
       $user = JFactory::getUser();
       foreach($selected as $id){
       	   $action = JResearchAccessHelper::getActions('member', $id);           	
       	   if($action->get('core.staff.edit')){
    	       $allOk = $allOk && $member->publish(array($id), 1, $user->get('id'));
    	       if(!$allOk) $this->setError($member->getError());
       	   }else{
                $allOk = false;
                $this->setError(new JException(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $id)));           	   	   
       	   }
       }
       
       return $allOk;
    }

    /**
     * Unpublish a set of items
     */
    function unpublish(){
       $selected = JRequest::getVar('cid', array(), '', 'array');
       $member = JTable::getInstance('Member', 'JResearch');    
       $user = JFactory::getUser();       
       $allOk = true;
       foreach($selected as $id){
       	   $action = JResearchAccessHelper::getActions('member', $id);           	
       	   if($action->get('core.staff.edit')){
                $allOk = $allOk && $member->publish(array($id), 0, $user->get('id'));
                $this->setError($member->getError());	    	       
       	   } else {
                $allOk = false;
                $this->setError(new JException(JText::sprintf('JRESEARCH_EDIT_ITEM_STATE_NOT_ALLOWED', $id)));
       	   }
       }
       
       return $allOk;
    }
    
    /**
     * Delete a set of items
     */        
    function delete(&$pks) {
       $n = 0;
       $selected = JRequest::getVar('cid', 0, '', 'array');
       $area = JTable::getInstance('Member', 'JResearch');
       foreach($selected as $id) {
            $action = JResearchAccessHelper::getActions('member', $id);
            if($action->get('core.staff.delete')){
                if(!$area->delete($id)){
                    $this->setError(new JException(JText::sprintf('JRESEARCH_MEMBER_NOT_DELETED', $id)));
                }else{
                    $n++;
                }
            }else{
                $this->setError(new JException(JText::sprintf('JRESEARCH_DELETE_ITEM_NOT_ALLOWED', $id)));
            }
       }
       
       return $n;
    }

    function checkin($pk=null){
        $data = &$this->getData();

        if(!empty($data)){
            // Database processing
            $row = &$this->getTable('Member', 'JResearch');
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
    public function getItem($pk = null) {
        $row = $this->getTable('Member', 'JResearch');
        $data =& $this->getData();
        $row->bind($data);
        return $row;
    }
	
    /**
     * Returns the record with the username specified
     *
     * @param string $username
     * @return JResearchMember Member with the username specified.
     */
    public function getByUsername($username){
        $db = JFactory::getDBO();
        $query = "SELECT * FROM ".$db->quoteName('#__jresearch_member')." WHERE ".$db->quoteName('username').' = '.$db->Quote($username);
        $db->setQuery($query);
        $results = $db->loadAssoc();

        $member = JTable::getInstance('Member', 'JResearch');
        $member->bind($results);
        return $member;
    }

    /**
     * Returns an array with the n latest internal and published publications 
     * in which the member collaborated.
     *
     * @param int $memberId
     * @param int $n
     * @return array Array of JResearchPublicationObjects
     */
    function getLatestPublications($memberId, $n = 0){
        $db =& JFactory::getDBO();
        $latestPub = array();

        $query = 'SELECT '.$db->quoteName('id_publication').' FROM '.$db->quoteName('#__jresearch_publication_internal_author').' ia,  '
                .$db->quoteName('#__jresearch_publication').' p WHERE '.$db->quoteName('p').'.'.$db->quoteName('id').' = '.$db->quoteName('ia').'.'.$db->quoteName('id_publication').' '
                .' AND '.$db->quoteName('ia').'.'.$db->quoteName('id_staff_member').' = '.$db->Quote($memberId).' AND p.published = '.$db->Quote('1').' AND p.internal =  '.$db->Quote('1').' ORDER BY '.$db->quoteName('p').'.'.$db->quoteName('year').' DESC';

        if($n > 0){
            $query .= ' LIMIT 0, '.$n;
        }

        $db->setQuery($query);
        $result = $db->loadColumn();
        foreach($result as $id){
            $publication =& JResearchPublication::getById($id);
            $latestPub[] = $publication;
        }

        return $latestPub;
    }


    /**
     * Returns the number of publications where the member has participated.
     * 
     * @param int $memberId
     */
    function countPublications($memberId){
        $db =& JFactory::getDBO();

        $query = 'SELECT count(*) FROM '.$db->quoteName('#__jresearch_publication_internal_author').' WHERE '.$db->quoteName('id_staff_member').' = '.$db->Quote($memberId);
        $db->setQuery($query);		
        return (int)$db->loadResult();
    }

    /**
     * Returns an array with the n latest projects in which the member has collaborated.
     * @param int $memberId
     * @param int $n
     */
    function getLatestProjects($memberId, $n = 0){
        $db =& JFactory::getDBO();
        $latestProj = array();

        $query = 'SELECT '.$db->quoteName('id_project').' FROM '.$db->quoteName('#__jresearch_project_internal_author').' ia,  '
                         .$db->quoteName('#__jresearch_project').' p WHERE '.$db->quoteName('p').'.'.$db->quoteName('id').' = '.$db->quoteName('ia').'.'.$db->quoteName('id_project').' AND p.published = '.$db->Quote('1').' AND '.$db->quoteName('ia').'.'.$db->quoteName('id_staff_member').' = '.$db->Quote($memberId).' ORDER BY '.$db->quoteName('p').'.'.$db->quoteName('start_date').' DESC';

        if($n > 0){
                $query .= ' LIMIT 0, '.$n;
        }

        $db->setQuery($query);

        $result = $db->loadColumn();
        foreach($result as $id){
            $project = new JResearchProject($db);
            $project->load($id);
            $latestProj[] = $project;
        }

        return $latestProj;
    }


    /**
     * Returns the number of projects the member has participated.
     * @param int $memberId
     */
    function countProjects($memberId){
        $db =& JFactory::getDBO();

        $query = 'SELECT count(*) FROM '.$db->quoteName('#__jresearch_project_internal_author').' WHERE '.$db->quoteName('id_staff_member').' = '.$db->Quote($memberId);
        $db->setQuery($query);		
        return (int)$db->loadResult();
    }

    /**
     * Returns an array with the n latest theses in which the member has collaborated.
     * @param int $memberId
     * @param int $n
     */
    function getLatestTheses($memberId, $n = 0){
        $db =& JFactory::getDBO();
        $latestThes = array();

        $query = 'SELECT '.$db->quoteName('id_thesis').' FROM '.$db->quoteName('#__jresearch_thesis_internal_author').' ia,  '
                         .$db->quoteName('#__jresearch_thesis').' t WHERE '.$db->quoteName('t').'.'.$db->quoteName('id').' = '.$db->quoteName('ia').'.'.$db->quoteName('id_thesis').' AND t.published = '.$db->Quote('1')
                         .' AND '.$db->quoteName('ia').'.'.$db->quoteName('id_staff_member').' = '.$db->Quote($memberId).' ORDER BY '.$db->quoteName('t').'.'.$db->quoteName('start_date').' DESC';

        if($n > 0){
            $query .= ' LIMIT 0, '.$n;
        }

        $db->setQuery($query);
        $result = $db->loadColumn();
        foreach($result as $id){
            $thesis = new JResearchThesis($db);
            $thesis->load($id);
            $latestThes[] = $thesis;
        }

        return $latestThes;				
    }



    /**
     * Returns the number of degree theses the member has participated.
     * @param int $memberId
     */
    function countTheses($memberId){
        $db =& JFactory::getDBO();

        $query = 'SELECT count(*) FROM '.$db->quoteName('#__jresearch_thesis_internal_author').' WHERE '.$db->quoteName('id_staff_member').' = '.$db->Quote($memberId);
        $db->setQuery($query);
        return (int)$db->loadResult();
    }


    public function getTeams($memberId)
    {
        $db = JFactory::getDBO();
        $teams = array();

        $sql = 'SELECT '.$db->quoteName('id_team').' FROM '.$db->quoteName('#__jresearch_team_member').' WHERE '.$db->quoteName('id_member').' = '.$db->Quote($memberId);
        $db->setQuery($sql);

        $ids = $db->loadColumn();

        foreach($ids as $id) {
            $team = JTable::getInstance('Team', 'JResearch');
            $team->load($id);
            $teams[] = $team;
        }
        return $teams;
    }

    public function getCV(){
        if(!empty($this->files))
            return JURI::root().'administrator/components/com_jresearch/'.str_replace(DS, DS, $params->get('files_root_path', 'files'))."/staff/".$this->files;

        return false;	
    }
}
?>
