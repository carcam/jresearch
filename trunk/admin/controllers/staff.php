<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of staff members in the backend interface.
*/

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
/**
 * Staff Backend Controller
 *
 * @package		JResearch
 */
class JResearchAdminStaffController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		// Task for edition of profile
		$this->registerTask('add', 'add');
		$this->registerTask('import', 'import');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'staff');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'staff');
		
	}

	/**
	 * Default method, it shows the list of staff members in the administration style.
	 *
	 * @access public
	 */

	function display(){
		$view = &$this->getView('Staff', 'html', 'JResearchAdminView');
		$model = &$this->getModel('Staff', 'JResearchModel');
		$areaModel = &$this->getModel('ResearchArea', 'JResearchModel');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->setLayout('default');
		$view->display();
	}

	/**
	* Invoked when the user has decided to add staff members. JResearch requires staff members
	* to be Joomla users, so this method will show a form to import members from Joomla users
	* table.
	*/	
	function add(){
		$view = &$this->getView('Staff', 'html', 'JResearchAdminView');
		$view->setLayout('add');
		$view->display();

	}

	/**
	* Invoked when an administrator decides to create/edit a record.
	* 
	* @access public
	*/
	function edit(){
		$cid = JRequest::getVar('cid');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$user = JFactory::getUser();

		$view = &$this->getView('Member', 'html', 'JResearchAdminView');	
		$researchAreaModel = &$this->getModel('ResearchAreasList', 'JResearchModel');
		$model = &$this->getModel('Member', 'JResearchModel');		
		$view->setLayout('default');
		
		if($cid){
			$member = $model->getItem($cid[0]);
			if($member->isCheckedOut($user->get('id'))){
				$this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
			}else{	
				$member->checkout($user->get('id'));
				$view->setModel($model, true);
				$view->setModel($researchAreaModel);
				$view->display();
			}
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
		
		$member = new JResearchMember($db);
		$member->publish($cid, 1);
		$this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));

	}

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
			// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		
		$member = new JResearchMember($db);
		$member->publish($cid, 0);
		$this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;
		
		$member = new JResearchMember($db);
		foreach($cid as $id){
			if(!$member->delete($id)){
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_MEMBER_NOT_DELETED', $id));
			}else{
				$n++;
			}
		}
		$this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::sprintf('JRESEARCH_SUCCESSFULLY_DELETED', $n));
	}
	
	/**
	* Invoked when and administrator, clicks the button "Save" in the form for
	* importing members from Joomla users table.
	*/
	function import(){

		$db =& JFactory::getDBO();
		
		// Get the maximum index for members 
		$n = JRequest::getInt('staffCount');
		$count = 0;
		
		for($i=0; $i<= $n; $i++){
			$username = JRequest::getVar('member'.$i);
			if($username !== null){
				$newMember = new JResearchMember($db);
				$newMember->bindFromUser($username);
				if($newMember->store())
					$count++;
				else
					JError::raiseWarning(1, JText::sprintf('JRESEARCH_USER_IMPORTED_FAILED', $username));	
			}
		}
		$this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::sprintf('JRESEARCH_USER_IMPORTED_SUCCESSFULLY', $count));
	}
	
	/**
	* Invoked when the user has decided to save a profile, by clicking buttons Save or 
	* Apply in the edit profile form.
	*/
	function save(){
		global $mainframe;
		$db =& JFactory::getDBO();
		$photosFolder = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'members';
		$photosUrl = JURI::base().'components/com_jresearch/assets/members/';
		$member = new JResearchMember($db);

		// Bind request variables to publication attributes	
		$post = JRequest::get('post');
		$fileArray = JRequest::getVar('inputfile', null, 'FILES');
		$uploadedFile = $fileArray['tmp_name'];		
		$delete = JRequest::getVar('delete');
			
		if($delete == 'on')
			$member->url_photo = '';
		
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
				if(!move_uploaded_file($uploadedFile, $newName ))
					JError::raiseWarning(1, JText::_('JRESEARCH_PHOTO_NOT_UPLOADED'));
				else{
					if($member->url_photo)
						@unlink($member->url_photo);
					$member->url_photo = $photosUrl.basename($newName);
				}
			}		
		}
		
		$member->bind($post);	
		$member->firstname = trim($member->firstname);
		$member->lastname = trim($member->lastname);
		
		$member->former_member = (int) JRequest::getVar('former_member', '0', 'post', 'string');
		$member->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		
		if($member->check()){		
			if($member->store()){
				$task = JRequest::getVar('task');
				if($task == 'save' )
					$this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_MEMBER_SUCCESSFULLY_SAVED'));
				elseif($task == 'apply') 
					$this->setRedirect('index.php?option=com_jresearch&controller=staff&task=edit&cid[]='.$member->id, JText::_('JRESEARCH_MEMBER_SUCCESSFULLY_SAVED'));				
				
				// Trigger event
				$arguments = array('member', $member->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);
			
			}else{
				$this->setRedirect('index.php?option=com_jresearch&controller=staff&task=edit&cid[]='.$member->id, JText::_('JRESEARCH_SAVE_FAILED').' '.$member->getError());					
			}
		}else{
			JError::raiseWarning(1, $member->getError());
			$this->setRedirect('index.php?option=com_jresearch&controller=staff&task=edit&cid[]='.$member->id);					
		}
		
		$user =& JFactory::getUser();
		if(!$member->isCheckedOut($user->get('id'))){
			if(!$member->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
		}
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing 
	 * profiles.
	 *
	 */
	function cancel(){
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Member', 'JResearchModel');		
		
		if($id != null){
			$member = $model->getItem($id);			
			if(!$member->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=staff');
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
			$this->setRedirect( 'index.php?option=com_jresearch&controller=staff', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel('Staff', 'JResearchModel');
		
		if ($model->orderItem($id, -1))
		{
			$msg = JText::_( 'Member Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=staff', $msg );
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
			$this->setRedirect( 'index.php?option=com_jresearch&controller=staff', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel('Staff', 'JResearchModel');
		if ($model->orderItem($id, 1))
		{
			$msg = JText::_( 'Member Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=staff', $msg );
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

		$model =& $this->getModel('Staff', 'JResearchModel');
		
		if ($model->setOrder($cid))
		{
			$msg = JText::_( 'New ordering saved' );
		}
		else
		{
			$msg = $model->getError();
		}
		
		$this->setRedirect( 'index.php?option=com_jresearch&controller=staff', $msg );
	}
}
?>
