<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage		Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research projects in the backend interface.
*/

jimport('joomla.application.component.controller');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'project.php');


/**
* Projects Backend Controller
*
* @package		JResearch
*/
class JResearchAdminProjectsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'projects');
	}

	/**
	 * Default method, it shows the list of research projects in the administrator list.
	 * 
	 * @access public
	 */

	function display(){
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'projectslist');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$view = &$this->getView('ProjectsList', 'html', 'JResearchAdminView');
		$model = &$this->getModel('ProjectsList', 'JResearchModel');
		$areaModel = &$this->getModel('ResearchArea', 'JResearchModel');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();

	}

	/**
	* Invoked when an administrator decides to create/edit a project.
	* 
	* @access public
	*/
	function edit(){
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$cid = JRequest::getVar('cid');
		
		$view = &$this->getView('Project', 'html', 'JResearchAdminView');	
		$areaModel = &$this->getModel('ResearchAreasList', 'JResearchModel');
		$model =& $this->getModel('Project', 'JResearchModel');

		if($cid){
			$project = $model->getItem($cid[0]);
			$user =& JFactory::getUser();
			// Verify if it is checked out
			if($project->isCheckedOut($user->get('id'))){
				$this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
			}else{
				$project->checkout($user->get('id'));
				$view->setModel($model, true);
				$view->setModel($areaModel);
				$view->display();
			}	
		}else{
			$session =& JFactory::getSession();
			$session->set('citedRecords', array(), 'jresearch');
			$view->setModel($model, true);
			$view->setModel($areaModel);
			$view->display();
		}
	}
	
	/**
	* Invoked when an administrator has decided to publish a one or more items
	* @access	public
	*/ 
	function publish(){
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$project = new JResearchProject($db);
		$project->publish($cid, 1);
		$this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
		
	}

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
				
		$project = new JResearchProject($db);
		$project->publish($cid, 0);
		$this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));		
		
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;
		
		$project = new JResearchProject($db);
		foreach($cid as $id){
			if(!$project->delete($id)){
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_PROJECT_NOT_DELETED', $id));
			}else{
				$n++;
			}
		}
		$this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::sprintf('JRESEARCH_SUCCESSFULLY_DELETED', $n));
		
	}

	/**
	 * Invoked when an administrator has decided to the information of a project.
	 *
	 */
	function save(){
		global $mainframe;
		$db =& JFactory::getDBO();
		$photosFolder = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'projects';
		$photosUrl = JURI::base().'components/com_jresearch/assets/projects/';
		$project = new JResearchProject($db);

		// Bind request variables to publication attributes	
		$post = JRequest::get('post');		
		$fileArray = JRequest::getVar('inputfile', null, 'FILES');
		$uploadedFile = $fileArray['tmp_name'];		
		$delete = JRequest::getVar('delete');
			
		if($delete == 'on')
			$project->url_project_image = null;
		
		if($fileArray != null && $uploadedFile != null){								
			$newName = $photosFolder.DS.basename($uploadedFile);
			list($width, $height, $type, $attr) = getimagesize($uploadedFile);			

			if($fileArray['type'] != 'image/gif' && $fileArray['type'] != 'image/png' && $fileArray['type']	!= 'image/jpg' && $fileArray['type'] != 'image/jpeg')
				JError::raiseWarning(1, JText::_('JRESEARCH_IMAGE_FORMAT_NOT_SUPPORTED'));
			elseif($width > 400 || $height > 400){
				JError::raiseWarning(1, JText::_('JRESEARCH_EXCEEDS_SIZE'));
			}else{
				// Get extension 
				$extArray = explode('/', $fileArray['type']);				
				$extension = $extArray[1];
				$newName = $newName.'.'.$extension;
				if(!move_uploaded_file($uploadedFile, $newName))
					JError::raiseWarning(1, JText::_('JRESEARCH_PHOTO_NOT_UPLOADED'));
				else{
					if($project->url_project_image)
						@unlink($project->url_project_image);
					$project->url_project_image = $photosUrl.basename($newName);
				}
			}		
		}
					
		
		$project->bind($post);
		$project->title = trim($project->title);
		$project->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		//Time to set the authors
		$maxAuthors = JRequest::getInt('maxmembers');
		$k = 0;
	
		for($j=0; $j<=$maxAuthors; $j++){
			$value = JRequest::getVar("members".$j);
			if(!empty($value)){
				if(is_numeric($value)){
					// In that case, we are talking about a staff member
					$project->setAuthor(trim($value), $k, true); 
				}else{
					// For external authors 
					$project->setAuthor(trim($value), $k);
				}
				
				$k++;
			}			
		}
		
		// Validate and save
		if($project->check()){
			if($project->store()){
				$task = JRequest::getVar('task');
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_PROJECT_SUCCESSFULLY_SAVED'));
				elseif($task == 'apply') 
					$this->setRedirect('index.php?option=com_jresearch&controller=projects&task=edit&cid[]='.$project->id, JText::_('JRESEARCH_PROJECT_SUCCESSFULLY_SAVED'));					

				// Trigger event
				$arguments = array('project', $project->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);
					
			}else{
				JError::raiseWarning(1, $project->getError());
				$this->setRedirect('index.php?option=com_jresearch&controller=projects&task=edit&cid[]='.$project->id, JText::_('JRESEARCH_SAVE_FAILED'));					
			}
		}else{
			JError::raiseWarning(1, $project->getError());
			$this->setRedirect('index.php?option=com_jresearch&controller=projects&task=edit&cid[]='.$project->id);					
		}
		
		$user =& JFactory::getUser();
		if(!$project->isCheckedOut($user->get('id'))){
			if(!$project->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
		}
		
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing projects.
	 *
	 */
	function cancel(){
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Project', 'JResearchModel');		
		
		if($id != null){
			$project = $model->getItem($id);
			if(!$project->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=projects');
	}
	
}
?>
