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

jresearchimport('joomla.application.component.controller');

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
            $this->registerTask('delete', 'remove');            
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
            $this->registerTask('changeType', 'changeType');
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'publications');
	}

	/**
	 * Default method, it shows the list of publications for administration. 
	 *
	 * @access public
	 */

	function display($cachable = false){
            $view = $this->getView('Publications', 'html', 'JResearchAdminView');
            $model = $this->getModel('Publications', 'JResearchAdminModel');
            $view->setModel($model, true);
            $view->setLayout('default');
            $view->display();
	}

	/**
	* Invoked when an administrator decides to create a publication. Prints
	* a form where the user can select the type of publication to select.
	* @access public
	*/
	function add(){
            $view = $this->getView('Publication', 'html', 'JResearchAdminView');
            $view->setLayout('new');
            $view->display();
	}

	/**
	* Invoked when the user wants to edit an existing publication or has selected
	* the type for a new one.
	*/	
	function edit(){
			$app = JFactory::getApplication();
            $cid = JRequest::getVar('cid', array());
            $view = $this->getView('Publication', 'html', 'JResearchAdminView');
            $pubModel = $this->getModel('Publication', 'JResearchAdminModel');

            if(!empty($cid)){
                    $publication = $pubModel->getItem();
                    if(!empty($publication)){
                            $user = JFactory::getUser();
                            // Verify if it is checked out
                            if($publication->isCheckedOut($user->get('id'))){
                                $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
                            }else{
                                $publication->checkout($user->get('id'));
                                $view->setLayout('default');
                                $view->setModel($pubModel, true);
                                $view->display();
                            }
                    }else{
                        JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                        $this->setRedirect('index.php?option=com_jresearch&controller=publications');
                    }
            }else{
                $app->setUserState('com_jresearch.edit.publication.data', array());            	
                $view->setLayout('default');
                $view->setModel($pubModel, true);
                $view->display();
            }
	}

	/**
	* Invoked when an administrator has decided to publish a one or more items
	* @access	public
	*/ 
	function publish(){		
			if(!JRequest::checkToken()){
                $this->setRedirect('index.php?option=com_jresearch');
                return;
            }
		
            $model = $this->getModel('Publication', 'JResearchAdminModel');
            if(!$model->publish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
            }
            $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
    }

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
			if(!JRequest::checkToken()){
                $this->setRedirect('index.php?option=com_jresearch');
                return;
            }
		
            $model = $this->getModel('Publication', 'JResearchAdminModel');
            if(!$model->unpublish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_UNPUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
            }
            $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));
		
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
			if(!JRequest::checkToken()){
                $this->setRedirect('index.php?option=com_jresearch');
                return;
            }
	
            $model = $this->getModel('Publication', 'JResearchAdminModel');
            $n = $model->delete();
            $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_ITEM_SUCCESSFULLY_DELETED', $n));
		
	}
	
	/**
	* Invoked when an administrator has decided to import publications from a file.
	* @access	public
	*/
	function import(){
            $view = $this->getView('Publications', 'html', 'JResearchAdminView');
            $view->setLayout('import');
            $view->display();
	}
	
	/**
	* Invoked when an administrator has decided to export a set of publications.
	* @access	public
	*/
	function export(){
            $view = &$this->getView('Publications', 'html', 'JResearchAdminView');
            $model = &$this->getModel('Publications', 'JResearchAdminModel');
            $view->setModel($model, true);
            $view->setLayout('export');
            $view->display();
	}

	/**
	* Invoked when the user exports a single publication from backend list.
	*/	
	function exportSingle(){		            
            jresearchimport('helpers.exporters.factory', 'jresearch.admin');
            $document = JFactory::getDocument();

            $id = JRequest::getInt('id');
            $format = JRequest::getVar('format');
            $model = $this->getModel('Publication', 'JResearchAdminModel');
            $publication = $model->getItem();

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
            jresearchimport('helpers.importers.factory', 'jresearch.admin');
            $fileArray = JRequest::getVar('inputfile', null, 'FILES');
            $format = JRequest::getVar('formats');
            $idResearchArea = JRequest::getVar('researchAreas');
            $uploadedFile = $fileArray['tmp_name'];

            if($fileArray == null || $uploadedFile == null){
                JError::raiseWarning(1, JText::_('JRESEARCH_NO_INPUT_FILE'));
                $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=import');
            }else{
                $importer = &JResearchPublicationImporterFactory::getInstance($format);
                $parsedPublications = $importer->parseFile($uploadedFile);
                $n = 0;
                foreach($parsedPublications as $p){
                    $p->id_research_area = array($idResearchArea);
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
        $view = $this->getView('Publication', 'raw', 'JResearchAdminView');
        $publicationsModel = $this->getModel('Publications', 'JResearchAdminModel');
        $publicationModel = $this->getModel('Publication', 'JResearchAdminModel');        
        $view->setModel($publicationsModel, true);
        $view->setModel($publicationModel);        
        $view->display();		
	}

	/**
	* Invoked when the user has decided to save a publication.
	*/	
	function save(){		
		if(!JRequest::checkToken()){
           $this->setRedirect('index.php?option=com_jresearch');
           return;
        }
		
		$model = $this->getModel('Publication', 'JResearchAdminModel');
        $app = JFactory::getApplication();
        if ($model->save()){
             	$task = JRequest::getVar('task');             	
                $publication = $model->getItem();
                if($task == 'save'){
                    $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
                    $app->setUserState('com_jresearch.edit.publication.data', array());
                }elseif($task == 'apply'){
                    $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype, JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
             	}
             }else{
                $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
                $type = 'error';
                $app = JFactory::getApplication();
                $app->enqueueMessage($msg, $type);
                $view = $this->getView('Publication','html', 'JResearchAdminView');
                $view->setModel($model, true);
				$view->setLayout('default');
                $view->display();
            }
            
            return true;
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing publications.
	 *
	 */
	function cancel(){
		if(!JRequest::checkToken()){
           $this->setRedirect('index.php?option=com_jresearch');
           return;
        }
		
        $model = $this->getModel('Publication', 'JResearchAdminModel');
        if(!$model->checkin()){
            JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
        }
        
        $this->setRedirect('index.php?option=com_jresearch&controller=publications');		
	}
	
	
	/**
	 * Invoked when the user has pressed any of the buttons for changing internal 
	 * flag for publications. 
	 *
	 */
	function changeInternalStatus(){
		if(!JRequest::checkToken()){
           $this->setRedirect('index.php?option=com_jresearch');
           return;
        }
	
        $model = $this->getModel('Publication', 'JResearchAdminModel');
        $task = JRequest::getVar('task');
        $value = false;
        if($task == 'makeinternal'){
        	$key = 'JRESEARCH_NITEMS_TURNED_INTERNAL'; 
        	$value = true;
        }else{
			$key = 'JRESEARCH_NITEMS_TURNED_NON_INTERNAL';        	
        }
        
        $n = $model->setInternalValue($value);
		$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_NITEMS_TURNED_INTERNAL', $n));
	
	}	
	
	/**
	 * Invoked when the user has pressed the toggle button for change a publication's 
	 * internal status.
	 *
	 */
	function toggle_internal(){
		if(!JRequest::checkToken()){
           $this->setRedirect('index.php?option=com_jresearch');
           return;
        }
	
        $model = $this->getModel('Publication', 'JResearchAdminModel');

        if($model->toggleInternal())
	        $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_TOGGLE_INTERNAL_SUCCESSFULLY'));		
		else
			$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_TOGGLE_INTERNAL_UNSUCCESSFULLY'));	        
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
