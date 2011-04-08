<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Member Position
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of cooperations in the backend interface.
*/
jimport('joomla.application.component.controller');

/**
* JResearch Member positions Backend Controller
*
* @package		JResearch
* @subpackage	Member Position
*/
class JResearchAdminMember_positionsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
		parent::__construct();

		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.member_positions');
		
		$this->registerDefaultTask('display');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');

		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'member_positions');
		$this->addViewPath(JRESEARCH_COMPONENT_ADMIN.DS.'views'.DS.'member_positions');
		$this->addViewPath(JRESEARCH_COMPONENT_ADMIN.DS.'views'.DS.'member_position');		
	}

	/**
	 * Default method, it shows the control panel for JResearch component.
	 *
	 * @access public
	 */
	function display()
	{
		$view = $this->getView('Member_positions', 'html', 'JResearchAdminView');
        $model = $this->getModel('Member_positions', 'JResearchAdminModel');
        $view->setModel($model, true);
        $view->display();		
	}

	function edit()
	{
        $cid = JRequest::getVar('cid');		
		$view = $this->getView('Member_position', 'html', 'JResearchAdminView');
		$model = $this->getModel('Member_position', 'JResearchAdminModel');

		if(!empty($cid))
		{
			$position = $model->getItem();
			
			if(!empty($position)){
				$user = &JFactory::getUser();
				//Check if it is checked out
				if($position->isCheckedOut($user->get("id")))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
				}
				else
				{
					$position->checkout($user->get("id"));
					$view->setModel($model, true);
					$view->display();					
				}
			}
			else
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=member_positions');
			}			
		}
		else
		{
			$view->setModel($model, true);
			$view->display();
		}
	}

	function publish()
	{		
       	$model = $this->getModel('Member_position', 'JResearchAdminModel');
       	if(!$model->publish()){
       		JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
       	}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
	}

	function unpublish()
	{
        $model = $this->getModel('Member_position', 'JResearchAdminModel');
        if(!$model->unpublish()){
        	JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
        }
        
        $this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));
	}

	function remove()
	{
		if(!JRequest::checkToken()){
        	$this->setRedirect('index.php?option=com_jresearch');
            return;
        }
	
        $model = $this->getModel('Member_position', 'JResearchAdminModel');
        $n = $model->delete();
        $this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::sprintf('JRESEARCH_ITEM_SUCCESSFULLY_DELETED', $n));
		
	}

	function save()
	{
		if(!JRequest::checkToken()){
        	$this->setRedirect('index.php?option=com_jresearch');
            return;
        }
		
		$model = $this->getModel('Member_position', 'JResearchAdminModel');
        $app = JFactory::getApplication();

        if ($model->save()){
        	$task = JRequest::getVar('task');
            $position = $model->getItem();
            if($task == 'save'){
            	$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
                $app->setUserState('com_jresearch.edit.member_position.data', array());
            }elseif($task == 'apply'){
            	$this->setRedirect('index.php?option=com_jresearch&controller=member_positions&task=edit&cid[]='.$position->id, JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
           	}
        }else{
        	$msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
            $type = 'error';
            $app = JFactory::getApplication();
            $app->enqueueMessage($msg, $type);
            $view = $this->getView('Member_position','html', 'JResearchAdminView');
            $view->setModel($model, true);
			$view->setLayout('default');
            $view->display();
         }
            
         return true;		
	}

	function cancel()
	{
		$model = $this->getModel('Member_position', 'JResearchAdminModel');
        $app = JFactory::getApplication();
        if(!$model->checkin()){            	
        	JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
        }
            
        $app->setUserState('com_jresearch.edit.member_position.data', array());
        $this->setRedirect('index.php?option=com_jresearch&controller=member_positions');		
	}
}
?>