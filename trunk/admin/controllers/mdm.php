<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Mtm
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of members of the month in the backend interface.
*/

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'mdm.php');

/**
 * Member of the Month Backend Controller
 * @package		JResearch
 * @subpackage	MtM
 */
class JResearchAdminMdmController extends JController
{
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask('add', 'add');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('cancel', 'cancel');
		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'mdm');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'staff');
	}
	
	/**
	 * Default method, it shows the list of staff members in the administration style.
	 *
	 * @access public
	 */
	function display()
	{
		$view = &$this->getView('MdmList', 'html', 'JResearchAdminView');
		$model = &$this->getModel('MdmList', 'JResearchModel');
		$mModel = &$this->getModel('Member', 'JResearchModel');
		
		$view->setModel($model, true);
		$view->setModel($mModel);
		
		$view->display();
	}

	/**
	* Invoked when the user has decided to add a member of the month. 
	*/	
	function add()
	{
		$view = &$this->getView('Mdm', 'html', 'JResearchAdminView');	
		$model = &$this->getModel('Mdm', 'JResearchModel');
		$mModel = &$this->getModel('Staff', 'JResearchModel');
		
		$view->setModel($model, true);
		$view->setModel($mModel);
		
		$view->display();

	}

	/**
	* Invoked when an administrator decides to create/edit a record.
	* 
	* @access public
	*/
	function edit()
	{
		$cid = JRequest::getVar('cid');
		$user = JFactory::getUser();

		$view = &$this->getView('Mdm', 'html', 'JResearchAdminView');	
		$model = &$this->getModel('Mdm', 'JResearchModel');
		$mModel = &$this->getModel('Staff', 'JResearchModel');
		
		if($cid)
		{
			$mdm = $model->getItem($cid[0]);
			
			if($mdm->isCheckedOut($user->get('id')))
			{
				$this->setRedirect('index.php?option=com_jresearch&controller=mdm', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
			}
			else
			{	
				$mdm->checkout($user->get('id'));
				$view->setModel($model, true);
				$view->setModel($mModel);
				
				$view->display();
			}
		}


	}
	
	/**
	* Invoked when an administrator has decided to publish a one or more items
	* @access	public
	*/ 
	function publish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		
		$member = new JResearchMdm($db);
		$member->publish($cid, 1);
		$this->setRedirect('index.php?option=com_jresearch&controller=mdm', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));

	}

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		
		$mdm = new JResearchMdm($db);
		$mdm->publish($cid, 0);
		$this->setRedirect('index.php?option=com_jresearch&controller=mdm', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove()
	{
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;
		
		$mdm = new JResearchMdm($db);
		foreach($cid as $id)
		{
			if(!$mdm->delete($id))
			{
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_MEMBER_NOT_DELETED', $id));
			}
			else
			{
				$n++;
			}
		}
		$this->setRedirect('index.php?option=com_jresearch&controller=mdm', JText::sprintf('JRESEARCH_SUCCESSFULLY_DELETED', $n));
	}
	
	/**
	* Invoked when the user has decided to save a profile, by clicking buttons Save or 
	* Apply in the edit profile form.
	*/
	function save()
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		$mdm = new JResearchMdm($db);

		// Bind request variables
		$post = JRequest::get('post');
		
		$mdm->bind($post);
		$mdm->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if($mdm->check())
		{		
			if($mdm->store())
			{
				$task = JRequest::getVar('task');
				
				//Check for task (save/apply?)
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=mdm', JText::_('JRESEARCH_MDM_SUCCESSFULLY_SAVED'));
				elseif($task == 'apply') 
					$this->setRedirect('index.php?option=com_jresearch&controller=mdm&task=edit&cid[]='.$mdm->id, JText::_('JRESEARCH_MDM_SUCCESSFULLY_SAVED'));				
				
				// Trigger event
				$arguments = array('mdm', $mdm->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);
			
			}
			else
			{
				$this->setRedirect('index.php?option=com_jresearch&controller=mdm&task=edit&cid[]='.$mdm->id, JText::_('JRESEARCH_SAVE_FAILED').' '.$mdm->getError());					
			}
		}
		else
		{
			JError::raiseWarning(1, $mdm->getError());
			$this->setRedirect('index.php?option=com_jresearch&controller=mdm&task=edit&cid[]='.$mdm->id);					
		}
		
		$user =& JFactory::getUser();
		if(!$mdm->isCheckedOut($user->get('id')))
		{
			if(!$mdm->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
		}
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing 
	 * profiles.
	 *
	 */
	function cancel()
	{
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Mdm', 'JResearchModel');		
		
		if($id != null)
		{
			$mdm = $model->getItem($id);
						
			if(!$mdm->checkin())
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=mdm');
	}
}
?>