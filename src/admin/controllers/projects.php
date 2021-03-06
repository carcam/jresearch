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
defined('_JEXEC') or die( 'Restricted access' );

jresearchimport('joomla.application.component.controller');

/**
* Projects Backend Controller
* @package		JResearch
* @subpackage	Projects
*/
class JResearchAdminProjectsController extends JControllerLegacy
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
        $this->registerTask('save2new', 'save');
        $this->registerTask('save2copy', 'save');
        $this->registerTask('apply', 'save');
        $this->registerTask('cancel', 'cancel');
        $this->registerTask('saveOrderAjax', 'saveOrderAjax');                
        $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'projects');
    }

    /**
     * Default method, it shows the list of research projects in the administrator list.
     * 
     * @access public
     */

    function display($cachable = false, $urlparams = array()){
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
        $jinput = JFactory::getApplication()->input;
        $cid = $jinput->get('cid', array(), 'array');
        $view = $this->getView('Project', 'html', 'JResearchAdminView');
        $projModel = $this->getModel('Project', 'JResearchAdminModel');
        $user = JFactory::getUser();
        $canDoProjs = JResearchAccessHelper::getActions();        

        if(!empty($cid)){
            $project = $projModel->getItem();
            if(!empty($project)){
                if($canDoProjs->get('core.projects.edit') ||
                    ($canDoProjs->get('core.projects.edit.own') && $project->created_by == $user->get('id'))){
                    // Verify if it is checked out
                    if($project->isCheckedOut($user->get('id'))){
                            $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
                    }else{
                        $project->checkout($user->get('id'));
                        $view->setLayout('default');
                        $view->setModel($projModel, true);
                        $view->display();
                    }
                } else {
                    JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                    $this->setRedirect('index.php?option=com_jresearch&controller=projects');
                }
            }else{
                JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                $this->setRedirect('index.php?option=com_jresearch&controller=projects');
            }
        } else {        		
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

        $canDoProjs = JResearchAccessHelper::getActions();
        if($canDoProjs->get('core.projects.edit.state')){  		
            $model = $this->getModel('Project', 'JResearchAdminModel');
            if(!$model->publish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));   
                $this->setRedirect('index.php?option=com_jresearch&controller=projects');       
            }else{
                $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));        	
            }
        }else{
            JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
        }
    }

    /**
    * Invoked when an administrator has decided to unpublish one or more items
    * @access	public
    */ 
    function unpublish(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );

        $canDoProjs = JResearchAccessHelper::getActions();
        if($canDoProjs->get('core.projects.edit.state')){
            $model = $this->getModel('Project', 'JResearchAdminModel');
            if(!$model->unpublish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_UNPUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
                $this->setRedirect('index.php?option=com_jresearch&controller=projects');            
            }else{
                $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));    	
            }
        }else{
            JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
        }				
    }

    /**
    * Invoked when an administrator has decided to remove one or more items
    * @access	public
    */ 
    function remove(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );

        $canDoProjs = JResearchAccessHelper::getActions();
        if($canDoProjs->get('core.projects.delete')){
            $model = $this->getModel('Project', 'JResearchAdminModel');
            $n = $model->delete();
            $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::plural('JRESEARCH_N_ITEMS_SUCCESSFULLY_DELETED', $n));
            $errors = $model->getErrors();
            if(!empty($errors)){
                JError::raiseWarning(1, explode('<br />', $errors));
            }        
        }else{        
            JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }
    }

    /**
     * Invoked when an administrator has decided to the information of a project.
     *
     */
    function save(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );				
        jresearchimport('helpers.projects', 'jresearch.admin');	

        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');					
        $model = $this->getModel('Project', 'JResearchAdminModel');
        $app = JFactory::getApplication();
        $form =& $model->getData();
        $canDoProjs = JResearchAccessHelper::getActions();
        $canProceed = false;	
        $user = JFactory::getUser();
        $params = JComponentHelper::getParams('com_jresearch');

        // Permissions check
        if(empty($form['id'])){
            $canProceed = $canDoProjs->get('core.projects.create');
            if(!isset($form['published'])){
                $form['published'] = $params->get('projects_default_published_status', 1);
            }
        }else{
            if ($task != 'save2copy') {
                $project = JResearchProjectsHelper::getProject($form['id']);
                $canProceed = $canDoProjs->get('core.projects.edit') ||
                ($canDoProjs->get('core.projects.edit.own') && $project->created_by == $user->get('id'));
            } else {
                unset($form['id']);
                $canProceed = $canDoProjs->get('core.projects.create');
            }
        }

        if(!$canProceed){
            JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
            return;
        }

        $app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'JResearchProject'));		                
        if ($model->save()){             	
            $project = $model->getItem();
            $app->triggerEvent('OnAfterSaveJResearchEntity', array($project, 'JResearchProject'));        

            if($task == 'save'){
                $this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
                $app->setUserState('com_jresearch.edit.project.data', array());
            }elseif($task == 'apply' || $task == 'save2copy'){
                $this->setRedirect('index.php?option=com_jresearch&controller=projects&task=edit&cid[]='.$project->id, $task == 'apply' ? JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED') : JText::_('JRESEARCH_ITEM_COPY_SUCCESSFULLY_SAVED'));
            }elseif($task='save2new'){
                $this->setRedirect('index.php?option=com_jresearch&controller=projects&task=add', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
                $app->setUserState('com_jresearch.edit.project.data', array());
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
        $model = &$this->getModel('Project', 'JResearchAdminModel');		

        if($id != null){
            $project = $model->getItem($id);
            if(!$project->checkin()){
                JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
            }
        }

        $this->setRedirect('index.php?option=com_jresearch&controller=projects');
    }
    
    function saveOrderAjax() {
        $canDoProjects = JResearchAccessHelper::getActions();
        if ($canDoProjects->get('core.projects.edit')) {
            $model = $this->getModel('Project', 'JResearchAdminModel');
            $jinput = JFactory::getApplication()->input;
            $pks = $jinput->get('cid', array(), 'ARRAY');
            $order = $jinput->get('order', array(), 'ARRAY');
            JArrayHelper::toInteger($pks);
            JArrayHelper::toInteger($order);
            // Save the ordering
            $return = $model->saveorder($pks, $order);

            if ($return) {
                echo "1";
            }
        } else {
            echo "0";
        }
        // Close the application
        JFactory::getApplication()->close();
    }
}
?>