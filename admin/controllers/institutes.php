<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Institutes
* @copyright	Copyright (C) 2009 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of institutes in the backend interface.
*/

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'institute.php');

/**
* JResearch Institutes Backend Controller
*
* @package		JResearch
* @subpackage	Institutes
*/
class JResearchAdminInstitutesController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
		parent::__construct();

		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.institutes');
		
		$this->registerDefaultTask('display');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');

		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'institutes');
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'institutes');
	}

	/**
	 * Default method, it shows the control panel for JResearch component.
	 *
	 * @access public
	 */
	function display()
	{
		$view = &$this->getView('Institutes', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Institutes', 'JResearchModel');
		
		$view->setModel($model,true);
		$view->display();
	}

	function edit()
	{
		$cid = JRequest::getVar('cid', array());

		$view = &$this->getView('Institute', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Institute', 'JResearchModel');

		if(!empty($cid)){
			$institute = $model->getItem($cid[0]);
			if(!empty($institute)){
				$user = &JFactory::getUser();
				//Check if it is checked out
				if($institute->isCheckedOut($user->get("id")))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=institutes', JText::_('You cannot edit this item. Another user has locked it.'));
				}
				else
				{
					$institute->checkout($user->get("id"));
					$view->setModel($model,true);
					$view->display();					
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=institutes');
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

		$institute = new JResearchInstitute($db);
		$institute->publish($cid, 1);

		$this->setRedirect('index.php?option=com_jresearch&controller=institutes', JText::_('The items were successfully published'));
	}

	function unpublish()
	{
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$institute = new JResearchInstitute($db);
		$institute->publish($cid, 0);

		$this->setRedirect('index.php?option=com_jresearch&controller=institutes', JText::_('The items were successfully unpublished'));
	}

	function remove()
	{
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;

		$institute = new JResearchInstitute($db);

		foreach($cid as $id)
		{
			if(!$institute->delete($id))
			{
				JError::raiseWarning(1, JText::sprintf('Cooperation with id %d could not be deleted', $id));
			}
			else
			{
				$n++;
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=institutes', JText::sprintf('%d successfully deleted.', $n));
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
		
		$params = JComponentHelper::getParams('com_jresearch');
		$imageWidth = $params->get('institute_image_width', _INSTITUTE_IMAGE_MAX_WIDTH_);
		$imageHeight = $params->get('institute_image_height', _INSTITUTE_IMAGE_MAX_HEIGHT_);

		$institute = new JResearchInstitute($db);

		// Bind request variables
		$post = JRequest::get('post');

		$institute->bind($post);
		$institute->name = JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$institute->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		//Generate an alias if needed
		$institute = trim(JRequest::getVar('alias'));
		if(empty($institute)){
			$institute->alias = JResearch::alias($institute->name);
		}
		
		//Upload photo
		$fileArr = JRequest::getVar('inputfile', null, 'FILES');
		$del = JRequest::getVar('delete');
		
		JResearch::uploadImage(	$institute->image_url, 	//Image string to save
								$fileArr, 			//Uploaded File array
								'assets'.DS.'institutes'.DS, //Relative path from administrator folder of the component
								($del == 'on')?true:false,	//Delete?
								 $imageWidth, //Max Width
								 $imageHeight //Max Height
		); 
		
		// Validate and save
		$task = JRequest::getVar('task');
		if($institute->check())
		{
			if($institute->store())
			{
				//Specific redirect for specific task
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=institutes', JText::_('The cooperation was successfully saved.'));
				elseif($task == 'apply')
					$this->setRedirect('index.php?option=com_jresearch&controller=institutes&task=edit&cid[]='.$institute->id, JText::_('The cooperation was successfully saved.'));

				// Trigger event
				$arguments = array('cooperation', $institute->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);

			}
			else
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.$institute->getError());								
				$idText = !empty($institute->id) && $task == 'apply'?'&cid[]='.$institute->id:'';
				$this->setRedirect('index.php?option=com_jresearch&controller=institutes&task=edit'.$idText);
			}
		}
		else
		{
			$idText = !empty($institute->id) && $task == 'apply'?'&cid[]='.$institute->id:'';			

			for($i=0; $i<count($institute->getErrors()); $i++)
				JError::raiseWarning(1, $institute->getError($i));
				
			$this->setRedirect('index.php?option=com_jresearch&controller=institutes&task=edit'.$idText);
		}

		//Reordering ordering of other Institutes
		$institute->reorder();
		
		//Unlock record
		if(!empty($institute->id)){
			$user =& JFactory::getUser();
			if(!$institute->isCheckedOut($user->get('id')))
			{
				if(!$institute->checkin())
					JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
	}

	function cancel()
	{
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Institute', 'JResearchModel');

		if($id != null)
		{
			$institute = $model->getItem($id);

			if(!$institute->checkin())
			{
				JError::raiseWarning(1, JText::_('The record could not be unlocked.'));
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=institutes');
	}
}
?>