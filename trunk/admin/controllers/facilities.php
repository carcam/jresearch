<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of facilities in the backend interface.
*/
define('_FACILITY_IMAGE_MAX_WIDTH_', 1024);
define('_FACILITY_IMAGE_MAX_HEIGHT_', 768);

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'facility.php');

/**
* JResearch Facilities Backend Controller
* @package		JResearch
* @subpackage	Facilities
*/
class JResearchAdminFacilitiesController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
		parent::__construct();

		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.facilities');
		
		$this->registerDefaultTask('display');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');

		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'facilities');
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'facilities');
	}

	/**
	 * Default method, it shows the control panel for JResearch component.
	 *
	 * @access public
	 */
	function display()
	{
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');

		$view = &$this->getView('Facilities', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Facilities', 'JResearchModel');
		$areaModel = &$this->getModel('ResearchArea', 'JResearchModel');
		
		$view->setModel($model,true);
		$view->setModel($areaModel);
		$view->display();
	}


	function edit()
	{
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
	
		$cid = JRequest::getVar('cid', array());

		$view = &$this->getView('Facility', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Facility', 'JResearchModel');
		$areaModel = &$this->getModel('ResearchAreasList', 'JResearchModel');

		if(!empty($cid)){
			$fac = $model->getItem($cid[0]);
			
			if(!empty($fac)){
				$user = &JFactory::getUser();
	
				//Check if it is checked out
				if($fac->isCheckedOut($user->get("id")))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=facilities', JText::_('You cannot edit this item. Another user has locked it.'));
				}
				else
				{
					$fac->checkout($user->get("id"));
					$view->setModel($model,true);
					$view->setModel($areaModel);
					$view->display();
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=facilities');
			}
		}
		else
		{
			$view->setModel($model,true);
			$view->setModel($areaModel);
			$view->display();
		}
	}

	function publish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$fac = new JResearchFacility($db);
		$fac->publish($cid, 1);

		$this->setRedirect('index.php?option=com_jresearch&controller=facilities', JText::_('The items were successfully published'));
	}

	function unpublish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$fac = new JResearchCooperation($db);
		$fac->publish($cid, 0);

		$this->setRedirect('index.php?option=com_jresearch&controller=facilities', JText::_('The items were successfully unpublished'));
	}

	function remove()
	{
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;

		$fac = new JResearchFacility($db);

		foreach($cid as $id)
		{
			if(!$fac->delete($id))
			{
				JError::raiseWarning(1, JText::sprintf('Facility with id %d could not be deleted', $id));
			}
			else
			{
				$n++;
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=facilities', JText::sprintf('%d successfully deleted.', $n));
	}

	function save()
	{
		global $mainframe;

		$availableTypes = array("image/png","image/gif","image/jpg","image/jpeg");
		$db =& JFactory::getDBO();

		$imageFolder = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'facilities';
		$imageUrl = JURI::base().'components/com_jresearch/assets/facilities/';
		$fac = new JResearchFacility($db);

		// Bind request variables
		$post = JRequest::get('post');
		$fileArr = JRequest::getVar('inputfile', null, 'FILES');
		$uploadedFile = $fileArr['tmp_name'];
		$del = JRequest::getVar('delete');

		//Set image to null, if delete is on
		if($del == 'on')
			$fac->image_url = null;

		//Save image file
		if($fileArr != null && $uploadedFile != null)
		{
			$newName = $imageFolder.DS.basename($uploadedFile);

			list($width, $height, $type, $attr) = getimagesize($uploadedFile);

			if(!in_array($fileArr['type'],$availableTypes))
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_IMAGE_FORMAT_NOT_SUPPORTED'));
			}
			elseif($width > _FACILITY_IMAGE_MAX_WIDTH_ || $height > _FACILITY_IMAGE_MAX_HEIGHT_)
			{
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_EXCEEDS_SIZE',_FACILITY_IMAGE_MAX_WIDTH_,_FACILITY_IMAGE_MAX_HEIGHT_));
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
					if($fac->image_url)
						@unlink($fac->image_url);

					$fac->image_url = $imageUrl.basename($newName);
				}
			}
		}

		$fac->bind($post);
		$fac->name = JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$fac->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		// Validate and save
		if($fac->check())
		{
			if($fac->store())
			{
				$task = JRequest::getVar('task');

				//Specific redirect for specific task
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=facilities', JText::_('The facility was successfully saved.'));
				elseif($task == 'apply')
					$this->setRedirect('index.php?option=com_jresearch&controller=facilities&task=edit&cid[]='.$fac->id, JText::_('The facility was successfully saved.'));

				// Trigger event
				$arguments = array('facility', $fac->id);
				$mainframe->triggerEvent('onAfterSaveFacilityEntity', $arguments);

			}
			else
			{
				JError::raiseWarning(1, $fac->getError());
				$this->setRedirect('index.php?option=com_jresearch&controller=facilities&task=edit&cid[]='.$fac->id, JText::_('JRESEARCH_SAVE_FAILED'));
			}
		}
		else
		{
			JError::raiseWarning(1, $fac->getError());
			$this->setRedirect('index.php?option=com_jresearch&controller=facilities&task=edit&cid[]='.$fac->id);
		}
		
		//Reordering ordering of other facilities
		$fac->reorder('published >= 0 AND id_research_area = '.(int) $fac->id_research_area);

		//Unlock record
		$user =& JFactory::getUser();
		if(!$fac->isCheckedOut($user->get('id')))
		{
			if(!$fac->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
		}
	}

	function cancel()
	{
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Facility', 'JResearchModel');

		if($id != null)
		{
			$fac = $model->getItem($id);

			if(!$fac->checkin())
			{
				JError::raiseWarning(1, JText::_('The record could not be unlocked.'));
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=facilities');
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
			$this->setRedirect( 'index.php?option=com_jresearch&controller=facilities', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel('Facilities', 'JResearchModel');
		if ($model->orderItem($id, -1))
		{
			$msg = JText::_( 'Facility Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=facilities', $msg );
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
			$this->setRedirect( 'index.php?option=com_jresearch&controller=facilities', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel('Facilities', 'JResearchModel');
		if ($model->orderItem($id, 1))
		{
			$msg = JText::_( 'Facility Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=facilities', $msg );
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

		$model =& $this->getModel('Facilities', 'JResearchModel');
		
		if ($model->setOrder($cid))
		{
			$msg = JText::_( 'New ordering saved' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=facilities', $msg );
	}
}
?>