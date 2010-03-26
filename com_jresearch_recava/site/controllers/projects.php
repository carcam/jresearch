<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research projects.
*/

jimport('joomla.application.component.controller');

/**
* JResearch Component Projects Controller
*
*/
class JResearchProjectsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.projects');
		
		$this->registerTask('show', 'show');
		$this->registerTask('executeExport', 'executeExport');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('cancel', 'cancel');
		
		// Add models paths
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'projects');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'financiers');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'projectslist');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'project');		
	}

	/**
	 * Default method, it shows the list of research projects. 
	 *
	 * @access public
	 */

	function display(){
		global $mainframe;
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		
		$limit = $params->get('projects_entries_per_page');			
		$limitstart = JRequest::getVar('limitstart', null);		
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);		

		JRequest::setVar('limit', $limit);	
		
		$order = $params->get('order');
		$order_Dir = $params->get('order_Dir');
		
		JRequest::setVar('filter_order', $order);
		JRequest::setVar('filter_order_Dir', $order_Dir);
		
		// Set the view and the model
		$model = $this->getModel('ProjectsList', 'JResearchModel');
		$areaModel = $this->getModel('ResearchArea', 'JResearchModel');
		$view = $this->getView('ProjectsList', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();		
	}

	/**
	* Invoked when an authenticated user decides to create/edit a project he/she is part of
	* 
	* @access public
	*/
	function edit(){
		$cid = JRequest::getVar('id');
		$Itemid = JRequest::getVar('Itemid');
		
		$view = $this->getView('Project', 'html', 'JResearchView');
		$projModel = $this->getModel('Project', 'JResearchModel');	
		$finModel = $this->getModel('Financiers', 'JResearchModel');
		$model = $this->getModel('ResearchAreasList', 'JResearchModel');
		
		if($this->getTask() == 'edit')
		{
			$project = $projModel->getItem($cid);
			
			if(!empty($project)){
				$user = JFactory::getUser();
				
				// Verify if it is checked out
				if($project->isCheckedOut($user->get('id')))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=projects&Itemid='.$Itemid, JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
				}
				else
				{
					$publication->checkout($user->get('id'));	
				}
			}
			else
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			}
		}
		
		$view->setLayout('edit');
		$view->setModel($projModel, 'Project');
		$view->setModel($finModel, 'Financiers');
		$view->setModel($model, 'Researchareaslist');
		$view->display();		
	}

	/**
	* Invoked when the visitant has decided to see the detailed description of
	* a research project.
	* @access public
	*/
	function show(){
		$model =& $this->getModel('Project', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('Project', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();
	}

	/**
	* Invoked when an authenticated user sees the list of his/her projects
	* in an administrator form.
	*
	*/
	function administer(){
		JRequest::setVar('view', 'projects');
		JRequest::serVar('layout', 'admin');
		parent::display();
	}
	
	/**
	 * Triggered when the user clicks the submit button in the export projects
	 * form.
	 *
	 */
	function executeExport(){
		$session = &JFactory::getSession();
		JRequest::setVar('format', 'raw');
		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'projects');
		require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'projectexporters'.DS.'factory.php');
		$markedRecords = $session->get('markedRecords', null, 'jresearch');
		if($markedRecords !== null){
			if($markedRecords !== 'all'){
				$model = &$this->getModel('Project', 'JResearchModel');
				$projectsArray = array();
				foreach($markedRecords as $id){
					$projectsArray[] = $model->getItem($id);
				}
			}
			
			$format = 'doc';
			
			$exporter =& JResearchProjectExporterFactory::getInstance($format);
			$output = $exporter->parse($projectsArray);
			$document =& JFactory::getDocument();
			$document->setMimeEncoding($exporter->getMimeEncoding());
			$session->clear('markedRecords', 'jresearch');

			$tmpfname = "jresearch_output.$format";
			header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
			echo $output;
			
		}
	}
	
/**
	 * Invoked when an administrator has decided to the information of a project.
	 *
	 */
	function save(){
		global $mainframe;
	    if(!JRequest::checkToken())
		{
		    $this->setRedirect('index.php?option=com_jresearch&controller=projects');
		    return;
		}
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');
		
		$db = JFactory::getDBO();
		$project = JTable::getInstance('Project', 'JResearch');
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
	 * Invoked when pressing cancel button in the form for editing publications.
	 *
	 */
	function cancel(){
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Project', 'JResearchModel');
		$Itemid = JRequest::getVar('Itemid');
		$ItemidText = !empty($Itemid)?'&Itemid='.$Itemid:'';
			
		if($id != null){
			$project = $model->getItem($id);
			if(!$project->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=projects'.$ItemidText.$viewText);
	}
	
}
?>
