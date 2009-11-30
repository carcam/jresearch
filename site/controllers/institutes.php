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

defined( '_JEXEC' ) or die( 'Restricted access' );


require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'institute.php');

/**
 * JResearch Cooperations Component Controller
 *
 */
class JResearchInstitutesController extends JResearchFrontendController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 */
	function __construct()
	{
		parent::__construct();
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.institutes');
		
		// Task for edition of profile
		$this->registerTask('show', 'show');
		$this->registerTask('edit', 'edit');
		$this->registerTask('save','save');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'institutes');
		
		$this->addPathwayItem(JText::_('JRESEARCH_INSTITUTES'), 'index.php?option=com_jresearch&view=institutes');
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
    	$limit = $params->get('institute_entries_per_page');
    	
		JRequest::setVar('limit', $limit);
		$limitstart = JRequest::getVar('limitstart', null);	
			
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);
			
		$model =& $this->getModel('Institutes', 'JResearchModel');
		$view =& $this->getView('Institutes', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	* Invoked when the visitant has decided to see a cooperation
	*/
	function show()
	{
		$model =& $this->getModel('Institute', 'JResearchModel');
		$view =& $this->getView('Institute', 'html', 'JResearchView');
		
		$view->setModel($model, true);
		$view->display();				
	}
	
	function edit()
	{
		global $mainframe;
		
		$cid = JRequest::getVar("id");

		$user =& JFactory::getUser();
		$view = &$this->getView('Institute', 'html', 'JResearchView');
		$model = &$this->getModel('Institute', 'JResearchModel');
		
		$institute = $model->getItem($cid);
		
		if($cid && ($institute != null))
		{
			$user = &JFactory::getUser();

			//Check if it is checked out
			if($institute->isCheckedOut($user->get("id")))
			{
				$this->setRedirect('index.php?option=com_jresearch&controller=institutes', JText::_('You cannot edit this item. Another user has locked it.'));
			}
			else
			{
				$institute->checkout($user->get("id"));
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
		
		$params = JComponentHelper::getParams('com_jresearch');
		$imageWidth = $params->get('institute_image_width', _INSTITUTE_IMAGE_MAX_WIDTH_);
		$imageHeight = $params->get('institute_image_height', _INSTITUTE_IMAGE_MAX_HEIGHT_);
		
		$institute = new JResearchInstitute($db);

		// Bind request variables
		$post = JRequest::get('post');

		$institute->bind($post);
		$institute->name = JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$institute->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

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
		if($institute->check())
		{
			if($institute->store())
			{
				$task = JRequest::getVar('task');

				//Specific redirect for specific task
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=institutes', JText::_('The institute was successfully saved.'));
				elseif($task == 'apply')
					$this->setRedirect('index.php?option=com_jresearch&controller=institutes&task=edit&cid[]='.$institute->id, JText::_('The institute was successfully saved.'));

				// Trigger event
				$arguments = array('institute', $institute->id);
				$mainframe->triggerEvent('onAfterSaveInstituteEntity', $arguments);

			}
			else
			{
				JError::raiseWarning(1, $institute->getError());
				$this->setRedirect('index.php?option=com_jresearch&controller=institutes&task=edit&cid[]='.$institute->id, JText::_('JRESEARCH_SAVE_FAILED'));
			}
		}
		else
		{
			JError::raiseWarning(1, $institute->getError());
			$this->setRedirect('index.php?option=com_jresearch&controller=institutes&task=edit&cid[]='.$institute->id);
		}

		//Reordering ordering of other institutes
		$institute->reorder();
		
		//Unlock record
		$user =& JFactory::getUser();
		if(!$institute->isCheckedOut($user->get('id')))
		{
			if(!$institute->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
		}
	}
}
?>