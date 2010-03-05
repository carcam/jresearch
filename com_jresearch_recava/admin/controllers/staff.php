<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of staff members in the backend interface.
*/

define('_MEMBER_IMAGE_MAX_WIDTH_', 1024);
define('_MEMBER_IMAGE_MAX_HEIGHT_', 768);

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
/**
 * Staff Backend Controller
 * @package		JResearch
 * @subpackage	Staff
 */
class JResearchAdminStaffController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.staff');
		
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
		$this->registerTask('autoSuggestMembers', 'autoSuggestMembers');
		$this->registerTask('getTeam', 'getTeam');				
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
			
			if(!empty($member)){
				if($member->isCheckedOut($user->get('id'))){
					$this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
				}else{	
					$member->checkout($user->get('id'));
					$view->setModel($model, true);
					$view->setModel($researchAreaModel);
					$view->display();
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=staff');				
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
	    if(!JRequest::checkToken())
		{
		    $this->setRedirect('index.php?option=com_jresearch');
		    return;
		}
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');
		
		$db =& JFactory::getDBO();
		$member = new JResearchMember($db);

		// Bind request variables to publication attributes	
		$post = JRequest::get('post');

		$member->bind($post);	
		$member->firstname = trim($member->firstname);
		$member->lastname = trim($member->lastname);
		
		$member->former_member = (int) JRequest::getVar('former_member', '0', 'post', 'string');
		$member->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		//Upload photo
		$fileArr = JRequest::getVar('inputfile', null, 'FILES');
		$delete = JRequest::getVar('delete');
		
		JResearch::uploadImage(	$member->url_photo, 	//Image string to save
								$fileArr, 			//Uploaded File array
								'assets'.DS.'members'.DS, //Relative path from administrator folder of the component
								($delete == 'on')?true:false,	//Delete?
								 _MEMBER_IMAGE_MAX_WIDTH_, //Max Width
								 _MEMBER_IMAGE_MAX_HEIGHT_ //Max Height
		);
		
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
			for($i=0; $i<count($member->getErrors()); $i++)
				JError::raiseWarning(1, $member->getError($i));
				
			$this->setRedirect('index.php?option=com_jresearch&controller=staff&task=edit&cid[]='.$member->id);					
		}
		
		//Reordering other members
		$member->reorder();
		if(!empty($member->id)){
			$user =& JFactory::getUser();
			if(!$member->isCheckedOut($user->get('id'))){
				if(!$member->checkin())
					JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
			}
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
	
	/**
	 * Returns the information about the members whose names begin with the key
	 * sent as a HTTP parameter.
	 *
	 */
	function autoSuggestMembers(){
		$key = JRequest::getVar('key');
		JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		echo JHTML::_('AuthorsSelector.jsonMembers', $key);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	function getTeam(){
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('text/xml');
		$index = JRequest::getInt('index');
		$value = JRequest::getVar('value');
		$team = null;
		$teamId = -1;
		
		if(is_numeric($value)){
			//It is a J!Research member
			$member = JTable::getInstance('Member', 'JResearch');
			$member->load((int)$value);
			$team = $member->getTeam();
		}else{
			//It is a string
			$team = JResearchMember::getTeamByAuthorName($value);
		}
		
		if(!empty($team))
			$teamId = $team->id;
		
		$writer = new XMLWriter;
		$writer->openMemory();
		$writer->startDocument('1.0');
		
		$writer->startElement("result");
		$writer->writeElement('index', $index);
		$writer->writeElement('value', $teamId);
		$writer->endElement();											
		$output = $writer->outputMemory();
		echo $output;				
				
	}
	
}
?>
