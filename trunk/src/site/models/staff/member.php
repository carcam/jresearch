<?php
/**
* @package		JResearch
* @subpackage	Models
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('JPATH_BASE') or die;

jresearchimport('models.modelform', 'jresearch.site');

/**
* Model class for holding a single research area record.
*
*/
class JResearchModelMember extends JResearchModelForm{	
	
	/**
    * Returns the model data store in the user state as a table
    * object
    */
    public function getItem(){
    	if(!isset($this->_row)){
        	$row = $this->getTable('Member', 'JResearch');
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

    /**
     * Method to get the data.
     *
     * @access      public
     * @return      array of string
     * @since       1.0
     */
    public function &getData()
    {
    	if(!isset($this->_data)){
	    	$app = & JFactory::getApplication();
	    	$data = & JRequest::getVar('jform');
	    	if (empty($data))
	    	{
	    		// For new items
	    		jresearchimport('helpers.staff', 'jresearch.admin');
	    		$selected = & JRequest::getVar('cid', 0, '', 'array');
	    		$user = JFactory::getUser();
	    		$data = JResearchStaffHelper::getMemberArrayFromUsername($user->get('username'));
	    	}
	
	    	if (empty($data))
	    	{
	    		// Check the session for previously entered form data.
	    		$data = $app->getUserState('com_jresearch.edit.member.data', array());
	    	}
	
	    	//Once the data is retrieved, time to fix it
	    	if(is_string($data['id_research_area'])){
	    		$data['id_research_area'] = explode(',', $data['id_research_area']);
	    	}

	    	// Store the state as an array of values
	    	$app->setUserState('com_jresearch.edit.member.data', $data);
	        $this->_data = $data;
	    }

        return $this->_data;
    }    
            
	/**
	 * Returns an array with the n latest internal and published publications 
	 * in which the member collaborated.
	 *
	 * @param int $n
	 * @return array Array of JResearchPublicationObjects
	 */
	function getLatestPublications($n = 0){
		$db = JFactory::getDBO();
		$latestPub = array();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);
		
		$query = 'SELECT p.* FROM '.$db->nameQuote('#__jresearch_publication_internal_author').' ia JOIN '
				 .$db->nameQuote('#__jresearch_publication').' p WHERE '.$db->nameQuote('p').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_publication').' '
				 .' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId)
				 .' AND p.published = '.$db->Quote('1').' AND p.internal =  '.$db->Quote('1').' ORDER BY '.$db->nameQuote('p').'.'.$db->nameQuote('year')
				 .' DESC, STR_TO_DATE(p.'.$db->nameQuote('month').', \'%M\' ) DESC, '.'p.'.$db->nameQuote('created').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $row){
			$publication = JTable::getInstance('Publication', 'JResearch');
			$publication->bind($row);
			$latestPub[] = $publication;
		}
		
		return $latestPub;				 
	}
	
	
	/**
	 * Returns the number of publications where the member has participated.
	 * 
	 */
	function countPublications(){
		$db = JFactory::getDBO();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);
		
		$internal_author = $db->nameQuote('#__jresearch_publication_internal_author');
		$publications = $db->nameQuote('#__jresearch_publication');				
		$memberValue = $db->Quote($memberId);

		$query = "SELECT COUNT(*) FROM $internal_author pia, $publications p WHERE pia.id_publication = p.id 
		AND p.published = 1 AND p.internal = 1 AND pia.id_staff_member = $memberValue";
		$db->setQuery($query);		
		
		return (int)$db->loadResult();
	}

	/**
	 * Returns an array with the n latest projects in which the member has collaborated.
	 * @param int $n
	 */
	function getLatestProjects($n = 0){
		$db = JFactory::getDBO();
		$latestProj = array();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);		
		
		$query = 'SELECT p.* FROM '.$db->nameQuote('#__jresearch_project_internal_author').' ia,  '
				 .$db->nameQuote('#__jresearch_project').' p WHERE '.$db->nameQuote('p').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_project')
				 .' AND p.published = '.$db->Quote('1').' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId)
				 .' ORDER BY '.$db->nameQuote('p').'.'.$db->nameQuote('start_date').' DESC, '.$db->nameQuote('p').'.'.$db->nameQuote('end_date').' DESC, p.'.$db->nameQuote('created').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);

		$result = $db->loadAssocList();
		foreach($result as $row){
			$project = JTable::getInstance('Project', 'JResearch');
			$project->bind($row);
			$latestProj[] = $project;
		}
		
		return $latestProj;
	}
	
		
	/**
	 * Returns the number of projects the member has participated.
	 */
	function countProjects(){
		$db = JFactory::getDBO();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);		

		$internal_author = $db->nameQuote('#__jresearch_project_internal_author');
		$projects = $db->nameQuote('#__jresearch_project');				
		$memberValue = $db->Quote($memberId);
		
		$query = "SELECT COUNT(*) FROM $internal_author pia, $projects p 
				 WHERE pia.id_project = p.id AND p.published = 1 
				 AND pia.id_staff_member = $memberValue";
		
		$db->setQuery($query);		
		
		return (int)$db->loadResult();
	}
	
	/**
	 * Returns an array with the n latest theses in which the member has collaborated.
	 * @param int $n
	 */
	function getLatestTheses($n = 0){
		$db = JFactory::getDBO();
		$latestThes = array();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);		
		
		$query = 'SELECT t.* FROM '.$db->nameQuote('#__jresearch_thesis_internal_author').' ia,  '
				 .$db->nameQuote('#__jresearch_thesis').' t WHERE '.$db->nameQuote('t').'.'.$db->nameQuote('id').' = '.$db->nameQuote('ia').'.'.$db->nameQuote('id_thesis')
				 .' AND t.published = '.$db->Quote('1').' AND '.$db->nameQuote('ia').'.'.$db->nameQuote('id_staff_member').' = '.$db->Quote($memberId)
				 .' ORDER BY '.$db->nameQuote('t').'.'.$db->nameQuote('start_date').' DESC, '.$db->nameQuote('t').'.'.$db->nameQuote('end_date').' DESC, t.'.$db->nameQuote('created').' DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $row){
			$thesis = JTable::getInstance('Thesis', 'JResearch');
			$thesis->bind($row);
			$latestThes[] = $thesis;
		}
		
		return $latestThes;				
	}
	

		
	/**
	 * Returns the number of degree theses the member has participated.
	 * @param int $memberId
	 */
	function countTheses($memberId){
		$db = JFactory::getDBO();
		$memberId = !empty($this->_row)? $this->_row->id : JRequest::getVar('id', 0);
				
		$internal_author = $db->nameQuote('#__jresearch_thesis_internal_author');
		$theses = $db->nameQuote('#__jresearch_thesis');				
		$memberValue = $db->Quote($memberId);
		
		$query = "SELECT COUNT(*) FROM $internal_author pia, $theses p 
		WHERE pia.id_thesis = p.id AND p.published = 1 AND pia.id_staff_member = $memberValue";
		$db->setQuery($query);		

		return (int)$db->loadResult();
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
    function save()
    {
        $app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_jresearch');
        $data = &$this->getData();

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
	    JError::raiseWarning(1, var_export($files, true));    	
		if(!empty($files['name']['file_files_0'])){	    	
	    	$data['files'] = JResearchUtilities::uploadDocument($files, 'file_files_0', $params->get('files_root_path', 'files').DS.'staff');
    	}

        // Bind the form fields to the hello table
        if (!$row->save($data))
        {
            $this->setError($row->getError());
            return false;
        }

        $app->setUserState('com_jresearch.edit.member.data', $data);
        $this->_row = $row;

    	return true;
    }
}

?>