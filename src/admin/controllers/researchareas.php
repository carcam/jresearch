<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	researchareas
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research areas in the backend interface.
*/

defined('_JEXEC') or die( 'Restricted access' );

jresearchimport('joomla.application.component.controller');
jresearchimport('helpers.researchareas', 'jresearch.admin');

/**
 * Research Areas Backend Controller
 * @package		JResearch
 * @subpackage	researchareas
 */
class JResearchAdminResearchareasController extends JControllerLegacy
{
    /**
     * Initialize the controller by registering the tasks to methods.
     * @return void
     */
    function __construct(){
        parent::__construct();

        $lang = JFactory::getLanguage();
        $lang->load('com_jresearch.researchareas');

        $this->registerTask('add', 'edit');
        $this->registerTask('publish', 'publish');
        $this->registerTask('unpublish', 'unpublish');
        $this->registerTask('add', 'edit');
        $this->registerTask('edit', 'edit');
        $this->registerTask('remove', 'remove');
        $this->registerTask('apply', 'save');
        $this->registerTask('save', 'save');
        $this->registerTask('save2new', 'save');
        $this->registerTask('save2copy', 'save');				
        $this->registerTask('cancel', 'cancel');
        $this->registerTask('saveOrderAjax', 'saveOrderAjax');
        $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'researchareas');		
    }

    /**
    * Invoked when saving the information about a research area.
    */	
    function save(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );		
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');
        $model = $this->getModel('Researcharea', 'JResearchAdminModel');
        $app = JFactory::getApplication();
        $form =& $model->getData();        
        $app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'JResearchResearcharea'));                
        $canDoAreas = JResearchAccessHelper::getActions();
        $canProceed = false;	

        // Permissions check
        if(empty($form['id'])){
            $canProceed = $canDoAreas->get('core.researchareas.create');
        }else{
            if ($task != 'save2copy') {
                $area = JResearchResearchareasHelper::getResearchArea($form['id']);
                $canProceed = $canDoAreas->get('core.researchareas.edit') ||
                ($canDoAreas->get('core.researchareas.edit.own') && $area->createdBy == $user->get('id'));
            } else {
                unset($form['id']);
                $canProceed = $canDoAreas->get('core.researchareas.create');
            }
        }

        if(!$canProceed){
            JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
            return;
        }		

        if ($model->save($form)){
            $area = $model->getItem();
            $app->triggerEvent('OnAfterSaveJResearchEntity', array($area, 'JResearchResearcharea'));

            if($task == 'save'){
                $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
                $app->setUserState('com_jresearch.edit.researcharea.data', array());                    
            }elseif($task == 'apply' || $task == 'save2copy') {
                $this->setRedirect('index.php?option=com_jresearch&controller=researchareas&task=edit&cid[]='.$area->id, $task == 'apply' ? JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED') : JText::_('JRESEARCH_ITEM_COPY_SUCCESSFULLY_SAVED'));
            }elseif($task == 'save2new') {
                $this->setRedirect('index.php?option=com_jresearch&controller=researchareas&task=add', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
                $app->setUserState('com_jresearch.edit.researcharea.data', array());                
            }             	
        }else{
            $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
            $type = 'error';
            $app = JFactory::getApplication();
            $app->enqueueMessage($msg, $type);
            $view = $this->getView('Researcharea','html', 'JResearchAdminView');
            $view->setModel($model, true);
            $view->setLayout('default');
            $view->display();
        }

        return true;
    }

    /**
     * Default method, it shows the list of research areas in an administration list.
     *
     * @access public
     */
    function display($cachable = false, $urlparams = Array()){
        //Check permissions
        $user = JFactory::getUser();
        if($user->authorise('core.manage', 'com_jresearch')){
            $view = $this->getView('researchareas', 'html', 'JResearchAdminView');
            $model = $this->getModel('researchareas', 'JResearchAdminModel');
            $view->setModel($model, true);
            $view->setLayout('default');
            $view->display();
        }else{
            JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }
    }

    /**
    * Invoked when the user has published a set of research areas items.
    */	
    function publish(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );
        $canDoAreas = JResearchAccessHelper::getActions();

        if($canDoAreas->get('core.researchareas.edit.state')){		
            $model = $this->getModel('Researcharea', 'JResearchAdminModel');
            if(!$model->publish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
                $this->setRedirect('index.php?option=com_jresearch&controller=researchareas');        	
            }else{
                $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
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
        $canDoAreas = JResearchAccessHelper::getActions();

        if($canDoAreas->get('core.researchareas.edit.state')){		
            $model = $this->getModel('Researcharea', 'JResearchAdminModel');
            if(!$model->unpublish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
                $this->setRedirect('index.php?option=com_jresearch&controller=researchareas');
            }else{        
                $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::_('JRESEARCH_AREAS_UNPUBLISHED_SUCCESSFULLY'));
            }
        }else{
            JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
        }
    }

    /**
    * Invoked when an administrator has decided to remove one or more items.
    * @access	public
    */ 
    function remove(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );		
        $canDoAreas = JResearchAccessHelper::getActions();	

        if($canDoAreas->get('core.researchareas.delete')){
            $model = $this->getModel('Researcharea', 'JResearchAdminModel');
            $n = $model->delete();
            $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::plural('JRESEARCH_N_AREA_SUCCESSFULLY_DELETED', $n));
            $errors = $model->getErrors();
            if(!empty($errors)){
                JError::raiseWarning(1, explode('<br />', $errors));
            }
        }else{
            JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
        }
    }


    /**
    * Invoked when the administrator has decided to edit/create a research area.
    *
    * @access public
    */
    function edit(){
        $jinput = JFactory::getApplication()->input;        
        $cid = $jinput->get('cid', 0, 'INT');
        $view = $this->getView('ResearchArea', 'html', 'JResearchAdminView');
        $model = $this->getModel('ResearchArea', 'JResearchAdminModel');
        $user = JFactory::getUser();	
        $canDoAreas = JResearchAccessHelper::getActions();			

        if(!empty($cid)){
            $area = $model->getItem();
            if(!empty($area)){
                if($canDoAreas->get('core.researchareas.edit') ||
                    ($canDoAreas->get('core.researchareas.edit.own') && $area->createdBy == $user->get('id'))){  	
                    // Verify if it is checked out
                        if($area->isCheckedOut($user->get('id'))){
                            $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
                        }else{
                            $area->checkout($user->get('id'));
                            $view->setLayout('default');
                            $view->setModel($model, true);
                            $view->display();
                        }
                }else{
                    JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));     				
                }
            }else{
                JError::raiseError(404, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                $this->setRedirect('index.php?option=com_jresearch&controller=researchareas');
            }
        }else{        		
            if($canDoAreas->get('core.researchareas.create')){
                $session = JFactory::getSession();
                $session->set('citedRecords', array(), 'jresearch');
                $view->setLayout('default');
                $view->setModel($model, true);
                $view->display();
            }
        }
    }

    /**
     * Invoked when pressing cancel button in the form for editing research areas.
     *
     */
    function cancel(){
        if(!JRequest::checkToken()){
            $this->setRedirect('index.php?option=com_jresearch');
            return;
        }

        $model = $this->getModel('Researcharea', 'JResearchAdminModel');
        $app = JFactory::getApplication();
        if(!$model->checkin()){            	
            JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
        }

        $app->setUserState('com_jresearch.edit.researcharea.data', array());
        $this->setRedirect('index.php?option=com_jresearch&controller=researchareas');
    }
    
    /**
     * It updates the column ordering for the records involved in a change order
     * operation (achieved by dragging rows to their new positions)
     */
    function saveOrderAjax() {
        $canDoAreas = JResearchAccessHelper::getActions();
        if ($canDoAreas->get('core.researchareas.edit')) {
            $model = $this->getModel('ResearchArea', 'JResearchAdminModel');
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
