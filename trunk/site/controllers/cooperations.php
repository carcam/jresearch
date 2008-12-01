<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of staff members.
*/

jimport('joomla.application.component.controller');

/**
 * JResearch Cooperations Component Controller
 *
 */
class JResearchCooperationsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 */
	function __construct()
	{
		parent::__construct();
		
		// Task for edition of profile
		$this->registerTask('show', 'show');
		$this->registerTask('edit', 'edit');
		$this->registerTask('save','save');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'cooperations');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'staff');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'member');

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
    	$limit = $params->get('cooperation_entries_per_page');
    	
		JRequest::setVar('limit', $limit);
		$limitstart = JRequest::getVar('limitstart', null);	
			
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);
			
		
		$model =& $this->getModel('Cooperations', 'JResearchModel');
		$view =& $this->getView('Cooperations', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	* Invoked when the visitant has decided to see a member's profile
	*/
	function show()
	{
		$model =& $this->getModel('Cooperation', 'JResearchModel');
		$view =& $this->getView('Cooperation', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();				
	}
	
	function edit()
	{
		global $mainframe;
		
		$cid = JRequest::getVar("id");

		$user =& JFactory::getUser();
		$view = &$this->getView('Cooperation', 'html', 'JResearchView');
		$model = &$this->getModel('Cooperation', 'JResearchModel');
		
		if($cid)
		{
			$coop = $model->getItem($cid);
			$user = &JFactory::getUser();

			//Check if it is checked out
			if($coop->isCheckedOut($user->get("id")))
			{
				$this->setRedirect('index.php?option=com_jresearch&controller=cooperations', JText::_('You cannot edit this item. Another user has locked it.'));
			}
			else
			{
				$coop->checkout($user->get("id"));
			}
		}
		
		$view->setModel($model, true);
		$view->setLayout('edit');
		$view->display();
	}
	
	function save()
	{
		global $mainframe;

		$availableTypes = array("image/png","image/gif","image/jpg","image/jpeg");
		$db =& JFactory::getDBO();

		$imageFolder = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'cooperations';
		$imageUrl = JURI::base().'components/com_jresearch/assets/cooperations/';
		$coop = new JResearchCooperation($db);

		// Bind request variables
		$post = JRequest::get('post');
		$fileArr = JRequest::getVar('inputfile', null, 'FILES');
		$uploadedFile = $fileArr['tmp_name'];
		$del = JRequest::getVar('delete');

		//Set image to null, if delete is on
		if($del == 'on')
			$coop->image_url = null;

		//Save image file
		if($fileArr != null && $uploadedFile != null)
		{
			$newName = $imageFolder.DS.basename($uploadedFile);

			list($width, $height, $type, $attr) = getimagesize($uploadedFile);

			if(!in_array($fileArr['type'],$availableTypes))
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_IMAGE_FORMAT_NOT_SUPPORTED'));
			}
			elseif($width > _COOPERATION_IMAGE_MAX_WIDTH_ || $height > _COOPERATION_IMAGE_MAX_HEIGHT_)
			{
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_EXCEEDS_SIZE', _COOPERATION_IMAGE_MAX_WIDTH_, _COOPERATION_IMAGE_MAX_HEIGHT_));
			}
			else
			{
				// Get extension
				$extArr = explode('/', $fileArr['type']);
				$ext = $extArr[1];
				$newName = $newName.'.'.$ext;

				if(!move_uploaded_file($uploadedFile, $newName))
				{
					JError::raiseWarning(1, JText::_('JRESEARCH_PHOTO_NOT_UPLOADED'));
				}
				else
				{
					if($coop->image_url)
						@unlink($coop->image_url);

					$coop->image_url = $imageUrl.basename($newName);
				}
			}
		}

		$coop->bind($post);
		$coop->name = JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$coop->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		// Validate and save
		if($coop->check())
		{
			if($coop->store())
			{
				$task = JRequest::getVar('task');

				//Specific redirect for specific task
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=cooperations', JText::_('The cooperation was successfully saved.'));
				elseif($task == 'apply')
					$this->setRedirect('index.php?option=com_jresearch&controller=cooperations&task=edit&cid[]='.$coop->id, JText::_('The cooperation was successfully saved.'));

				// Trigger event
				$arguments = array('cooperation', $coop->id);
				$mainframe->triggerEvent('onAfterSaveCooperationEntity', $arguments);

			}
			else
			{
				JError::raiseWarning(1, $coop->getError());
				$this->setRedirect('index.php?option=com_jresearch&controller=cooperations&task=edit&cid[]='.$coop->id, JText::_('JRESEARCH_SAVE_FAILED'));
			}
		}
		else
		{
			JError::raiseWarning(1, $coop->getError());
			$this->setRedirect('index.php?option=com_jresearch&controller=cooperations&task=edit&cid[]='.$coop->id);
		}

		//Reordering ordering of other cooperations
		$coop->reorder();
		
		//Unlock record
		$user =& JFactory::getUser();
		if(!$coop->isCheckedOut($user->get('id')))
		{
			if(!$coop->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
		}
	}
}
?>