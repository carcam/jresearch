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


jresearchimport('joomla.application.component.modelform');

/**
* Model class for holding a single project record.
*
* @subpackage	Projects
*/
class JResearchAdminModelProject extends JModelForm{
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
    public function &getData(){
		if (empty($this->data)){
	        $app = & JFactory::getApplication();
	        $data = & JRequest::getVar('jform');
	        if (empty($data)){
	            $selected = & JRequest::getVar('cid', 0, '', 'array');
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('`#__jresearch_project`');
                $query->where('id = ' . (int)$selected[0]);
                $db->setQuery((string)$query);
                $data = & $db->loadAssoc();
	        }
	        
	        if (empty($data)){
				// Check the session for previously entered form data.
	            $data = $app->getUserState('com_jresearch.edit.project.data', array());
	        }
	                    
	        //Once the data is retrieved, time to fix it
	        if(is_string($data['id_research_area'])){
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
         * Method to save a record
         *
         * @access      public
         * @return      boolean True on success
         */
        function save()
        {
			$app = JFactory::getApplication();
			jresearchimport('helpers.projects', 'jresearch.admin');
			$params = JComponentHelper::getParams('com_jresearch');
			$omittedFields = array();
                
            $data =& $this->getData();                
            $row =& $this->getTable('Project', 'JResearch');
	    		
	    	if($data['resethits'] == 1){
				$data['hits'] = 0;
			}else{
				$omittedFields[] = 'hits';			    	
			}
			
        	//Remove files in case the user indicated it
            $nAttach = $data['count_files'];
            $data['files'] = '';
	    	$tempFilesArr = array();            
            for($i = 0; $i <= $nAttach; ++$i){
				$delete = $data['delete_files_'.$i];
		    	$theFile = $data['old_files_'.$i];		    	
				if($delete == 'on'){
			    	$filetoremove = JRESEARCH_COMPONENT_ADMIN.DS.$params->get('files_root_path', 'files').DS.'projects'.DS.$theFile;
			    	@unlink($filetoremove);
			    }else{
			    	$tempFilesArr[] = $theFile;
			    }
            }
		    		    
            //Now update files
	    	$files = JRequest::getVar('jform', array(), 'FILES');
	    	for($i = 0; $i <= $nAttach; ++$i){
				if(!empty($files['name']['file_files_'.$i])){	    	
		 		   	$tempFilesArr[] = JResearchUtilities::uploadDocument($files, 'file_files_'.$i, $params->get('files_root_path', 'files').DS.'projects');
		    	}
	    	}
	    	$data['files'] = implode(';', $tempFilesArr);
			    			    			    
			//Now time for the authors
			$maxAuthors = (int)$data['nauthorsfield'];
			$authorsArray = array();
			for($j = 0; $j < $maxAuthors; $j++){
				$value = $data["authorsfield".$j];
				$flagValue = $data["check_authorsfield".$j];
				if(!empty($value)){
					$row->addAuthor($value, $flagValue);
				}
			}
				
			$data['authors'] = $row->authors;
								
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
        function publish(){
           $selected = JRequest::getVar('cid', 0, '', 'array');
           JError::raiseWarning(1, var_export($selected, true));
	       $project = JTable::getInstance('Project', 'JResearch');           
	       $allOk = true;
	       $user = JFactory::getUser();
           foreach($selected as $id){
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
		   $selected = JRequest::getVar('cid', 0, '', 'array');
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
        function delete(){
           	$n = 0;
           	$selected = JRequest::getVar('cid', 0, '', 'array');
           	$project = JTable::getInstance('Project', 'JResearch');
           	$user = JFactory::getUser();           
           	foreach($selected as $id){
        		$actions = JResearchAccessHelper::getActions('project', $id);
        		if($actions->get('core.projects.delete')){           	
	                $project->load($id);
		           	if(!$project->isCheckedOut($user->get('id'))){	
        	        	if($project->delete($id)){
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
        	$cid = JRequest::getVar('cid', array());
        	$result = array();
        	foreach($cid as $id){
        		$proj = JTable::getInstance('Project', 'JResearch');
        		$proj->load($id);
        		$result[] = $proj;
        	}
        	
        	return $result;
        }
        
        /**
		* Ordering item
		*/
		function orderItem($item, $movement)
		{
            $row = JTable::getInstance('Project', 'JResearch');
            $row->load($item);

            if (!$row->move($movement))
            {
                $this->setError($row->getError());
                return false;
            }

            return true;
		}

		/**
		 * Set ordering
		*/
		function setOrder($items)
		{
			$total = count($items);
            $row = JTable::getInstance('Project', 'JResearch');

			$order = JRequest::getVar( 'order', array(), 'post', 'array' );
			JArrayHelper::toInteger($order);

			// update ordering values
			for( $i=0; $i < $total; $i++ )
			{
				$row->load( $items[$i] );

				$groupings[] = $row->former_member;
				if ($row->ordering != $order[$i])
				{
					$row->ordering = $order[$i];
					if (!$row->store())
					{
						$this->setError($row->getError());
						return false;
					}
				} // if
			} // for

			// execute updateOrder
			$groupings = array_unique($groupings);
			foreach ($groupings as $group)
			{
				$row->reorder(' AND published >= 0');
			}

			return true;
		}
}
?>
