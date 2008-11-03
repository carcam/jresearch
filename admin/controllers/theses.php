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
		$db =& JFactory::getDBO();
		$thesis = new JResearchThesis($db);
		
		// Bind request variables to publication attributes	
		$post = JRequest::get('post');		
		$thesis->bind($post);
		$thesis->title = trim($thesis->title);
		$thesis->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		//Time to set the authors
		$count = 0;
		$maxStudents = JRequest::getInt('maxstudents');
		$maxDirectors = JRequest::getInt('maxdirectors');
		
		// Save directors information
		for($i=0; $i<=$maxDirectors; $i++){
			$value = trim(JRequest::getVar('directors'.$i));
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
			$value = trim(JRequest::getVar('students'.$i));
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
				JError::raiseWarning(1, $thesis->getError());
				$this->setRedirect('index.php?option=com_jresearch&controller=theses&task=edit&cid[]='.$thesis->id, JText::_('JRESEARCH_SAVE_FAILED'));					
			}
		}else{
			JError::raiseWarning(1, $thesis->getError());
			$this->setRedirect('index.php?option=com_jresearch&controller=theses&task=edit&cid[]='.$thesis->id);					
		}

		$user =& JFactory::getUser();
		if(!$thesis->isCheckedOut($user->get('id'))){
			if(!$thesis->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
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
