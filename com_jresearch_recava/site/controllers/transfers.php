<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Transfers
* @copyright	Copyright (C) 2010 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of staff members.
*/

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'cooperation.php');

/**
 * JResearch Transfers Component Controller
 *
 */
class JResearchTransfersController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 */
	function __construct()
	{
		parent::__construct();
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.transfers');
		
		// Task for edition of profile
		$this->registerTask('show', 'show');
		$this->registerTask('edit', 'edit');
		$this->registerTask('save','save');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'transfers');

	}

	/**
	 * Default method, it shows the list of published staff members.
	 *
	 * @access public
	 */
	function display()
	{
		global $mainframe;
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$limit = $params->get('transfer_entries_per_page');
    	
		JRequest::setVar('limit', $limit);
		$limitstart = JRequest::getVar('limitstart', null);	
			
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);
			
		
		$model =& $this->getModel('Transfers', 'JResearchModel');
		$view =& $this->getView('Transfers', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	* Invoked when the visitant has decided to see a cooperation
	*/
	function show()
	{
		$model =& $this->getModel('Transfer', 'JResearchModel');
		$view =& $this->getView('Transfer', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();				
	}
	
	function edit()
	{
		global $mainframe;
		
		$cid = JRequest::getVar("id");

		$user =& JFactory::getUser();
		$view = &$this->getView('Transfer', 'html', 'JResearchView');
		$model = &$this->getModel('Transfer', 'JResearchModel');
		
		if($cid)
		{
			$transfer = $model->getItem($cid);
			$user = &JFactory::getUser();

			//Check if it is checked out
			if($transfer->isCheckedOut($user->get("id")))
			{
				$this->setRedirect('index.php?option=com_jresearch&controller=transfers', JText::_('You cannot edit this item. Another user has locked it.'));
			}
			else
			{
				$transfer->checkout($user->get("id"));
			}
		}
		
		$view->setModel($model, true);
		$view->setLayout('edit');
		$view->display();
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
		$transfer = new JResearchTransfer($db);

		// Bind request variables
		$post = JRequest::get('post');

		$transfer->bind($post);
		$transfer->name = JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$transfer->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		// Validate and save
		if($transfer->check())
		{
			if($transfer->store())
			{
				$task = JRequest::getVar('task');

				//Specific redirect for specific task
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=transfers', JText::_('The cooperation was successfully saved.'));
				elseif($task == 'apply')
					$this->setRedirect('index.php?option=com_jresearch&controller=transfers&task=edit&cid[]='.$transfer->id, JText::_('The cooperation was successfully saved.'));

				// Trigger event
				$arguments = array('cooperation', $transfer->id);
				$mainframe->triggerEvent('onAfterSaveTransferEntity', $arguments);

			}
			else
			{
				JError::raiseWarning(1, $transfer->getError());
				$this->setRedirect('index.php?option=com_jresearch&controller=transfers&task=edit&cid[]='.$transfer->id, JText::_('JRESEARCH_SAVE_FAILED'));
			}
		}
		else
		{
			JError::raiseWarning(1, $transfer->getError());
			$this->setRedirect('index.php?option=com_jresearch&controller=transfers&task=edit&cid[]='.$transfer->id);
		}

		//Reordering ordering of other cooperations
		$transfer->reorder();
		
		//Unlock record
		$user =& JFactory::getUser();
		if(!$transfer->isCheckedOut($user->get('id')))
		{
			if(!$transfer->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
		}
	}
}
?>