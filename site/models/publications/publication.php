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
* Model class for holding a single publication record.
*
*/
class JResearchModelPublication extends JResearchModelForm{
	
    /**
     * Returns the model data store in the user state as a table
     * object
     */
    public function getItem(){
        if(!isset($this->_row)){
            $row = $this->getTable('Publication', 'JResearch');
             if($row->load(JRequest::getInt('id'))){
                 if($row->published && $row->internal)
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
        if (empty($this->_data))
        {
            $app = & JFactory::getApplication();
            $data = & JRequest::getVar('jform');            
            if (empty($data))
            {
            	$selected = JRequest::getInt('id', 0);
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select all fields from the hello table.
                $query->select('*');
                $query->from('`#__jresearch_publication`');
                $query->where('id = ' . $selected);
                $db->setQuery((string)$query);
                $data = & $db->loadAssoc();
             }
             
                          
             if (empty($data))
             {
				// Check the session for previously entered form data.
                $data = $app->getUserState('com_jresearch.edit.publication.data', array());
             }
                    
             //Once the data is retrieved, time to fix it
             if(is_string($data['id_research_area'])){
             	$data['id_research_area'] = explode(',', $data['id_research_area']);
             }
                    
             $app->setUserState('com_jresearch.edit.publication.data', $data);
             $this->_data = $data;
        }
        
        return $this->_data;
    }    
    
    /**
    * Method to get the HelloWorld form.
    *
    * @access      public
    * @return      mixed   JForm object on success, false on failure.
    * @since       1.0
    */
    public function getForm($data = array(), $loadData = true){
    	$pubtype = JRequest::getVar('pubtype', 'article');
        $form = $this->loadForm('com_jresearch.'.$pubtype, $pubtype, array('control' => 'jform', 'load_data' => $loadData));
        return $form;
    }
        
    /**
    * Method to save a record
    *
    * @access      public
    * @return      boolean True on success
    */
    function save(){
		$app = JFactory::getApplication();
		jresearchimport('helpers.publications', 'jresearch.admin');
		$params = JComponentHelper::getParams('com_jresearch');
		$omittedFields = array();
		$user = JFactory::getUser();
                
        $data =& $this->getData();                
        $row =& $this->getTable('Publication', 'JResearch');

        //Time to upload the file
        $delete = $data['delete_files_0'];
	    if($delete == 1){
	    	if(!empty($data['old_files_0'])){
		    	$filetoremove = JRESEARCH_COMPONENT_ADMIN.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.$row->files;
		    	$data['files'] = '';
		    	@unlink($filetoremove);
	    	}
		}
		    		    
	    $files = JRequest::getVar('jform', array(), 'FILES');
		if(!empty($files['name']['file_files_0'])){	    	
			$data['files'] = JResearchUtilities::uploadDocument($files, 'file_files_0', $params->get('files_root_path', 'files').DS.'publications');
	    }
	    		
	    if($data['resethits'] == 1){
			$data['hits'] = 0;
		}else{
			$omittedFields[] = 'hits';			    	
		}
			    			    			    
		//Now time for the authors
		$maxAuthors = (int)$data['nauthorsfield'];
		$authorsArray = array();
		for($j = 0; $j < $maxAuthors; $j++){
			$value = $data["authorsfield".$j];
			if(!empty($value)){
				$row->addAuthor($value);
			}
		}
				
		$data['authors'] = $row->authors;
				
		//Citekey generation
		if(empty($data['citekey'])){
			$data['citekey'] = JResearchPublicationsHelper::generateCitekey($data);
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
								
        if (!$row->save($data, '', $omittedFields))
        {
        	JRequest::setVar('jform', $data);        	
        	$this->setError($row->getError());
            return false;
        }
        
        $app->setUserState('com_jresearch.edit.publication.data', $data);
        $data['id'] = $row->id;
        $this->_row = $row;
        $this->_data &= $data;

        return true;
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

        //Time to upload the file
        $delete = $data['delete_url'];

        if($delete == 1){
	    	if(!empty($data['files'])){
		    	$filetoremove = JRESEARCH_COMPONENT_ADMIN.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.$row->files;
		    	$data['files'] = '';
		    	@unlink($filetoremove);
	    	}
		}
		    		    
	    $files = JRequest::getVar('jform', array(), 'FILES');
		if(!empty($files['name']['file_url'])){	    	
			$data['files'] = JResearchUtilities::uploadDocument($files, 'file_url', $params->get('files_root_path', 'files').DS.'publications');
	    }
	    		
	    if($data['resethits'] == 1){
			$data['hits'] = 0;
		}else{
			$omittedFields[] = 'hits';			    	
		}
			    			    			    
		//Now time for the authors
		$maxAuthors = (int)$data['nauthorsfield'];
		$authorsArray = array();
		for($j = 0; $j < $maxAuthors; $j++){
			$value = $data["authorsfield".$j];
			if(!empty($value)){
				$row->addAuthor($value);
			}
		}
				
		$data['authors'] = $row->authors;
		$data['pubtype'] = JRequest::getVar('change_type', 'article');
		$keepOld = JRequest::getVar('keepold', null);
			
		//Store it as a new publication
		if($keepOld == 'on'){
			unset($data['id']);
			$data['title'] = $data['title'].' (Copy)'; 
		}
			
				
		//Citekey generation
		$data['citekey'] = JResearchPublicationsHelper::generateCitekey($data);
				
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
							
        if (!$row->save($data, '', $omittedFields)){
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
    * 
    * Returns the number of removed items based on the 
    * selected items
    */
    function delete(){
    	$n = 0;
        $id = JRequest::getInt('id', 0);
        $publication = JTable::getInstance('Publication', 'JResearch');
        $user = JFactory::getUser();           
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
                                         
       	return $n;           
    }
}
?>