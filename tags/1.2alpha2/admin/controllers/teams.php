<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Teams
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of staff members in the backend interface.
*/

jimport('joomla.application.component.controller');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'team.php');

/**
 * Team Backend Controller
 * @package		JResearch
 * @subpackage	Team
 */
class JResearchAdminTeamsController extends JController
{
/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
		parent::__construct();
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.teams');
		
		// Task for edition of profile
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('cancel', 'cancel');
		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'teams');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'staff');
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'teams');
	}
	
	/**
	 * Default method, it shows the list of teams in the administration style.
	 *
	 * @access public
	 */
	function display()
	{
		$view = &$this->getView('Teams', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Teams', 'JResearchModel');
		
		$view->setModel($model, true);
		
		$view->setLayout('default');
		$view->display();
	}

	/**
	* Invoked when the user has decided to add/edit a team.
	*/	
	function edit()
	{
		$cid = JRequest::getVar('cid', array());
		
		$view = &$this->getView('Team', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Team', 'JResearchModel');
		$membersModel =& $this->getModel('Staff', 'JResearchModel');
		$teamsModel =& $this->getModel('Teams', 'JResearchModel');
		
		if(!empty($cid))
		{
			$team = $model->getItem($cid[0]);
			if(!empty($team))
			{
				$user = &JFactory::getUser();
				//Check if it is checked out
				if($team->isCheckedOut($user->get("id")))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=teams', JText::_('You cannot edit this item. Another user has locked it.'));
				}
				else
				{
					$team->checkout($user->get("id"));				
				}
			}
			else
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=teams');
			}			
		}
		
		$view->setModel($model,true);
		$view->setModel($membersModel);
		$view->setModel($teamsModel);
		$view->display();
	}

	function publish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$team = new JResearchTeam($db);
		$team->publish($cid, 1);

		$this->setRedirect('index.php?option=com_jresearch&controller=teams', JText::_('The items were successfully published'));
	}

	function unpublish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$team = new JResearchTeam($db);
		$team->publish($cid, 0);

		$this->setRedirect('index.php?option=com_jresearch&controller=teams', JText::_('The items were successfully unpublished'));
	}

	function remove()
	{
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;

		$team = new JResearchTeam($db);

		foreach($cid as $id)
		{
			if(!$team->delete($id))
			{
				JError::raiseWarning(1, JText::sprintf('Team with id %d could not be deleted', $id));
			}
			else
			{
				$n++;
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=teams', JText::sprintf('%d successfully deleted.', $n));
	}

	function save()
	{
		global $mainframe;
	    if(!JRequest::checkToken())
		{
		    $this->setRedirect('index.php?option=com_jresearch');
		    return;
		}
		
		$db =& JFactory::getDBO();
		$team = new JResearchTeam($db);

		// Bind request variables
		$post = JRequest::get('post');

		$team->bind($post);
		$team->name = JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$team->description = JRequest::getVar('description','','post', 'string', JREQUEST_ALLOWRAW);
		
		//If parent isn't set to valid id, set to null
		//@todo prevent to set cycle
		if($team->parent <= 0 || $team->parent == $team->id)
		{
			$team->parent = null;
		}
		
		$members = JRequest::getVar('members', array(), 'post');
		
		//Set members
		foreach($members as $member)
		{
			$team->setMember($member);
		}

			// Validate and save
		if($team->check())
		{
			if($team->store())
			{
				$task = JRequest::getVar('task');

				//Specific redirect for specific task
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=teams', JText::_('The team was successfully saved.'));
				elseif($task == 'apply')
					$this->setRedirect('index.php?option=com_jresearch&controller=teams&task=edit&cid[]='.$team->id, JText::_('The team was successfully saved.'));

				// Trigger event
				$arguments = array('team', $team->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);

			}
			else
			{
				$this->setRedirect('index.php?option=com_jresearch&controller=teams&task=edit&cid[]='.$team->id, JText::_('JRESEARCH_SAVE_FAILED').': '.$team->getError());
			}
		}
		else
		{
			for($i=0; $i<count($team->getErrors()); $i++)
				JError::raiseWarning(1, $team->getError($i));
			$this->setRedirect('index.php?option=com_jresearch&controller=teams&task=edit&cid[]='.$team->id);
		}
		
		//Unlock record
		if(!empty($fac->id)){
			$user =& JFactory::getUser();
			if(!$team->isCheckedOut($user->get('id')))
			{
				if(!$team->checkin())
					JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
	}

	function cancel()
	{
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Team', 'JResearchModel');

		if($id != null)
		{
			$team = $model->getItem($id);

			if(!$team->checkin())
			{
				JError::raiseWarning(1, JText::_('The record could not be unlocked.'));
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=teams');
	}
}
?>