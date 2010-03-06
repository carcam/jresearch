<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Transfers
* @copyright	Copyright (C) 2010 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of cooperations in the backend interface.
*/

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'course.php');

/**
* JResearch Cooperations Backend Controller
*
* @package		JResearch
* @subpackage	Transfer
*/
class JResearchAdminTransfersController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
		parent::__construct();

		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.transfer');
		
		$this->registerDefaultTask('display');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');

		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'transfer');
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'transfer');
	}

	/**
	 * Default method, it shows the control panel for JResearch component.
	 *
	 * @access public
	 */
	function display()
	{
		JResearchToolbar::coursesAdminListToolbar();

		$view = &$this->getView('Courses', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Courses', 'JResearchModel');
		
		$view->setModel($model,true);
		$view->display();
	}

	function edit()
	{
		JResearchToolbar::editCourseAdminToolbar();
		$cid = JRequest::getVar('cid', array());

		$view = &$this->getView('Course', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Course', 'JResearchModel');

		if(!empty($cid)){
			$course = $model->getItem($cid[0]);
			if(!empty($course)){
				$user = &JFactory::getUser();
				//Check if it is checked out
				if($course->isCheckedOut($user->get("id")))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=courses', JText::_('You cannot edit this item. Another user has locked it.'));
				}
				else
				{
					$course->checkout($user->get("id"));
					$view->setModel($model,true);
					$view->display();					
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=courses');
			}			
		}else{
			$view->setModel($model,true);
			$view->display();
		}
	}

	function publish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$course = new JResearchCourse($db);
		$course->publish($cid, 1);

		$this->setRedirect('index.php?option=com_jresearch&controller=courses', JText::_('The items were successfully published'));
	}

	function unpublish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$course = new JResearchCourse($db);
		$course->publish($cid, 0);

		$this->setRedirect('index.php?option=com_jresearch&controller=courses', JText::_('The items were successfully unpublished'));
	}

	function remove()
	{
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;

		$course = new JResearchCourse($db);

		foreach($cid as $id)
		{
			if(!$course->delete($id))
			{
				JError::raiseWarning(1, JText::sprintf('Cooperation with id %d could not be deleted', $id));
			}
			else
			{
				$n++;
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=courses', JText::sprintf('%d successfully deleted.', $n));
	}

	function save()
	{
		global $mainframe;
		if(!JRequest::checkToken())
		{
		    $this->setRedirect('index.php?option=com_jresearch');
		    return;
		}
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');
		
		$db =& JFactory::getDBO();

		$course = new JResearchCourse($db);

		// Bind request variables
		$post = JRequest::get('post');

		$course->bind($post);
		$course->title = JRequest::getVar('title', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		// Validate and save
		$task = JRequest::getVar('task');		
		if($course->check())
		{
			if($course->store())
			{
				//Specific redirect for specific task
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=courses', JText::_('The course was successfully saved.'));
				elseif($task == 'apply')
					$this->setRedirect('index.php?option=com_jresearch&controller=courses&task=edit&cid[]='.$course->id, JText::_('The course was successfully saved.'));

				// Trigger event
				$arguments = array('course', $course->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);

			}
			else
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.$course->getError());								
				$idText = !empty($course->id) && $task == 'apply'?'&cid[]='.$course->id:'';
				$this->setRedirect('index.php?option=com_jresearch&controller=courses&task=edit'.$idText);
			}
		}
		else
		{
			$idText = !empty($course->id) && $task == 'apply'?'&cid[]='.$course->id:'';			
			for($i=0; $i<count($course->getErrors()); $i++)
				JError::raiseWarning(1, $course->getError($i));
			$this->setRedirect('index.php?option=com_jresearch&controller=courses&task=edit'.$idText);
		}
		
		//Unlock record
		if(!empty($course->id)){
			$user =& JFactory::getUser();
			if(!$course->isCheckedOut($user->get('id')))
			{
				if(!$course->checkin())
					JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
	}

	function cancel()
	{
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Course', 'JResearchModel');

		if($id != null)
		{
			$course = $model->getItem($id);

			if(!$course->checkin())
			{
				JError::raiseWarning(1, JText::_('The record could not be unlocked.'));
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=courses');
	}
}
?>