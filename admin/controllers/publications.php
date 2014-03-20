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
jresearchimport('helpers.access', 'jresearch.admin');

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
            $this->registerTask('save2new', 'save');
            $this->registerTask('save2copy', 'saveAsCopy');            
            $this->registerTask('apply', 'save');
            $this->registerTask('cancel', 'cancel');
            $this->registerTask('toggle_internal', 'toggle_internal');
            $this->registerTask('changeType', 'saveAsCopy');
            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'publications');
	}

	/**
	 * Default method, it shows the list of publications for administration. 
	 *
	 * @access public
	 */

	function display(){
		$user = JFactory::getUser();
		if($user->authorise('core.manage', 'com_jresearch')){		
			$view = $this->getView('Publications', 'html', 'JResearchAdminView');
    	    $model = $this->getModel('Publications', 'JResearchAdminModel');
        	$view->setModel($model, true);
        	$view->setLayout('default');
        	$view->display();
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	}

	/**
	* Invoked when an administrator decides to create a publication. Prints
	* a form where the user can select the type of publication to select.
	* @access public
	*/
	function add(){
    	$actions = JResearchAccessHelper::getActions();
    	if($actions->get('core.publications.create')){
			$view = $this->getView('Publication', 'html', 'JResearchAdminView');
    	    $view->setLayout('new');
        	$view->display();
    	}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));    		
    	}
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
        $user = JFactory::getUser();
        $canDoPubs = JResearchAccessHelper::getActions();        		
        
        if(!empty($cid)){
        	$publication = $pubModel->getItem();
            if(!empty($publication)){
            	$canDoPub = JResearchAccessHelper::getActions('publication', $cid[0]);
            	if($canDoPub->get('core.publications.edit') ||
     			($canDoPub->get('core.publications.edit.own') && $publication->created_by == $user->get('id'))){
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
					JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        	        $this->setRedirect('index.php?option=com_jresearch&controller=publications');
            	}
        	}else{
    	        JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
        	    $this->setRedirect('index.php?option=com_jresearch&controller=publications');
        	}
		}else{
        	if($canDoPubs->get('core.publications.create')){
	        	$app->setUserState('com_jresearch.edit.publication.data', array());            	
    	        $view->setLayout('default');
        	    $view->setModel($pubModel, true);
            	$view->display();
        	}else{
				JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));        		
        	}			
		}
	}

	/**
	* Invoked when an administrator has decided to publish a one or more items
	* @access	public
	*/ 
	function publish(){		
		JRequest::checkToken() or jexit( 'JInvalid_Token' );
				
        $model = $this->getModel('Publication', 'JResearchAdminModel');
        if(!$model->publish()){
            JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));   
	        $this->setRedirect('index.php?option=com_jresearch&controller=publications');       
        }else{
	        $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));        	
        }
    }

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
		JRequest::checkToken() or jexit( 'JInvalid_Token' );
		
        $model = $this->getModel('Publication', 'JResearchAdminModel');
        if(!$model->unpublish()){
            JError::raiseWarning(1, JText::_('JRESEARCH_UNPUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
        	$this->setRedirect('index.php?option=com_jresearch&controller=publications');            
        }else{
	        $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));    	
        }
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );	
        $model = $this->getModel('Publication', 'JResearchAdminModel');
        $n = $model->delete();
        $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::plural('JRESEARCH_N_ITEMS_SUCCESSFULLY_DELETED', $n));
        $errors = $model->getErrors();
        if(!empty($errors)){
        	JError::raiseWarning(1, explode('<br />', $errors));
        }        
	}
	
	/**
	* Invoked when an administrator has decided to import publications from a file.
	* @access	public
	*/
	function import(){
		$actions = JResearchAccessHelper::getActions();
		if($actions->get('core.publications.create')){
			$view = $this->getView('Publications', 'html', 'JResearchAdminView');
    	    $view->setLayout('import');
        	$view->display();
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}
	
	/**
	* Invoked when an administrator has decided to export a set of publications.
	* @access	public
	*/
	function export(){
		$actions = JResearchAccessHelper::getActions();		
		if($actions->get('core.manage')){
	        $view = &$this->getView('Publications', 'html', 'JResearchAdminView');
    	    $model = &$this->getModel('Publications', 'JResearchAdminModel');
        	$view->setModel($model, true);
        	$view->setLayout('export');
        	$view->display();
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}

	/**
	* Invoked when the user exports a single publication from backend list.
	*/	
	function exportSingle(){		            
		$actions = JResearchAccessHelper::getActions();
		
		if($actions->get('core.manage')){
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
		}else{
			echo JText::_('JERROR_ALERTNOAUTHOR');
		}
	}

	/**
	 * Takes the sent file and imports its contents into the database of
	 * available publications.
	 *
	 */
	function executeImport(){
		$actions = JResearchAccessHelper::getActions();
		$params = JComponentHelper::getParams('com_jresearch');
		if($actions->get('core.publications.create')){
            jresearchimport('helpers.importers.factory', 'jresearch.admin');
            $fileArray = JRequest::getVar('inputfile', null, 'FILES');
            $format = JRequest::getVar('formats');
            $uploadedFile = $fileArray['tmp_name'];
	        $researchAreas = JRequest::getVar('researchAreas', array(), '', 'array');
	        if(empty($researchAreas) || in_array('1', $researchAreas)){
				$researchAreasText = '1';
			}else{
				$researchAreasText = implode(',', $researchAreas);
			}
            
            if($fileArray == null || $uploadedFile == null){
                JError::raiseWarning(1, JText::_('JRESEARCH_NO_INPUT_FILE'));
                $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=import');
            }else{
                $importer = &JResearchPublicationImporterFactory::getInstance($format);
                $parsedPublications = $importer->parseFile($uploadedFile);
                $n = 0;
                foreach($parsedPublications as $p){
					//Auto make internal when uploading from bibtex file
                    $p->id_research_area = $researchAreasText;
                    $p->internal = $params->get('publications_default_internal_status', 1) == 1;
                    $p->published = $params->get('publications_default_published_status', 1) == 1;                    
                    if(!$p->store()){
                        JError::raiseWarning(1, JText::_('PUBLICATION_COULD_NOT_BE_SAVED').': '.$p->getError());
                    }else{
                        $n++;
                    }
                }
                $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_IMPORTED_ITEMS', count($parsedPublications), $n));
            }
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}
	
	/**
	 * Triggered when the user clicks the submit button in the export publications
	 * form.
	 *
	 */
	function executeExport(){
		$actions = JResearchAccessHelper::getActions();		
		if($actions->get('core.manage')){		
	        $view = $this->getView('Publication', 'raw', 'JResearchAdminView');
    	    $publicationsModel = $this->getModel('Publications', 'JResearchAdminModel');
        	$publicationModel = $this->getModel('Publication', 'JResearchAdminModel');        
        	$view->setModel($publicationsModel, true);
        	$view->setModel($publicationModel);        
        	$view->display();
		}
	}

	/**
	* Invoked when the user has decided to save a publication.
	*/	
	function save(){		
		JRequest::checkToken() or jexit( 'JInvalid_Token' );	
			
		jresearchimport('helpers.publications', 'jresearch.admin');	
		jresearchimport('helpers.access', 'jresearch.admin');		
					
		$model = $this->getModel('Publication', 'JResearchAdminModel');
        $app = JFactory::getApplication();
		$form =& $model->getData();
		$canDoPubs = JResearchAccessHelper::getActions();
		$canProceed = false;	
		$user = JFactory::getUser();
		$params = JComponentHelper::getParams('com_jresearch');
		
		
		// Permissions check
		if(empty($form['id'])){
			$canProceed = $canDoPubs->get('core.publications.create');
			if(!isset($form['published']))
				$form['published'] = $params->get('publications_default_published_status', 1);
			if(!isset($form['internal']))
				$form['internal'] = $params->get('publications_default_internal_status', 1);
		}else{
			$publication = JResearchPublicationsHelper::getPublication($form['id']);
			$canProceed = $canDoPubs->get('core.publications.edit') ||
     			($canDoPubs->get('core.publications.edit.own') && $publication->created_by == $user->get('id'));
		}
        
		if(!$canProceed){
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
			return;
		}

        $app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'JResearchPublication'));		                
        if ($model->save()){
            $task = JRequest::getVar('task');             	
            $publication = $model->getItem();
        	$app->triggerEvent('OnAfterSaveJResearchEntity', array($publication, 'JResearchPublication'));        
             
            if($task == 'save'){
                $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
             	$app->setUserState('com_jresearch.edit.publication.data', array());
            }elseif($task == 'apply'){
             	$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype, JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
            }elseif($task == 'save2new'){
				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=add', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
             	$app->setUserState('com_jresearch.edit.publication.data', array());								
            }
        }else{
            $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
            $type = 'error';
            $app = JFactory::getApplication();
            $app->enqueueMessage($msg, $type);                
            $view = $this->getView('Publication','html', 'JResearchAdminView');
            JRequest::setVar('pubtype', $form['pubtype']);
            $view->setLayout('default');
            $view->setModel($model, true);
            $view->display();
        }
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing publications.
	 *
	 */
	function cancel(){
		JRequest::checkToken() or jexit( 'JInvalid_Token' );
				
        $model = $this->getModel('Publication', 'JResearchAdminModel');
        $data =& $model->getData();
        if(!empty($data['id'])){
	        if(!$model->checkin($id)){
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
		JRequest::checkToken() or jexit( 'JInvalid_Token' );		

		$actions = JResearchAccessHelper::getActions();
		if($actions->get('core.publications.edit.state')){		
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
			$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::plural('JRESEARCH_N_ITEMS_TURNED_INTERNAL', $n));
	        $errors = $model->getErrors();
			if(!empty($errors))
				JError::raiseWarning(1, explode('<br />', $errors));
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	}	
	
	/**
	 * Invoked when the user has pressed the toggle button for change a publication's 
	 * internal status.
	 *
	 */
	function toggle_internal(){
		JRequest::checkToken() or jexit( 'JInvalid_Token' );		
		$actions = JResearchAccessHelper::getActions();
        
		if($actions->get('core.publications.edit.state')){
			$model = $this->getModel('Publication', 'JResearchAdminModel');
	
	        if($model->toggleInternal())
		        $this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_TOGGLE_INTERNAL_SUCCESSFULLY'));		
			else
				$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_TOGGLE_INTERNAL_UNSUCCESSFULLY'));
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}
	
	/**
	 * Invoked when the user has decided to change the type of a publication.
	 * It is only applied to existing items.
	 */
	function saveAsCopy(){	
		JRequest::checkToken() or jexit( 'JInvalid_Token' );	
			
		jresearchimport('helpers.publications', 'jresearch.admin');		
		
		$model = $this->getModel('Publication', 'JResearchAdminModel');
        $app = JFactory::getApplication();
		$form &= $model->getData();
		$canDoPubs = JResearchAccessHelper::getActions();
		$canProceed = false;	
		$user = JFactory::getUser();
		$params = JComponentHelper::getParams('com_jresearch');
		
		// Permissions check
		if(!empty($form['id'])){
			$canProceed = $canDoPubs->get('core.publications.create');
			if(!isset($form['published']))
				$form['published'] = $params->get('publications_default_published_status', 1);
			if(!isset($form['internal']))
				$form['internal'] = $params->get('publications_default_internal_status', 1);			
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
			return;
		}
		
		if(!$canProceed){
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
			return;
		}        

        $app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'JResearchPublication'));		
        if ($model->saveAsCopy()){             	
            $publication = $model->getItem();
        	$app->triggerEvent('OnAfterSaveJResearchEntity', array($publication, 'JResearchPublication'));        
            $this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype, $task == 'apply' ? JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED') : JText::_('JRESEARCH_ITEM_COPY_SUCCESSFULLY_SAVED'));
        }else{
            $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
            $type = 'error';
            $app = JFactory::getApplication();
            $app->enqueueMessage($msg, $type);                
            $view = $this->getView('Publication','html', 'JResearchAdminView');
            $view->setLayout('default');
            $view->setModel($model, true);
            $view->display();
        }
		
	}	

}
?>
