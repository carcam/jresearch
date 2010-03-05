<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research projects in the backend interface.
*/
define('_PROJECT_IMAGE_MAX_WIDTH_', 400);
define('_PROJECT_IMAGE_MAX_HEIGHT_', 400);

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'project.php');

/**
* Projects Backend Controller
* @package		JResearch
* @subpackage	Projects
*/
class JResearchAdminProjectsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.projects');
		
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
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'financiers');
		
		$cid = JRequest::getVar('cid', array());
		
		$view = &$this->getView('Project', 'html', 'JResearchAdminView');	
		
		$finModel = &$this->getModel('Financiers', 'JResearchModel');
		$areaModel = &$this->getModel('ResearchAreasList', 'JResearchModel');
		$model =& $this->getModel('Project', 'JResearchModel');

		if(!empty($cid)){
			$project = $model->getItem($cid[0]);
			if(!empty($project)){
				$user =& JFactory::getUser();
				// Verify if it is checked out
				if($project->isCheckedOut($user->get('id'))){
					$this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
				}else{
					$project->checkout($user->get('id'));
					$view->setModel($model, true);
					$view->setModel($areaModel);
					$view->setModel($finModel);
					$view->display();
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=projects');
			}	
		}else{
			$session =& JFactory::getSession();
			$session->set('citedRecords', array(), 'jresearch');
			$view->setModel($model, true);
			$view->setModel($areaModel);
			$view->setModel($finModel);
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
	    if(!JRequest::checkToken())
		{
		    $this->setRedirect('index.php?option=com_jresearch');
		    return;
		}
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');
		
		$db =& JFactory::getDBO();
		$project = new JResearchProject($db);
		$user = JFactory::getUser();

		// Bind request variables to publication attributes	
		$post = JRequest::get('post');		
		
		$project->bind($post);
		$project->title = trim(JRequest::getVar('title','','post','string',JREQUEST_ALLOWHTML));
		$project->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		//Upload photo
		$fileArr = JRequest::getVar('inputfile', null, 'FILES');
		$delete = JRequest::getVar('delete');
		JResearch::uploadImage(	$project->url_project_image, 	//Image string to save
								$fileArr, 			//Uploaded File array
								'assets'.DS.'projects'.DS, //Relative path from administrator folder of the component
								($delete == 'on')?true:false,	//Delete?
								 _PROJECT_IMAGE_MAX_WIDTH_, //Max Width
								 _PROJECT_IMAGE_MAX_HEIGHT_ //Max Height
		);

		//Time to set the authors
		$maxAuthors = JRequest::getInt('nmembersfield');
		$k = 0;
	
		for($j=0; $j<=$maxAuthors; $j++){
			$value = JRequest::getVar("membersfield".$j);
			$flagValue = JRequest::getVar("check_membersfield".$j);
			$flag = $flagValue == 'on'?true:false;
			if(!empty($value)){
				$project->setAuthor(trim($value), $k, is_numeric($value), $flag);
				$k++;
			}			
		}
		
		//Set financiers for the project
		if(empty($project->text_id_financier)){
			$financiers = JRequest::getVar('id_financier');
			
			if(is_array($financiers))
			{
				foreach($financiers as $fin)
				{
					$id = (int) $fin;
					
					$project->setFinancier($id);
				}
			}
		}
		// Set the id of the author if the item is new
		if(empty($project->id))
			$project->created_by = $user->get('id');
		
		// Validate and save
		$task = JRequest::getVar('task');
		if($project->check()){
			if($project->store()){
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=projects', JText::_('JRESEARCH_PROJECT_SUCCESSFULLY_SAVED'));
				elseif($task == 'apply') 
					$this->setRedirect('index.php?option=com_jresearch&controller=projects&task=edit&cid[]='.$project->id, JText::_('JRESEARCH_PROJECT_SUCCESSFULLY_SAVED'));					

				// Trigger event
				$arguments = array('project', $project->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);
					
			}else{
				if($db->getErrorNum() == 1062)				
					JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.JText::_('JRESEARCH_DUPLICATED_RECORD'));
				else
					JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.$db->getErrorMsg());				
				
				$idText = !empty($project->id) && $task == 'apply'?'&cid[]='.$project->id:'';
				$this->setRedirect('index.php?option=com_jresearch&controller=projects&task=edit'.$idText);					
			}
		}else{
			for($i=0; $i<count($project->getErrors()); $i++)
				JError::raiseWarning(1, $project->getError($i));
				
			$idText = !empty($project->id)?'&cid[]='.$project->id:'';			
			$this->setRedirect('index.php?option=com_jresearch&controller=projects&task=edit'.$idText);				
		}
		
		if(!empty($project->id)){
			$user =& JFactory::getUser();
			if(!$project->isCheckedOut($user->get('id'))){
				if(!$project->checkin())
					JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
			}
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
