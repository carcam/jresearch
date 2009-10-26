<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research areas in the backend interface.
*/

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'researchArea.php');

/**
 * Research Areas Backend Controller
 * @package		JResearch
 * @subpackage	ResearchAreas
 */
class JResearchAdminResearchAreasController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.researchareas');
		
		$this->registerTask('add', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('remove', 'remove');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		
	}

	/**
	* Invoked when saving the information about a research area.
	*/	
	function save(){
		global $mainframe;
	    if(!JRequest::checkToken())
		{
		    $this->setRedirect('index.php?option=com_jresearch');
		    return;
		}
		
		$db =& JFactory::getDBO();
		$area = new JResearchArea($db);

		// Bind request variables to publication attributes	
		$post = JRequest::get('post');		
		
		$area->bind($post);			
		$area->name = trim($area->name);
		$area->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
			//Generate an alias if needed
		$alias = trim(JRequest::getVar('alias'));
		if(empty($alias)){
			$area->alias = JResearch::alias($area->name);
		}
		
		// Validate and save
		if($area->check()){		
			if($area->store(true)){
				$task = JRequest::getVar('task');
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas', JText::_('JRESEARCH_AREA_SUCCESSFULLY_SAVED'));
				elseif($task == 'apply') 
					$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas&task=edit&cid[]='.$area->id, JText::_('JRESEARCH_AREA_SUCCESSFULLY_SAVED'));					
					
				// Trigger event
				$arguments = array('researcharea', $area->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);					
			}else{
				$idText = !empty($area->id) && $task == 'apply'?'&cid[]='.$area->id:'';
				
				if($db->getErrorNum() == 1062)				
					JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.JText::_('JRESEARCH_DUPLICATED_RECORD'));
				else
					JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.$db->getErrorMsg());				
				
				$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas&task=edit'.$idText);					
			}
		}else{			
			for($i=0; $i<count($area->getErrors()); $i++)
				JError::raiseWarning(1, $area->getError($i));
				
			$idText = !empty($area->id)?'&cid[]='.$area->id:'';			
			$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas&task=edit'.$idText);					
		}

		if(!empty($area->id)){
			$user =& JFactory::getUser();
			if(!$area->isCheckedOut($user->get('id'))){
				if(!$area->checkin())
					JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
			}
		}
	}

	/**
	 * Default method, it shows the list of research areas in an administration list.
	 *
	 * @access public
	 */

	function display(){
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'researchareaslist');
		$view = &$this->getView('ResearchAreasList', 'html', 'JResearchAdminView');
		$model = &$this->getModel('ResearchAreasList', 'JResearchModel');
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->display();
	}

	/**
	* Invoked when the user has published a set of research areas items.
	*/	
	function publish(){
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
				
		$area = new JResearchArea($db);
		$area->publish($cid, 1);
		$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
	}
	
		/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
			// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		
		$area = new JResearchArea($db);
		$area->publish($cid, 0);
		$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas', JText::_('JRESEARCH_AREAS_UNPUBLISHED_SUCCESSFULLY'));
	}
	
	/**
	* Invoked when an administrator has decided to remove one or more items.
	* @access	public
	*/ 
	function remove(){
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;
		
		$area = new JResearchArea($db);
		foreach($cid as $id){
			if(!$area->delete($id)){
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_AREA_NOT_DELETED', $id));
			}else{
				$n++;
			}
		}
		$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas', JText::sprintf('JRESEARCH_AREA_SUCCESSFULLY_DELETED', $n));
	}


	/**
	* Invoked when the administrator has decided to edit/create a research area.
	*
	* @access public
	*/
	function edit(){
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'researcharea');
		$cid = JRequest::getVar('cid');
		$view = &$this->getView('ResearchArea', 'html', 'JResearchAdminView');	
		$model = &$this->getModel('ResearchArea', 'JResearchModel');		
				
		if($cid){
			$area = $model->getItem($cid[0]);
			if(!empty($area)){
				$user =& JFactory::getUser();
				// Verify if it is checked out
				if($area->isCheckedOut($user->get('id'))){
					$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
				}else{
					$area->checkout($user->get('id'));
					$view->setLayout('default');
					$view->setModel($model, true);
					$view->display();
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas');
			}
		}else{
			$session =& JFactory::getSession();
			$session->set('citedRecords', array(), 'jresearch');
			$view->setLayout('default');
			$view->setModel($model, true);
			$view->display();
		}		
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing research areas.
	 *
	 */
	function cancel(){
		$id = JRequest::getInt('id');
		$model = &$this->getModel('ResearchArea', 'JResearchModel');		
		
		if($id != null){
			$area = $model->getItem($id);
			if(!$area->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=researchAreas');
	}
	
}
?>
