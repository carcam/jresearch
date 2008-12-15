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

jimport('joomla.application.component.controller');

/**
 * JResearch Staff Component Controller
 *
 */
class JResearchStaffController extends JController
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
		$this->registerTask('displayflow', 'displayflow');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'staff');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'staff');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'member');
	}

	/**
	 * Default method, it shows the list of published staff members.
	 *
	 * @access public
	 */

	function display(){
		global $mainframe;
		
		//Layout
		$layout = JRequest::getVar('layout','default');
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$limit = $params->get('staff_entries_per_page');
		JRequest::setVar('limit', $limit);
		$limitstart = JRequest::getVar('limitstart', null);		
		if($limitstart === null)
			JRequest::setVar('limitstart', 0);
			
		
		$model =& $this->getModel('Staff', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('Staff', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->setLayout($layout);
		
		$view->display();
	}

	/**
	* Invoked when an authenticated user decides to edit his/her personal profile.
	* 
	* @access public
	*/
	function edit(){
		$user =& JFactory::getUser();
		if($user->guest){
			JError::raiseWarning(1, JText::_('Access not allowed.'));
			return;
		}	
		
		$model =& $this->getModel('Member', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchAreasList', 'JResearchModel');
		$view =& $this->getView('Member', 'html', 'JResearchView');				
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->setLayout('edit');
		$view->display();						
	}

	/**
	* Invoked when the visitant has decided to see a member's profile
	*/
	function show(){
		$model =& $this->getModel('Member', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('Member', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();				
	}	
	
	/**
	* Invoked when the user has decided to save a profile, by clicking buttons Save or 
	* Apply in the edit profile form.
	*/
	function save(){
		global $mainframe;		
		$task = JRequest::getVar('task');
		
		$db =& JFactory::getDBO();
		$photosFolder = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'members';
		if($mainframe->isAdmin())
			$photosUrl = JURI::base().'components/com_jresearch/assets/members/';
		else
			$photosUrl = JURI::base().'administrator/components/com_jresearch/assets/members/';
		
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
				JError::raiseWarning(1, JText::_('Image format not supported. Please provide images with extension jpg, gif, png'));
			elseif($width > 400 || $height > 400){
				JError::raiseWarning(1, JText::_('The image exceeds maximum size allowed (400x400)'));
			}else{
				// Get extension 
				$extArray = explode('/', $fileArray['type']);				
				$extension = $extArray[1];
				$newName = $newName.'.'.$extension;
				if(!move_uploaded_file($uploadedFile, $newName ))
					JError::raiseWarning(1, JText::_('The photo could not be imported into JResearch space.'));
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
		$member->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$itemText = '';
		if($itemId != null)
			$itemText = '&Itemid='.$itemId;		
		if($member->check()){		
			if($member->store()){
				$itemId = JRequest::getVar('Itemid');

				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&view=staff'.$itemText, JText::_('The profile was successfully saved.'));
				else
					$this->setRedirect('index.php?option=com_jresearch&view=member&task=edit&layout=edit'.$itemText, JText::_('The profile was successfully saved.'));
				// Trigger event
				$arguments = array('member', $member->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);			
			}else{
				$this->setRedirect('index.php?option=com_jresearch&view=member&task=edit&layout=edit'.$itemText, JText::_('The profile could not be saved.').' '.$member->getError());					
			}
		}else{
			JError::raiseWarning(1, $member->getError());
			$this->setRedirect('index.php?option=com_jresearch&view=member&task=edit&layout=edit'.$itemText);					
		}
		// Uncheck element
		$user =& JFactory::getUser();
		if(!$member->isCheckedOut($user->get('id'))){
			if(!$member->checkin())
				JError::raiseWarning(1, JText::_('The record could not be unlocked.'));		
		}		
	}
	
	/**
	 * Invoked when the user has decided to cancel frontend edition
	 *
	 */
	function cancel(){
		$user = JFactory::getUser();
		$username = $user->get('username');
		$model = &$this->getModel('Member', 'JResearchModel');		
		
		if($id != null){
			$member = $model->getByUsername($username);			
			if(!$member->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&view=staff');		
	}
	
}
?>
