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

jimport('joomla.application.component.controller');
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
		$this->registerTask('add', 'edit');
		$this->registerTask('import', 'import');
		$this->registerTask('doimport', 'doimport');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('autoSuggestMembers', 'autoSuggestMembers');
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'staff');
		$this->addViewPath(JRESEARCH_COMPONENT_ADMIN.DS.'views'.DS.'staff');
		
	}

	/**
	 * Default method, it shows the list of staff members in the administration style.
	 *
	 * @access public
	 */

	function display(){
            $view = $this->getView('Staff', 'html', 'JResearchAdminView');
            $model = $this->getModel('Staff', 'JResearchAdminModel');
            $view->setModel($model, true);
            $view->setLayout('default');
            $view->display();
	}

	/**
	* Invoked when the user has decided to add staff members. JResearch requires staff members
	* to be Joomla users, so this method will show a form to import members from Joomla users
	* table.
	*/	
	function import(){
            JRequest::checkToken() or jexit( 'JInvalid_Token' );
            $view = $this->getView('Staff', 'html', 'JResearchAdminView');
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
            $user = JFactory::getUser();

            $view = $this->getView('Member', 'html', 'JResearchAdminView');
            $model = $this->getModel('Member', 'JResearchAdminModel');
            $view->setLayout('default');

            if($cid){
                $member = $model->getItem();
                if(!empty($member)){
                    if($member->isCheckedOut($user->get('id'))){
                        $this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
                    }else{
                        $member->checkout($user->get('id'));
                        $view->setModel($model, true);
                        $view->display();
                    }
                }else{
                    JError::raiseWarning(404, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                    $this->setRedirect('index.php?option=com_jresearch&controller=staff');
                }
            }
	}
	
	/**
	* Invoked when an administrator has decided to publish a one or more items
	* @access	public
	*/ 
	function publish(){
            JRequest::checkToken() or jexit( 'JInvalid_Token' );
            $model = $this->getModel('Member', 'JResearchAdminModel');
            if(!$model->publish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
            }
            $this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
	}

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
            JRequest::checkToken() or jexit( 'JInvalid_Token' );
            $model = $this->getModel('Member', 'JResearchAdminModel');
            if(!$model->unpublish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
            }
            $this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY'));
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
            JRequest::checkToken() or jexit( 'JInvalid_Token' );
            $model = $this->getModel('Researcharea', 'JResearchAdminModel');
            $n = $model->delete();
            $this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::sprintf('JRESEARCH_SUCCESSFULLY_DELETED', $n));
	}
	
	/**
	* Invoked when and administrator, clicks the button "Save" in the form for
	* importing members from Joomla users table.
	*/
	function doimport(){
            // Get the maximum index for members
            JRequest::checkToken() or jexit( 'JInvalid_Token' );
            $n = JRequest::getInt('staffCount');
            $count = 0;

            for($i=0; $i<= $n; $i++){
                    $username = JRequest::getVar('member'.$i);
                    if($username !== null){
                        JTable::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'tables');
                        $newMember = JTable::getInstance('Member', 'JResearch');
                        $newMember->ordering = $i;
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
            JRequest::checkToken() or jexit( 'JInvalid_Token' );
            $model = $this->getModel('Member', 'JResearchAdminModel');
            $app = JFactory::getApplication();
            if ($model->save()){
               $task = JRequest::getVar('task');
               $area = $model->getItem();
               if($task == 'save'){
                   $this->setRedirect('index.php?option=com_jresearch&controller=staff', JText::_('JRESEARCH_MEMBER_SUCCESSFULLY_SAVED'));
                   $app->setUserState('com_jresearch.edit.member.data', array());
                }elseif($task == 'apply')
                   $this->setRedirect('index.php?option=com_jresearch&controller=staff&task=edit&cid[]='.$area->id, JText::_('JRESEARCH_MEMBER_SUCCESSFULLY_SAVED'));
            }else{
               $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
               $type = 'error';
               $app = & JFactory::getApplication();
               $app->enqueueMessage($msg, $type);
               $view = &$this->getView('Member','html', 'JResearchAdminView');
               $view->setModel($model, true);
               $view->setLayout('default');
               $view->display();
            }

            return true;
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing 
	 * profiles.
	 *
	 */
	function cancel(){
            JRequest::checkToken() or jexit( 'JInvalid_Token' );
            $model = $this->getModel('Member', 'JResearchAdminModel');
            if(!$model->checkin()){
                JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
            }
            $this->setRedirect('index.php?option=com_jresearch&controller=researchAreas');
	}

	/**
	* Save the item(s) to the menu selected
	*/
	function orderup()
	{
            // Check for request forgeries
            JRequest::checkToken() or jexit( 'JInvalid_Token' );

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

            $model = $this->getModel('Staff', 'JResearchAdminModel');

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
            JRequest::checkToken() or jexit( 'JInvalid_Token' );

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

            $model = $this->getModel('Staff', 'JResearchAdminModel');
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
            JRequest::checkToken() or jexit( 'JInvalid_Token' );

            $cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
            JArrayHelper::toInteger($cid);

            $model = $this->getModel('Staff', 'JResearchAdminModel');

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
            JHTML::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'html');
            echo JHTML::_('jresearchhtml.jsonMembers', $key);
	}
	
}
?>
