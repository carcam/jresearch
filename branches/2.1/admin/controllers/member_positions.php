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
		$this->registerTask('save2new', 'save');
		$this->registerTask('save2copy', 'save');
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
	function display(){
		$user = JFactory::getUser();		
		if($user->authorise('core.manage', 'com_jresearch')){		
			$view = $this->getView('Member_positions', 'html', 'JResearchAdminView');
        	$model = $this->getModel('Member_positions', 'JResearchAdminModel');
        	$view->setModel($model, true);
        	$view->display();
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}

	function edit(){
		$user = JFactory::getUser();		
		if($user->authorise('core.manage', 'com_jresearch')){				
	        $cid = JRequest::getVar('cid');		
			$view = $this->getView('Member_position', 'html', 'JResearchAdminView');
			$model = $this->getModel('Member_position', 'JResearchAdminModel');

			if(!empty($cid)){
				$position = $model->getItem();
			
				if(!empty($position)){
					//Check if it is checked out
					if($position->isCheckedOut($user->get("id"))){
						$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
					}else{
						$position->checkout($user->get("id"));
						$view->setModel($model, true);
						$view->display();					
					}
				}else{
					JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
					$this->setRedirect('index.php?option=com_jresearch&controller=member_positions');
				}			
			}else{
				$view->setModel($model, true);
				$view->display();
			}
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}

	function publish(){		
		JRequest::checkToken() or jexit( 'JInvalid_Token' );		
		$user = JFactory::getUser();
		if($user->authorise('core.manage', 'com_jresearch')){		       	
			$model = $this->getModel('Member_position', 'JResearchAdminModel');
    	   	if(!$model->publish()){
       			JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
       		}
		
			$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}

	function unpublish(){
		JRequest::checkToken() or jexit( 'JInvalid_Token' );		
		$user = JFactory::getUser();
		if($user->authorise('core.manage', 'com_jresearch')){
	        $model = $this->getModel('Member_position', 'JResearchAdminModel');
    	    if(!$model->unpublish()){
        		JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
        	}
        
        	$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}

	function remove(){
		JRequest::checkToken() or jexit( 'JInvalid_Token' );
		
		$user = JFactory::getUser();
		if($user->authorise('core.manage', 'com_jresearch')){
	        $model = $this->getModel('Member_position', 'JResearchAdminModel');
    	    $n = $model->delete();
        	$this->setRedirect('index.php?option=com_jresearch&controller=member_positions',JText::plural('JRESEARCH_N_ITEMS_SUCCESSFULLY_DELETED', $n));
        	$errors = $model->getErrors();
        	if(!empty($errors)){
        		JError::raiseWarning(1, explode('<br />', $errors));
        	}        	
		}
		else
		{
			$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}
		
	}

	function save(){
		JRequest::checkToken() or jexit( 'JInvalid_Token' );
        $task = JRequest::getVar('task');
		$user = JFactory::getUser();
		if(!$user->authorise('core.manage', 'com_jresearch')){
            $this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JERROR_ALERTNOAUTHOR'));			
			return;
		}
		
		$model = $this->getModel('Member_position', 'JResearchAdminModel');
        $app = JFactory::getApplication();
        $form =& $model->getData();
        $app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'JResearchMember_position'));
	    if ($task == 'save2copy') {
        	unset($form['id']);
        }        
        
        if ($model->save()){
            $position = $model->getItem();
        	$app->triggerEvent('OnAfterSaveJResearchEntity', array($position, 'JResearchMember_position'));                            
            if($task == 'save'){
            	$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
                $app->setUserState('com_jresearch.edit.member_position.data', array());
            }elseif($task == 'apply' || $task == 'save2copy'){
            	$this->setRedirect('index.php?option=com_jresearch&controller=member_positions&task=edit&cid[]='.$position->id, $task == 'apply' ? JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED') : JText::_('JRESEARCH_ITEM_COPY_SUCCESSFULLY_SAVED'));
           	}elseif($task == 'save2new'){
            	$this->setRedirect('index.php?option=com_jresearch&controller=member_positions&task=add', JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));           		
                $app->setUserState('com_jresearch.edit.member_position.data', array());           		
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

	/**
	* Save the item(s) to the menu selected
	*/
	function orderup()
	{
         // Check for request forgeries
         JRequest::checkToken() or jexit( 'JInvalid_Token' );

         $user = JFactory::getUser();
         if($user->authorise('core.manage', 'com_jresearch')){
	         $cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
	         JArrayHelper::toInteger($cid);
	
	         if (isset($cid[0]) && $cid[0])
	         {
	         	$id = $cid[0];
	         }
	         else
	         {
	            $this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_NO_ITEMS_SELECTED') );
	            return false;
	         }
	
	         $model = $this->getModel('Member_positions', 'JResearchAdminModel');
	
	         if ($model->orderItem($id, -1))
	         {
	            $msg = JText::_( 'JRESEARCH_ITEM_MOVED_UP' );
	         }
	         else
	         {
	         	$msg = $model->getError();
	         }
	
	         $this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', $msg );
         }else{
			 JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));         	
         }
	}

	/**
	* Save the item(s) to the menu selected
	*/
	function orderdown()
	{
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'JInvalid_Token' );

        $user = JFactory::getUser();
        if($user->authorise('core.manage', 'com_jresearch')){        
	        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	        JArrayHelper::toInteger($cid);
	
	        if (isset($cid[0]) && $cid[0])
	        {
	            $id = $cid[0];
	        }
	        else
	        {
	            $this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', JText::_('JRESEARCH_NO_ITEMS_SELECTED') );
	        	return false;
	        }
	
	        $model = $this->getModel('Member_positions', 'JResearchAdminModel');
	        if ($model->orderItem($id, 1)){
	            $msg = JText::_( 'JRESEARCH_ITEM_MOVED_DOWN' );
	        }else{
	            $msg = $model->getError();
	        }
	        $this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', $msg );
        }else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }
	}

	/**
	* Save the item(s) to the menu selected
	*/
	function saveorder()
	{
         // Check for request forgeries
         JRequest::checkToken() or jexit( 'JInvalid_Token' );
		 $user = JFactory::getUser();
         if($user->authorise('core.manage', 'com_jresearch')){         
	         $cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
	         JArrayHelper::toInteger($cid);
	         $model = $this->getModel('Member_positions', 'JResearchAdminModel');
	         if ($model->setOrder($cid)){
	         	$msg = JText::_( 'JRESEARCH_NEW_ORDERING_SAVED' );
	         }else{
	            $msg = $model->getError();
	         }
	         $this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', $msg );
         }else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));         	
         }
	}
}
?>