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
                    $app = & JFactory::getApplication();
                    $data = & JRequest::getVar('jform');
                    if (empty($data))
                    {
                            $selected = & JRequest::getVar('cid', 0, '', 'array');
                            $db = JFactory::getDBO();
                            $query = $db->getQuery(true);
                            // Select all fields from the hello table.
                            $query->select('*');
                            $query->from('`#__jresearch_publication`');
                            $query->where('id = ' . (int)$selected[0]);
                            $db->setQuery((string)$query);
                            $data = & $db->loadAssoc();
                    }
                    if (empty($data))
                    {
                            // Check the session for previously entered form data.
                            $data = $app->getUserState('com_jresearch.edit.publication.data', array());
                            unset($data['id']);
                    }
                    
                    //Once the data is retrieved, time to fix it
                    if(is_string($data['id_research_area'])){
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
        function save()
        {
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
	    		
			    $reset = JRequest::getVar('resethits', false);
	    		if($reset == 'on'){
			    	$data['hits'] = 0;
			    	$omittedFields[] = 'hits';
			    }
			    			    			    
			    //Now time for the authors
				$maxAuthors = $data['nauthorsfield'];
				$k = 0;
				$authorsArray = array();
				for($j = 0; $j <= $maxAuthors; $j++){
					$value = JRequest::getVar("authorsfield".$j);
					if(!empty($value)){
						if(is_numeric($value)){
							// In that case, we are talking about a staff member
							$publication->setAuthor($value, $k, true); 
						}else{
							// For external authors 
							$publication->setAuthor($value, $k);
						}
						$authorsArray[] = $value;
						$k++;
					}
				}
				
				$data['authors'] = implode(',', $authorsArray);
				//Citekey generation
				$data['citekey'] = JResearchPublicationsHelper::generateCitekey($data);
				
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
                    $this->setError($row->getError());
                    return false;
                }

                $app->setUserState('com_jresearch.edit.publication.data', $data);

                return true;
        }
        
        /**
         * Publishes the set of selected items
         */
        function publish(){
           $selected =& JRequest::getVar('cid', 0, '', 'array');
           $publication = JTable::getInstance('Publication', 'JResearch');
           return $publication->publish($selected, 1);
        }

        /**
         * Unpublishes the set of selected items
         */
        function unpublish(){
           $selected =& JRequest::getVar('cid', 0, '', 'array');
           $publication = JTable::getInstance('Publication', 'JResearch');
           return $publication->publish($selected, 0);

        }

        /**
         * 
         * Returns the number of removed items based on the 
         * selected items
         */
        function delete(){
           $n = 0;
           $selected =& JRequest::getVar('cid', 0, '', 'array');
           $publication = JTable::getInstance('Publication', 'JResearch');
           $user = JFactory::getUser();
           foreach($selected as $id){
                $publication->load($id);
	           	if(!$publication->isCheckedOut($user->get('id'))){	
                	if($publication->delete($id)){
            	        $n++;
                	}
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
        	$cid = JRequest::getVar('cid', array());
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
        	$cid = JRequest::getVar('cid', array());
        	$user = JFactory::getUser();
        	$n = 0;
        	foreach($cid as $id){
        		if($publication->toggleInternal(array($id), $value, $user->get('id'))){
        			$n++;
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
        	$cid = JRequest::getVar('cid', array());
        	$publication->load($cid[0]);
        	if(!empty($publication->id)){
        		$user = JFactory::getUser();
        		if(!$publication->isCheckedOut($user->get('id'))){
	        		$publication->internal = !$publication->internal;	
	        		return $publication->store();
        		}else{
        			return false;
        		}
        	}
        	
        	return false;
        }
	
}
?>
