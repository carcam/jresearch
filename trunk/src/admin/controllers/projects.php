<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research projects in the backend interface.
*/

jresearchimport('joomla.application.component.controller');
jresearchimport('helpers.access', 'jresearch.admin');

/**
* Projects Backend Controller
* @package		JResearch
* @subpackage	Projects
*/
class JResearchAdminProjectsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.projects');
		
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'projects');
	}

	/**
	 * Default method, it shows the list of research projects in the administrator list.
	 * 
	 * @access public
	 */

	function display(){
		$user = JFactory::getUser();
		if($user->authorise('core.manage', 'com_jresearch')){		
			$view = $this->getView('Projects', 'html', 'JResearchAdminView');
    	    $model = $this->getModel('Projects', 'JResearchAdminModel');
        	$view->setModel($model, true);
        	$view->setLayout('default');
        	$view->display();
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	}

	/**
	* Invoked when an administrator decides to create/edit a project.
	* 
	* @access public
	*/
	function edit(){
		$app = JFactory::getApplication();
        $cid = JRequest::getVar('cid', array());
        $view = $this->getView('Project', 'html', 'JResearchAdminView');
        $projModel = $this->getModel('Project', 'JResearchAdminModel');
        $user = JFactory::getUser();
        
        if(!empty($cid)){
        	$project = $projModel->getItem();
            if(!empty($project)){
            	$canDoProj = JResearchAccessHelper::getActions('project', $cid[0]);
            	if($canDoProj->get('core.publications.edit') ||
     			($canDoProj->get('core.publications.edit.own') && $project->created_by == $user->get('id'))){
                	// Verify if it is checked out
                	if($project->isCheckedOut($user->get('id'))){
                		$this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
                	}else{
                		$project->checkout($user->get('id'));
                    	$view->setLayout('default');
                    	$view->setModel($projModel, true);
                    	$view->display();
                	}
	            }else{
					JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        	        $this->setRedirect('index.php?option=com_jresearch&controller=projects');
            	}
        	}else{
    	        JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
        	    $this->setRedirect('index.php?option=com_jresearch&controller=projects');
        	}
		}else{
			$canDoProjs = JResearchAccessHelper::getActions();        		
        	if($canDoProjs->get('core.projects.create')){
	        	$app->setUserState('com_jresearch.edit.project.data', array());            	
    	        $view->setLayout('default');
        	    $view->setModel($projModel, true);
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
				
        $model = $this->getModel('Project', 'JResearchAdminModel');
        if(!$model->publish()){
            JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));   
	        $this->setRedirect('index.php?option=com_jresearch&controller=projects');       
        }else{
	        $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));        	
        }				
	}

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
		JRequest::checkToken() or jexit( 'JInvalid_Token' );
		
        $model = $this->getModel('Project', 'JResearchAdminModel');
        if(!$model->unpublish()){
            JError::raiseWarning(1, JText::_('JRESEARCH_UNPUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
        	$this->setRedirect('index.php?option=com_jresearch&controller=projects');            
        }else{
	        $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));    	
        }				
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );	
        $model = $this->getModel('Project', 'JResearchAdminModel');
        $n = $model->delete();
        $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::sprintf('JRESEARCH_ITEM_SUCCESSFULLY_DELETED', $n));
        $errors = $model->getErrors();
        if(!empty($errors)){
        	JError::raiseWarning(1, explode('<br />', $errors));
        }        
	}

	/**
	 * Invoked when an administrator has decided to the information of a project.
	 *
	 */
	function save(){
		JRequest::checkToken() or jexit( 'JInvalid_Token' );	
			
		jresearchimport('helpers.projects', 'jresearch.admin');	
		jresearchimport('helpers.access', 'jresearch.admin');		
					
		$model = $this->getModel('Project', 'JResearchAdminModel');
        $app = JFactory::getApplication();
		$form &= $model->getData();
		$canDoPubs = JResearchAccessHelper::getActions();
		$canProceed = false;	
		$user = JFactory::getUser();
		$params = JComponentHelper::getParams('com_jresearch');
		
		
		// Permissions check
		if(empty($form['id'])){
			$canProceed = $canDoPubs->get('core.projects.create');
			if(!isset($form['published'])){
				$form['published'] = $params->get('projects_default_published_status', 1);
			}
		}else{
			$canDoProj = JResearchAccessHelper::getActions('project', $form['id']);
			$publication = JResearchProjectsHelper::getProject($form['id']);
			$canProceed = $canDoPub->get('core.project.edit') ||
     			($canDoPubs->get('core.projects.edit.own') && $publication->created_by == $user->get('id'));
		}
        
		if(!$canProceed){
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
			return;
		}
		
        $app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'project'));		                
        if ($model->save()){
            $task = JRequest::getVar('task');             	
            $project = $model->getItem();
        	$app->triggerEvent('OnAfterSaveJResearchEntity', array($project, 'project'));        
             
            if($task == 'save'){
                $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
             	$app->setUserState('com_jresearch.edit.project.data', array());
            }elseif($task == 'apply'){
             	$this->setRedirect('index.php?option=com_jresearch&controller=projects&task=edit&cid[]='.$project->id, JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
            }
        }else{
            $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
            $type = 'error';
            $app = JFactory::getApplication();
            $app->enqueueMessage($msg, $type);                
            $view = $this->getView('Project','html', 'JResearchAdminView');
            $view->setLayout('default');
            $view->setModel($model, true);
            $view->display();
        }
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing projects.
	 *
	 */
	function cancel(){
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Project', 'JResearchModel');		
		
		if($id != null){
			$project = $model->getItem($id);
			if(!$project->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=projects');
	}
	
}
?>
