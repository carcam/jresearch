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
		$this->registerTask('displayflow', 'displayflow');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('autoSuggestMembers', 'autoSuggestMembers');		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'staff');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'member_position');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'staff');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'member');
		
		$this->addPathwayItem(JText::_('JRESEARCH_STAFF'), 'index.php?option=com_jresearch&view=staff');
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
            $positionsModel = $this->getModel('member_positionList', 'JResearchModel');
            $view =& $this->getView('Staff', 'html', 'JResearchView');
            $view->setModel($model, true);
            $view->setModel($areaModel);
            $view->setLayout($layout);
            $view->setModel($positionsModel);

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
			JError::raiseWarning(1, JText::_('JRESEARCH_ACCESS_NOT_ALLOWED'));
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
            if(!JRequest::checkToken())
            {
                $this->setRedirect('index.php?option=com_jresearch');
                return;
            }
            
            require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');

            $task = JRequest::getVar('task');

            $db =& JFactory::getDBO();

            $params = JComponentHelper::getParams('com_jresearch');
            $imageWidth = $params->get('member_image_width', _MEMBER_IMAGE_MAX_WIDTH_);
            $imageHeight = $params->get('member_image_height', _MEMBER_IMAGE_MAX_HEIGHT_);

            $member = JTable::getInstance('Table', 'JResearchMember');

            // Bind request variables to publication attributes
            $post = JRequest::get('post');

            $member->bind($post);
            $member->firstname = trim($member->firstname);
            $member->lastname = trim($member->lastname);
            $member->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

            //Image upload
            $fileArray = JRequest::getVar('inputfile', null, 'FILES');
            $delete = JRequest::getVar('delete');

            JResearch::uploadImage(	$member->url_photo, 	//Image string to save
                                                            $fileArray, 			//Uploaded File array
                                                            'assets'.DS.'members'.DS, //Relative path from administrator folder of the component
                                                            ($delete == 'on')?true:false,	//Delete?
                                                             $imageWidth, //Max Width
                                                             $imageHeight //Max Height
            );

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
	
	/**
	 * Returns the information about the members whose names begin with the key
	 * sent as a HTTP parameter.
	 *
	 */
	function autoSuggestMembers(){
		$key = JRequest::getVar('key');
		JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'html');
		echo JHTML::_('jresearchhtml.jsonMembers', $key);
	}
	
}
?>
