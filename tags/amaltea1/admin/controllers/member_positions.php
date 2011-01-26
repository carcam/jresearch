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

            $this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'member_position');
            $this->addViewPath(JRESEARCH_COMPONENT_ADMIN.DS.'views'.DS.'member_positions');
	}

	/**
	 * Default method, it shows the control panel for JResearch component.
	 *
	 * @access public
	 */
	function display()
	{
            JResearchUnlockerHelper::unlockItems('member_position');
            $view = &$this->getView('Member_positionList', 'html', 'JResearchAdminView');
            $model = &$this->getModel('Member_positionList', 'JResearchModel');

            $view->setModel($model,true);
            $view->display();
	}

	function edit()
	{
		$cid = JRequest::getVar('cid', array());

		$view = &$this->getView('Member_position', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Member_position', 'JResearchModel');

		if(!empty($cid))
		{
			$position = $model->getItem($cid[0]);
			
			if(!empty($position)){
				$user = &JFactory::getUser();
				//Check if it is checked out
				if($position->isCheckedOut($user->get("id")))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('You cannot edit this item. Another user has locked it.'));
				}
				else
				{
					$position->checkout($user->get("id"));
					$view->setModel($model,true);
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
			$view->setModel($model,true);
			$view->display();
		}
	}

	function publish()
	{
		// Array of ids
		$cid = JRequest::getVar('cid');
		$memp = JTable::getInstance('Member_position', 'JResearch');
		
		if($memp->publish($cid, 1, $user->get('id')))
		    $message = JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY');
        else
         	$message = JText::_('JRESEARCH_ITEMS_PUBLISHED_UNSUCCESSFULLY');	
		
		$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', $message);
	}

	function unpublish()
	{
		// Array of ids
		$cid = JRequest::getVar('cid');
		$memp = JTable::getInstance('Member_position', 'JResearch');
		
		if($memp->publish($cid, 1, $user->get('id')))
		    $message = JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY');
        else
         	$message = JText::_('JRESEARCH_ITEMS_UNPUBLISHED_UNSUCCESSFULLY');	
		
		$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', $message);
		
	}

	function remove()
	{
		$cid = JRequest::getVar('cid');
		$n = 0;

		$position = JTable::getInstance('Member_position', 'JResearch');

		foreach($cid as $id)
		{
			if(!$position->delete($id))
			{
				JError::raiseWarning(1, JText::sprintf('Position with id %d could not be deleted', $id));
			}
			else
			{
				$n++;
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::sprintf('%d successfully deleted.', $n));
	}

	function save()
	{
            global $mainframe;
	    if(!JRequest::checkToken())
            {
                $this->setRedirect('index.php?option=com_jresearch');
                return;
            }

            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'jresearch.php');

            $db =& JFactory::getDBO();

            $position = JTable::getInstance('Member_position', 'JResearch');

            // Bind request variables
            $post = JRequest::get('post');

            $position->bind($post);

            // Validate and save
            if($position->check())
            {
                    $mainframe->triggerEvent('onBeforeSaveJResearchEntity', array('position', &$position));
                    if($position->store())
                    {
                            $task = JRequest::getVar('task');

                            //Specific redirect for specific task
                            if($task == 'save')
                                    $this->setRedirect('index.php?option=com_jresearch&controller=member_positions', JText::_('The position was successfully saved.'));
                            elseif($task == 'apply')
                                    $this->setRedirect('index.php?option=com_jresearch&controller=member_positions&task=edit&cid[]='.$position->id, JText::_('The position was successfully saved.'));

                            // Trigger event
                            $arguments = array('position', $position->id);
                            $mainframe->triggerEvent('onAfterSaveMember_positionEntity', $arguments);

                    }
                    else
                    {
                            JError::raiseWarning(1, $position->getError());
                            $this->setRedirect('index.php?option=com_jresearch&controller=member_positions&task=edit&cid[]='.$position->id, JText::_('JRESEARCH_SAVE_FAILED'));
                    }
            }
            else
            {
                    JError::raiseWarning(1, $position->getError());
                    $this->setRedirect('index.php?option=com_jresearch&controller=member_positions&task=edit&cid[]='.$position->id);
            }

            //Reordering ordering of other cooperations
            $position->reorder();

            //Unlock record
            $user =& JFactory::getUser();
            if(!$position->isCheckedOut($user->get('id')))
            {
                    if(!$position->checkin())
                            JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
            }
	}

	function cancel()
	{
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Member_position', 'JResearchModel');

		if($id != null)
		{
			$position = $model->getItem($id);

			if(!$position->checkin())
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=member_positions');
	}
	
	/**
	* Save the item(s) to the menu selected
	*/
	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0])
		{
			$id = $cid[0];
		}
		else
		{
			$this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel('Member_positionList', 'JResearchModel');
		
		if ($model->orderItem($id, -1))
		{
			$msg = JText::_( 'Position Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', $msg );
	}

	/**
	* Save the item(s) to the menu selected
	*/
	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0])
		{
			$id = $cid[0];
		}
		else
		{
			$this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel('Member_positionList', 'JResearchModel');
		if ($model->orderItem($id, 1))
		{
			$msg = JText::_( 'Position Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', $msg );
	}

	/**
	* Save the item(s) to the menu selected
	*/
	function saveorder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel('Member_positionList', 'JResearchModel');
		
		if ($model->setOrder($cid))
		{
			$msg = JText::_( 'New ordering saved' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=member_positions', $msg );
	}
}
?>