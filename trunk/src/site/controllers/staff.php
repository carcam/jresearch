<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of staff members.
*/

jresearchimport('helpers.staff', 'jresearch.admin');

/**
 * JResearch Staff Component Controller
 *
 */
class JResearchStaffController extends JResearchFrontendController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 */
	function __construct(){
		parent::__construct();
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.staff');
		
		// Task for edition of profile
		$this->registerTask('edit', 'edit');
		$this->registerTask('show', 'show');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('autoSuggestMembers', 'autoSuggestMembers');		
		$this->addModelPath(JRESEARCH_COMPONENT_SITE.DS.'models'.DS.'staff');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'staff');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'member');
	}

	/**
	 * Default method, it shows the list of published staff members.
	 *
	 * @access public
	 */
	function display(){
		$layout = JRequest::getVar('layout');
		if($layout == 'edit'){
			$this->edit();
			return;	
		}		
		
        $limitstart = JRequest::getVar('limitstart', null);
        if($limitstart === null)
        	JRequest::setVar('limitstart', 0);

	    $model = $this->getModel('Staff', 'JResearchModel');
        $view = $this->getView('Staff', 'html', 'JResearchView');
        $view->setModel($model, true);
        $view->setLayout($layout);
        $view->display();
	}

	/**
	* Invoked when an authenticated user decides to edit his/her personal profile.
	* 
	* @access public
	*/
	function edit(){
		$user = JFactory::getUser();
		$memberInfo = JResearchStaffHelper::getMemberArrayFromUsername($user->username);		
		if(empty($memberInfo)){
        	JError::raiseWarning(1, JText::_('JRESEARCH_NOT_EXISTING_PROFILE'));
        	return;		
		}
		
		//Rules at staff level
		$canDoStaff = JResearchAccessHelper::getActions();
		if($canDoStaff->get('core.staff.edit.own')){
			$model = $this->getModel('Member', 'JResearchModel');
			$view = $this->getView('Member', 'html', 'JResearchView');				
			$view->setModel($model, true);
			$view->setLayout('edit');
			$view->display();									
		}else{
			JError::raiseWarning(1, JText::_('JRESEARCH_ACCESS_NOT_ALLOWED'));			
		}
	}

	/**
	* Invoked when the visitant has decided to see a member's profile
	*/
	function show(){
		$model = $this->getModel('Member', 'JResearchModel');
		$view = $this->getView('Member', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();				
	}	
	
	/**
	* Invoked when the user has decided to save a profile, by clicking buttons Save or 
	* Apply in the edit profile form.
	*/
	function save(){
        JRequest::checkToken() or jexit( 'JInvalid_Token' );
        $model = $this->getModel('Member', 'JResearchModel');
        $app = JFactory::getApplication();
        $form = JRequest::getVar('jform', array(), '', 'array');        
 
		//Rules at staff level
		$canDoStaff = JResearchAccessHelper::getActions();
		$allowedToContinue = false;
		
		if(empty($form['id'])){
			//check if creation is allowed
			$allowedToContinue = $canDoStaff->get('core.staff.create');
		}else{
			//Check if edition is allowed
			$allowedToContinue = $canDoStaff->get('core.staff.edit.own');
		}
                
		if($allowedToContinue){
			$app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'JResearchMember'));
	        if ($model->save()){
		        $app->triggerEvent('OnAfterSaveJResearchEntity', array($model->getItem(), 'JResearchMember'));        	
       		    $msg = JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED');            
       	    	$type = 'message';
	        }else{
    	        $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
        	    $type = 'error';
        	}
        
	        $app->enqueueMessage($msg, $type);
    	    $this->edit();		
		}else{
			JError::raiseWarning(1, JText::_('JRESEARCH_ACCESS_NOT_ALLOWED'));			
		}
	}
	
	/**
	 * Invoked when the user has decided to cancel frontend edition
	 *
	 */
	function cancel(){
		$user = JFactory::getUser();
		$username = $user->get('username');
		$model = $this->getModel('Member', 'JResearchModel');		
		$data =& $model->getData();
        
        if(!empty($data['id'])){
	        if(!$model->checkin()){
    	    	JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
        	}
        }
		
		$this->setRedirect('index.php?option=com_jresearch&view=staff');		
	}
	
	/**
	 * Returns the information about the members whose names begin with the key
	 * sent as a HTTP parameter.
	 *
	 */
	function autoSuggestMembers(){
		$key = JRequest::getVar('key');
		JHTML::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'html');
		echo JHTML::_('jresearchhtml.jsonMembers', $key);
	}
	
}
?>
