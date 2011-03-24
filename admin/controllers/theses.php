<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of theses in the backend interface.
*/

jimport('joomla.application.component.controller');


require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'thesis.php');
/**
 * Theses Backend Controller
 * @package		JResearch
 * @subpackage	Theses
 */
class JResearchAdminThesesController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.theses');
		
		// Tasks for edition of theses when the user is authenticated
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'theses');
		
	}

	/**
	 * Default method, it shows the list of theses.
	 *
	 * @access public
	 */

	function display(){
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'theseslist');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$view = &$this->getView('ThesesList', 'html', 'JResearchAdminView');
		$model = &$this->getModel('ThesesList', 'JResearchModel');
		$areaModel = &$this->getModel('ResearchArea', 'JResearchModel');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();
		
	}

	/**
	* Invoked when an administrator decides to create/edit a record.
	* 
	* @access public
	*/
	function edit(){
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$cid = JRequest::getVar('cid');		
		$view = &$this->getView('Thesis', 'html', 'JResearchAdminView');	
		$areaModel = &$this->getModel('ResearchAreasList', 'JResearchModel');
		$model =& $this->getModel('Thesis', 'JResearchModel');
		
		if($cid){
			$thesis = $model->getItem($cid[0]);
			
			if(!empty($thesis)){
				$user =& JFactory::getUser();
				// Verify if it is checked out
				if($thesis->isCheckedOut($user->get('id'))){
					$this->setRedirect('index.php?option=com_jresearch&controller=theses', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
				}else{	
					$thesis->checkout($user->get('id'));
					$view->setModel($model, true);
					$view->setModel($areaModel);
					$view->display();
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=theses');				
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

		$thesis = new JResearchThesis($db);
		$thesis->publish($cid, 1);
		$this->setRedirect('index.php?option=com_jresearch&controller=theses', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
		
		
	}

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');

		$thesis = new JResearchThesis($db);
		$thesis->publish($cid, 0);
		$this->setRedirect('index.php?option=com_jresearch&controller=theses', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));
		
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;
		
		$thesis = new JResearchThesis($db);
		foreach($cid as $id){
			if(!$thesis->delete($id)){
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_THESIS_NOT_DELETED', $id));
			}else{
				$n++;
			}
		}
		$this->setRedirect('index.php?option=com_jresearch&controller=theses', JText::sprintf('JRESEARCH_SUCCESSFULLY_DELETED', $n));
	}
	
	/**
	 * Invoked when the administrator has decided to save a thesis.
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
				
		$db = JFactory::getDBO();
		$thesis = new JResearchThesis($db);
		$user = JFactory::getUser();
		$id = JRequest::getInt('id');
		$post = JRequest::get('post');		
				
		$filesCount = JRequest::getInt('count_attachments');
		$filesResults = array();
		$thesisFiles = array();
		
		// Construct array with previously attached files
		for($k = 0; $k <= $filesCount; $k++){
			$oldFile = JRequest::getVar('old_attachments_'.$k, null);
			if($oldFile != null)
				$thesisFiles[] =  $oldFile;
		}
		
		for($k=0; $k<= $filesCount; $k++){
			$file = JRequest::getVar('file_attachments_'.$k, null, 'FILES');
			$params = JComponentHelper::getParams('com_jresearch');
			if(!empty($file['name'])){
				$result = JResearch::uploadDocument($file, $params->get('files_root_path', 'files').DS.'theses');
				if($result != null)
					 $filesResults[$k] = $result;				
			}else{
				$delete = JRequest::getVar('delete_attachments_'.$k, null);
				if($delete != null){
					if($delete == 'on'){
						if(!empty($thesisFiles[$k])){
							$path = JPATH_COMPONENT_ADMINISTRATOR.DS.$params->get('files_root_path', 'files').DS.'theses'.DS.$thesisFiles[$k];
							@unlink($path);
							unset($thesisFiles[$k]);
						}
					}
				}
			}
		}		
		$thesis->files = implode(';', array_merge($thesisFiles, $filesResults));
		
		
		// Bind request variables to publication attributes	
		$thesis->bind($post);
		$thesis->title = trim($thesis->title);
		$thesis->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWHTML);
		//Time to set the authors
		$count = 0;
		$maxStudents = JRequest::getInt('nstudentsfield');
		$maxDirectors = JRequest::getInt('ndirectorsfield');
		
		// Save directors information
		for($i=0; $i<=$maxDirectors; $i++){
			$value = trim(JRequest::getVar('directorsfield'.$i));
			if(!empty($value)){
				if(is_numeric($value)){
					// In that case, we are talking about a staff member
					$thesis->setAuthor($value, $count, true, true); 
				}else{
					// For external authors 
					$thesis->setAuthor($value, $count, false, true);
				}
				$count++;
			}
		}

		// Save students information
		for($i=0; $i<=$maxStudents; $i++){
			$value = trim(JRequest::getVar('studentsfield'.$i));
			if(!empty($value)){
				if(is_numeric($value)){
					// In that case, we are talking about a staff member
					$thesis->setAuthor($value, $count, true, false); 
				}else{
					// For external authors 
					$thesis->setAuthor($value, $count, false, false);
				}
				$count++;
			}	
		}
		
		// Set the id of the author if the item is new
		if(empty($thesis->id))
			$thesis->created_by = $user->get('id');

		$reset = JRequest::getVar('resethits', true);
	    if($reset == 'on'){
	    	$thesis->hits = 0;
	    }			
	    
			//Generate an alias if needed
		$alias = trim(JRequest::getVar('alias'));
		if(empty($alias)){
			$thesis->alias = JResearch::alias($thesis->title);
		}
		
		// Time to store information in the database
		if($thesis->check()){
			if($thesis->store(true)){
				$task = JRequest::getVar('task');
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=theses', JText::_('The thesis was successfully saved.'));
				elseif($task == 'apply') 
					$this->setRedirect('index.php?option=com_jresearch&controller=theses&task=edit&cid[]='.$thesis->id, JText::_('JRESEARCH_THESIS_SUCCESSFULLY_SAVED'));					
										
				// Trigger event
				$arguments = array('thesis', $thesis->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);	
				
			}else{
				if($db->getErrorNum() == 1062)				
					JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.JText::_('JRESEARCH_DUPLICATED_RECORD'));
				else
					JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.$db->getErrorMsg());				
				
				$idText = !empty($thesis->id) && $task == 'apply'?'&cid[]='.$thesis->id:'';
				$this->setRedirect('index.php?option=com_jresearch&controller=theses&task=edit'.$idText);					
			}
		}else{
			$idText = !empty($thesis->id)?'&cid[]='.$thesis->id:'';			
			
			for($i=0; $i<count($thesis->getErrors()); $i++)
				JError::raiseWarning(1, $thesis->getError($i));
			
			$this->setRedirect('index.php?option=com_jresearch&controller=theses&task=edit'.$idText);					
		}

		if(!empty($thesis->id)){
			$user =& JFactory::getUser();
			if(!$thesis->isCheckedOut($user->get('id'))){
				if(!$thesis->checkin())
					JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
			}
		}

	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing theses.
	 *
	 */
	function cancel(){
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Thesis', 'JResearchModel');		
		
		if($id != null){
			$thesis = $model->getItem($id);
			if(!$thesis->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=theses');
	}
	
}
?>
