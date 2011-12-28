<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of bibliographical references or publications in the backend interface.
*/

jimport('joomla.application.component.controller');

/**
* Publications Backend Controller
* @package		JResearch
* @subpackage	Publications
*/
class JResearchAdminPublicationsController extends JController
{

	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
            parent::__construct();

            $lang = JFactory::getLanguage();
            $lang->load('com_jresearch.publications');

            // Tasks for edition of publications when the user is authenticated
            $this->registerTask('add', 'add');
            $this->registerTask('edit', 'edit');
            $this->registerTask('publish', 'publish');
            $this->registerTask('unpublish', 'unpublish');
            $this->registerTask('makeinternal', 'changeInternalStatus');
            $this->registerTask('makenoninternal', 'changeInternalStatus');
            $this->registerTask('remove', 'remove');
            $this->registerTask('import', 'import');
            $this->registerTask('export', 'export');
            $this->registerTask('exportAll', 'export');
            $this->registerTask('exportSingle', 'exportSingle');
            $this->registerTask('executeImport', 'executeImport');
            $this->registerTask('executeExport', 'executeExport');
            $this->registerTask('save', 'save');
            $this->registerTask('apply', 'save');
            $this->registerTask('cancel', 'cancel');
            $this->registerTask('toggle_internal', 'toggle_internal');
            $this->registerTask('toggle_featured', 'toggle_featured');            
            $this->registerTask('changeType', 'changeType');
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'publications');
            JResearchPluginsHelper::verifyPublicationPluginsInstallation();
	}

	/**
	 * Default method, it shows the list of publications for administration. 
	 *
	 * @access public
	 */

	function display(){
            JResearchUnlockerHelper::unlockItems('publication');
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'researchareas');
            $view = &$this->getView('PublicationsList', 'html', 'JResearchAdminView');
            $pubModel = &$this->getModel('PublicationsList', 'JResearchModel');
            $model = &$this->getModel('ResearchAreasList', 'JResearchModel');
            $raModel = &$this->getModel('ResearchArea', 'JResearchModel');

            $view->setModel($pubModel, true);
            $view->setModel($model);
            $view->setModel($raModel);
            $view->display();
	}

	/**
	* Invoked when an administrator decides to create a publication. Prints
	* a form where the user can select the type of publication to select.
	* @access public
	*/
	function add(){
            $view = &$this->getView('Publication', 'html', 'JResearchAdminView');
            $view->setLayout('new');
            $view->display();
	}

	/**
	* Invoked when the user wants to edit an existing publication or has selected
	* the type for a new one.
	*/	
	function edit(){
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'researchareas');
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'teams');            
            $cid = JRequest::getVar('cid', array());
            $view = &$this->getView('Publication', 'html', 'JResearchAdminView');
            $pubModel = &$this->getModel('Publication', 'JResearchModel');
            $model = &$this->getModel('ResearchAreasList', 'JResearchModel');
            $teamsModel = &$this->getModel('Teams', 'JResearchModel');

            if(!empty($cid)){
                    $publication = $pubModel->getItem($cid[0]);
                    if(!empty($publication)){
                            $user =& JFactory::getUser();
                            // Verify if it is checked out
                            if($publication->isCheckedOut($user->get('id'))){
                                    $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
                            }else{
                                    $publication->checkout($user->get('id'));
                                    $view->setLayout('default');
                                    $view->setModel($model);
                                    $view->setModel($teamsModel);
                                    $view->display();
                            }
                    }else{
                            JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                            $this->setRedirect('index.php?option=com_jresearch&controller=publications');
                    }
            }else{
                    $view->setLayout('default');
                    $view->setModel($model);
                    $view->setModel($teamsModel);                    
                    $view->display();
            }
	}

	/**
	* Invoked when an administrator has decided to publish a one or more items
	* @access	public
	*/ 
	function publish(){
       // Array of ids
       $cid = JRequest::getVar('cid');
       $user = JFactory::getUser();

       $publication = JTable::getInstance('Publication', 'JResearch');
       
       if($publication->publish($cid, 1, $user->get('id')))
       		$message = JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY');
       else
         	$message = JText::_('JRESEARCH_ITEMS_PUBLISHED_UNSUCCESSFULLY');	
       
       $this->setRedirect('index.php?option=com_jresearch&controller=publications', $message);				
	}

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
		// Array of ids
       $cid = JRequest::getVar('cid');
       $user = JFactory::getUser();

       $publication = JTable::getInstance('Publication', 'JResearch');
       
       if($publication->publish($cid, 0, $user->get('id')))
       		$message = JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY');
       else
         	$message = JText::_('JRESEARCH_ITEMS_UNPUBLISHED_UNSUCCESSFULLY');	
       
       $this->setRedirect('index.php?option=com_jresearch&controller=publications', $message);
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
            $cid = JRequest::getVar('cid');
            $user = JFactory::getUser();
            $n = 0;
            $publication = JTable::getInstance('Publication', 'JResearch');
            foreach($cid as $id){ 
                 if(!$publication->delete($id))
                      JError::raiseWarning(1, JText::sprintf('JRESEARCH_PUBLICATION_NOT_DELETED', $id));
                    else
                      $n++;
            }
            $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_SUCCESSFULLY_DELETED', $n));
	}
	
	/**
	* Invoked when an administrator has decided to import publications from a file.
	* @access	public
	*/
	function import(){
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'researchareas');
            $view = &$this->getView('PublicationsList', 'html', 'JResearchAdminView');
            $model = &$this->getModel('ResearchAreasList', 'JResearchModel');
            $view->setLayout('import');
            $view->setModel($model);
            $view->display();
	}
	
	/**
	* Invoked when an administrator has decided to export a set of publications.
	* @access	public
	*/
	function export(){
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'publications');
            $view = &$this->getView('PublicationsList', 'html', 'JResearchAdminView');
            $model = &$this->getModel('PublicationsList', 'JResearchModel');
            $view->setModel($model, true);
            $view->setLayout('export');
            $view->display();
	}

	/**
	* Invoked when the user exports a single publication from backend list.
	*/	
	function exportSingle(){		
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'publications');
            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'exporters'.DS.'factory.php');
            $document =& JFactory::getDocument();


            $id = JRequest::getInt('id');
            $format = JRequest::getVar('format');
            $model = &$this->getModel('Publication', 'JResearchModel');
            $publication = $model->getItem($id);

            $exporter =& JResearchPublicationExporterFactory::getInstance($format);
            $output = $exporter->parse($publication);
            $document->setMimeEncoding($exporter->getMimeEncoding());

            if($format == 'bibtex')
                    $ext = 'bib';
            else
                    $ext = $format;

            $tmpfname = "jresearch_output.$ext";
            header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
            echo $output;
	}

	/**
	 * Takes the sent file and imports its contents into the database of
	 * available publications.
	 *
	 */
	function executeImport(){
            //global $mainframe;
            $fileArray = JRequest::getVar('inputfile', null, 'FILES');
            $format = JRequest::getVar('formats');
            $idResearchArea = JRequest::getVar('researchAreas');
            $uploadedFile = $fileArray['tmp_name'];
            //$savedRecords = 0;

            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'importers'.DS.'factory.php');

            if($fileArray == null || $uploadedFile == null){
                JError::raiseWarning(1, JText::_('JRESEARCH_NO_INPUT_FILE'));
                $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=import');
            }else{
                $importer = &JResearchPublicationImporterFactory::getInstance($format);
                $parsedPublications = $importer->parseFile($uploadedFile);
                $n = 0;
                foreach($parsedPublications as $p){
                    $p->id_research_area = $idResearchArea;
                    if(!$p->store()){
                        JError::raiseWarning(1, JText::_('PUBLICATION_COULD_NOT_BE_SAVED').': '.$p->getError());
                    }else{
                        $n++;
                    }
                }
                $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_IMPORTED_ITEMS', count($parsedPublications), $n));
            }
		
	}
	
	/**
	 * Triggered when the user clicks the submit button in the export publications
	 * form.
	 *
	 */
	function executeExport(){
            $session = JFactory::getSession();
            $exportOptions = array();

            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'publications');
            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'exporters'.DS.'factory.php');
            $markedRecords = $session->get('markedRecords', null, 'jresearch');
            if($markedRecords !== null){
                if($markedRecords === 'all'){
                        $model = &$this->getModel('PublicationsList', 'JResearchModel');
                        $publicationsArray = $model->getData();
                }else{
                        $model = &$this->getModel('Publication', 'JResearchModel');
                        $publicationsArray = array();
                        foreach($markedRecords as $id){
                                $publicationsArray[] = $model->getItem($id);
                        }
                }

                $strictBibtex = JRequest::getVar('strict_bibtex');
                if($strictBibtex == 'on')
                        $exportOptions['strict_bibtex'] = true;

                $format = JRequest::getVar('outformat');
                $exporter =& JResearchPublicationExporterFactory::getInstance($format);
                $output = $exporter->parse($publicationsArray, $exportOptions);
                $document =& JFactory::getDocument();
                $document->setMimeEncoding($exporter->getMimeEncoding());
                $session->clear('markedRecords', 'jresearch');


                if($format == 'bibtex')
                        $ext = 'bib';
                else
                        $ext = $format;
                $tmpfname = "jresearch_output.$ext";
                header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
                echo $output;
            }else{
                JError::raiseNotice(1, JText::_('JRESEARCH_SELECT_ITEMS_TO_EXPORT'));
                $this->setRedirect('index.php?option=com_jresearch&controller=publications');
            }
	}

	/**
	* Invoked when the user has decided to save a publication.
	*/	
	function save(){		
            global $mainframe;
		    if(!JRequest::checkToken())
            {
                $this->setRedirect('index.php?option=com_jresearch');
                return;
            }

            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'jresearch.php');
            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');

            $db = JFactory::getDBO();

            // Bind request variables to publication attributes
            $post = JRequest::get('post');
            $type = JRequest::getVar('pubtype');
            $publication = JTable::getInstance("Publication", "JResearch");
            $params = JComponentHelper::getParams('com_jresearch');
            $user = JFactory::getUser();
            $id = JRequest::getInt('id');
	   
		    $publication->bind($post);
		    $countUrl = JRequest::getInt('count_url', 0);
		    $file = JRequest::getVar('file_url_'.$countUrl, null, 'FILES');
            $previousFile = JRequest::getVar('old_url_0', null);
            $filetoremove = JRESEARCH_COMPONENT_ADMIN.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.$previousFile;
		
            //Verify if the user wants to remove old files
            $delete = JRequest::getVar('delete_url_0', false);
	    	if($delete === 'on'){	    	
	    		if($previousFile != null){
                    @unlink($filetoremove);
                    $publication->files = '';
	    		}
	    	}
	    
			// Upload new file	    
            if(!empty($file['name'])){
		    	$publication->files = JResearch::uploadDocument($file, $params->get('files_root_path', 'files').DS.'publications');			
	    		if($previousFile != null){
                    //Remove previous file if it has not been removed yet
                    if(file_exists($filetoremove))
                        @unlink($filetoremove);
	    		}

	    	}	    
	    
		    $reset = JRequest::getVar('resethits', false);
		    if($reset == 'on'){
	    		$publication->hits = 0;
	    	}
	    	
            //Generate an alias if needed
            $alias = trim(JRequest::getVar('alias'));
            if(empty($alias)){
                $publication->alias = JResearch::alias($publication->title);
            }else{
                $publication->alias = JResearch::alias($publication->alias);
            }
	    			
            $check = $publication->check();

            // Validate publication
            if(!$check){
                for($i=0; $i<count($publication->getErrors()); $i++)
                        JError::raiseWarning(1, $publication->getError($i));

                if($publication->id)
                    $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype);
                else
                    $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&pubtype='.$publication->pubtype);
            }else{
                //Time to set the authors
                $maxAuthors = JRequest::getInt('nauthorsfield');
                $k = 0;

                for($j=0; $j<=$maxAuthors; $j++){
                        $value = JRequest::getVar("authorsfield".$j);
                        if(!empty($value)){
                                if(is_numeric($value)){
                                        // In that case, we are talking about a staff member
                                        $publication->setAuthor($value, $k, true);
                                }else{
                                        // For external authors
                                        $publication->setAuthor($value, $k);
                                }

                                $k++;
                        }
                }

                	    	
            	//Citekey generation
            	$oldCitekey = JRequest::getVar('citekey');
            	if(empty($oldCitekey))
					$publication->citekey = JResearchPublicationsHelper::generateCitekey($publication);
                        
                
                // Set the id of the author if the item is new
                if(empty($publication->id))
                        $publication->created_by = $user->get('id');

                // Now, save the record
                $task = JRequest::getVar('task');
                $mainframe->triggerEvent('onBeforeSaveJResearchEntity', array('publication', $publication));
                if($publication->store(true)){

                        if($task == 'apply'){
                                $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype, JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED'));
                        }elseif($task == 'save'){
                                $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED'));
                        }

                        // Trigger event
                        $arguments = array('publication', $publication->id);
                        $mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);
                }else{
                    $idText = !empty($publication->id) && $task == 'apply'?'&cid[]='.$publication->id:'';

                    if($db->getErrorNum() == 1062){
                        //modify the citekey and the title only when we have duplicate data.
                        $publication->citekey = $publication->citekey."_1";
                        $publication->title = $publication->title."_1";   
                        if($publication->store(true)){
                                if($task == 'apply'){
                                        $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype, JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED_WITH_WARNINGS'));
                                }elseif($task == 'save'){
                                        $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED_WITH_WARNINGS'));
                                }

                                // Trigger event
                                $arguments = array('publication', $publication->id);
                                $mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);
                        }
                        else{
                            if($db->getErrorNum() == 1062)
                                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_SAVED').': '.JText::_('JRESEARCH_DUPLICATED_RECORD'));
                            else
                                JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.$db->getErrorMsg());

                            $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit'.$idText.'&pubtype='.$publication->pubtype);
                    
                        } 
                    }else {
                        JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.$db->getErrorMsg());
                        $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit'.$idText.'&pubtype='.$publication->pubtype);
                    }
               }
            }

            if(!empty($publication->id)){
                $user =& JFactory::getUser();
                if(!$publication->isCheckedOut($user->get('id'))){
                    if(!$publication->checkin())
                        JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
                }
            }

	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing publications.
	 *
	 */
	function cancel(){
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Publication', 'JResearchModel');		
		
		if($id != null){
			$publication = $model->getItem($id);
			if(!$publication->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=publications');
	}
	
	
	/**
	 * Invoked when the user has pressed any of the buttons for changing internal 
	 * flag for publications. 
	 *
	 */
	function changeInternalStatus(){
		$cid = JRequest::getVar('cid');
		$task = JRequest::getVar('task');		
		$db = JFactory::getDBO();

		$publication = JTable::getInstance('Publication', 'JResearch');
		if($publication->toggleInternal($cid, $task == 'makeinternal'?1:0))
			$message = JText::_('JRESEARCH_TOGGLE_INTERNAL_SUCCESSFULLY');
		else
			$message = JText::_('JRESEARCH_TOGGLE_INTERNAL_FAILED');		
					
		$this->setRedirect('index.php?option=com_jresearch&controller=publications', $message);		
	}	
	
	/**
	 * Invoked when the user has pressed the toggle button for change a publication's 
	 * internal status.
	 *
	 */
	function toggle_internal(){
		$cid = JRequest::getVar('cid');
		$user = JFactory::getUser();
		$publication =& JResearchPublication::getById($cid[0]);

		if(!$publication->isCheckedOut($user->get('id'))){		
			$publication->internal = !$publication->internal;
		
			if($publication->store())
				$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_TOGGLE_INTERNAL_SUCCESSFULLY'));
			else{
				JError::raiseWarning(1, JText::_('JRESEARCH_TOGGLE_INTERNAL_FAILED'));
				$this->setRedirect('index.php?option=com_jresearch&controller=publications');
			}
		}else{
			JError::raiseWarning(1, JText::_('JRESEARCH_TOGGLE_INTERNAL_FAILED'));
			$this->setRedirect('index.php?option=com_jresearch&controller=publications');			
		}		
	}
	
	/**
	 * Invoked when the user has pressed the toggle button for change a publication's 
	 * internal status.
	 *
	 */
	function toggle_featured(){
		$cid = JRequest::getVar('cid');
		$user = JFactory::getUser();
		$publication =& JResearchPublication::getById($cid[0]);

		if(!$publication->isCheckedOut($user->get('id'))){		
			$publication->featured = !$publication->featured;
		
			if($publication->store())
				$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_TOGGLE_FEATURED_SUCCESSFULLY'));
			else{
				JError::raiseWarning(1, JText::_('JRESEARCH_TOGGLE_FEATURED_FAILED'));
				$this->setRedirect('index.php?option=com_jresearch&controller=publications');
			}
		}else{
			JError::raiseWarning(1, JText::_('JRESEARCH_TOGGLE_FEATURED_FAILED'));
			$this->setRedirect('index.php?option=com_jresearch&controller=publications');			
		}		
	}
	
	
	/**
	 * Invoked when the user has decided to change the type of a publication.
	 * It is only applied to existing items.
	 */
	function changeType(){	
		global $mainframe;		
		require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'jresearch.php');
		
		$db = JFactory::getDBO();
		$type = JRequest::getVar('change_type');
		JRequest::setVar('pubtype', $type, 'POST', true);
		$post = JRequest::get('post');
		$publication = JTable::getInstance('Publication', 'JResearch');
		$publication->pubtype = $type;
		$user = JFactory::getUser();
		$id = JRequest::getInt('id');
		$keepOld = JRequest::getVar('keepold', false);
		$params = JComponentHelper::getParams('com_jresearch');
		
		if(empty($id)){
			$this->setRedirect('index.php?option=com_jresearch');
			return;
		}
		
		// Get old publication
		$oldPublication = JResearchPublication::getById($id);
		
		// Get extra parameters
		$delete = JRequest::getVar('delete_url_0');
	    if($delete === 'on'){
	    	if(!empty($publication->files)){
		    	$filetoremove = JRESEARCH_COMPONENT_ADMIN.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.$publication->files;
		    	@unlink($filetoremove);
		    	$publication->files = '';
		    	$oldPublication->files = '';
	    	}
	    }else{
	    	$publication->files = $oldPublication->files;
	   }
	   
	    $publication->bind($post, array('id'));	    
	    
	    $countUrl = JRequest::getInt('count_url', 0);
	    $file = JRequest::getVar('file_url_'.$countUrl, null, 'FILES');
	    if(!empty($file['name'])){	    	
	    	$publication->files = JResearch::uploadDocument($file, $params->get('files_root_path', 'files').DS.'publications');
	    }
	    
	    $reset = JRequest::getVar('resethits', false);
	    if($reset == 'on'){
	    	$publication->hits = 0;
	    }else{
	    	$publication->hits = $oldPublication->hits;
	    }

		// Validate publication	    
		$check = $publication->check();		
		if(!$check){
			for($i=0; $i<count($publication->getErrors()); $i++)
				JError::raiseWarning(1, $publication->getError($i));

			$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$oldPublication->id.'&pubtype='.$oldPublication->pubtype);
				
		}else{
			//Time to set the authors
			$maxAuthors = JRequest::getInt('nauthorsfield');
			$k = 0;
	
			for($j=0; $j<=$maxAuthors; $j++){
				$value = JRequest::getVar("authorsfield".$j);
				if(!empty($value)){
					if(is_numeric($value)){
						// In that case, we are talking about a staff member
						$publication->setAuthor($value, $k, true); 
					}else{
						// For external authors 
						$publication->setAuthor($value, $k);
					}
					
					$k++;
				}			
			}

					//Remove previous publication if user has not stated it must be backup
			if($keepOld !== 'on'){
				if(!$oldPublication->delete($id))
					JError::raiseWarning(1, JText::sprintf('JRESEARCH_PUBLICATION_NOT_DELETED', $id));
			}else{				
				//Rename unique fields like title and citekey
				$oldSuffix = JText::_('JRESEARCH_OLD');
				$oldPublication->title .= ' ('.$oldSuffix.')';
				$oldPublication->citekey .= $oldSuffix;
		    	
				// Duplicate files if they have not been removed
				if(!empty($oldPublication->files)){
					$source = JRESEARCH_COMPONENT_ADMIN.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.$oldPublication->files;				
					$dest = JRESEARCH_COMPONENT_ADMIN.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.'old_'.$oldPublication->files;					
					if(!@copy($source, $dest))
						JError::raiseWarning(1, JText::_('JRESEARCH_FILE_NOT_BACKUP'));
					$oldPublication->files = 'old_'.$oldPublication->files;
				}
								
				if(!$oldPublication->store(true)){
					$idText = '&cid[]='.$oldPublication->id;
					JError::raiseWarning(1, JText::_('JRESEARCH_OLD_PUBLICATION_NOT_SAVED'));
					$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit'.$idText.'&pubtype='.$publication->pubtype);					
					return;
				} 
			}			
		
			// Change created by
			$publication->created_by = $user->get('id');
			
			// Now, save the record
			if($publication->store(true)){							
		    	$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype, JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED'));				
				// Trigger event
				$arguments = array('publication', $publication->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);												
			}else{
				$idText = '&cid[]='.$oldPublication->id;
				
				if($db->getErrorNum() == 1062)				
					JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_SAVED').': '.JText::_('JRESEARCH_DUPLICATED_RECORD').' '.$db->getErrorMsg());
				else
					JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_SAVED').': '.$db->getErrorMsg());

				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit'.$idText.'&pubtype='.$publication->pubtype);
			}	
		}
	}	

}
?>
