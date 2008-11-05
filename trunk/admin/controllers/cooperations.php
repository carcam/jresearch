<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of cooperations in the backend interface.
*/

define('_PROJECT_IMAGE_MAX_WIDTH_', 400);
define('_PROJECT_IMAGE_MAX_HEIGHT_', 400);

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'cooperation.php');

/**
* JResearch Cooperations Backend Controller
*
* @package		JResearch
* @subpackage	Cooperations
*/
class JResearchAdminCooperationsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
		parent::__construct();

		$this->registerDefaultTask('display');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');

		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'cooperations');
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'cooperations');
	}

	/**
	 * Default method, it shows the control panel for JResearch component.
	 *
	 * @access public
	 */
	function display()
	{
		JResearchToolbar::cooperationsAdminListToolbar();

		$view = &$this->getView('Cooperations', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Cooperations', 'JResearchModel');
		
		$view->setModel($model,true);
		$view->display();
	}

	function edit()
	{
		JResearchToolbar::editCooperationAdminToolbar();
		$cid = JRequest::getVar("cid");

		$view = &$this->getView('Cooperation', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Cooperation', 'JResearchModel');

		if($cid)
		{
			$coop = $model->getItem($cid[0]);
			$user = &JFactory::getUser();

			//Check if it is checked out
			if($coop->isCheckedOut($user->get("id")))
			{
				$this->setRedirect('index.php?option=com_jresearch&controller=cooperations', JText::_('You cannot edit this item. Another user has locked it.'));
			}
			else
			{
				$coop->checkout($user->get("id"));
				$view->setModel($model,true);
				$view->display();
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
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$coop = new JResearchCooperation($db);
		$coop->publish($cid, 1);

		$this->setRedirect('index.php?option=com_jresearch&controller=cooperations', JText::_('The items were successfully published'));
	}

	function unpublish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$coop = new JResearchCooperation($db);
		$coop->publish($cid, 0);

		$this->setRedirect('index.php?option=com_jresearch&controller=cooperations', JText::_('The items were successfully unpublished'));
	}

	function remove()
	{
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;

		$coop = new JResearchCooperation($db);

		foreach($cid as $id)
		{
			if(!$coop->delete($id))
			{
				JError::raiseWarning(1, JText::sprintf('Cooperation with id %d could not be deleted', $id));
			}
			else
			{
				$n++;
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=cooperations', JText::sprintf('%d successfully deleted.', $n));
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
				JError::raiseWarning(1, JText::_('Image format not supported. Please provide images with extension jpg, gif, png'));
			}
			elseif($width > _PROJECT_IMAGE_MAX_WIDTH_ || $height > _PROJECT_IMAGE_MAX_HEIGHT_)
			{
				JError::raiseWarning(1, JText::_('The image exceeds maximum size allowed (400x400)'));
			}
			else
			{
				// Get extension
				$extArr = explode('/', $fileArr['type']);
				$ext = $extArr[1];
				$newName = $newName.'.'.$ext;

				if(!move_uploaded_file($uploadedFile, $newName))
				{
					JError::raiseWarning(1, JText::_('The photo could not be imported into JResearch space.'));
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
				$this->setRedirect('index.php?option=com_jresearch&controller=cooperations&task=edit&cid[]='.$coop->id, JText::_('The information could not be saved.'));
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
				JError::raiseWarning(1, JText::_('The record could not be unlocked.'));
		}
	}

	function cancel()
	{
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Cooperation', 'JResearchModel');

		if($id != null)
		{
			$coop = $model->getItem($id);

			if(!$coop->checkin())
			{
				JError::raiseWarning(1, JText::_('The record could not be unlocked.'));
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=cooperations');
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
			$this->setRedirect( 'index.php?option=com_jresearch&controller=cooperations', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel('Cooperations', 'JResearchModel');
		
		if ($model->orderItem($id, -1))
		{
			$msg = JText::_( 'Cooperation Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=cooperations', $msg );
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
			$this->setRedirect( 'index.php?option=com_jresearch&controller=cooperations', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel('Cooperations', 'JResearchModel');
		if ($model->orderItem($id, 1))
		{
			$msg = JText::_( 'Cooperation Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=cooperations', $msg );
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

		$model =& $this->getModel('Cooperations', 'JResearchModel');
		
		if ($model->setOrder($cid))
		{
			$msg = JText::_( 'New ordering saved' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=cooperations', $msg );
	}
}
?>