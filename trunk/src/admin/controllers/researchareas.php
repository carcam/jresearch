<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	researchareas
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of research areas in the backend interface.
*/

jresearchimport('joomla.application.component.controller');

/**
 * Research Areas Backend Controller
 * @package		JResearch
 * @subpackage	researchareas
 */
class JResearchAdminResearchareasController extends JController
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
		$this->addModelPath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'researchareas');
		
	}

	/**
	* Invoked when saving the information about a research area.
	*/	
	function save(){
             $model = $this->getModel('Researcharea', 'JResearchAdminModel');
             $app = JFactory::getApplication();
             if ($model->save()){
                $task = JRequest::getVar('task');
                $area = $model->getItem();
                if($task == 'save'){
                    $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::_('JRESEARCH_AREA_SUCCESSFULLY_SAVED'));
                    $app->setUserState('com_jresearch.edit.researcharea.data', array());
                }elseif($task == 'apply'){
                    $this->setRedirect('index.php?option=com_jresearch&controller=researchareas&task=edit&cid[]='.$area->id, JText::_('JRESEARCH_AREA_SUCCESSFULLY_SAVED'));
             	}
             }else{
                $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
                $type = 'error';
                $app = JFactory::getApplication();
                $app->enqueueMessage($msg, $type);
                $view = $this->getView('Researcharea','html', 'JResearchAdminView');
                $view->setModel($model, true);
				$view->setLayout('default');
                $view->display();
            }
            
            return true;
	}

	/**
	 * Default method, it shows the list of research areas in an administration list.
	 *
	 * @access public
	 */

	function display(){
            $view = $this->getView('researchareas', 'html', 'JResearchAdminView');
            $model = $this->getModel('researchareas', 'JResearchAdminModel');
            $view->setModel($model, true);
            $view->setLayout('default');
            $view->display();
	}

	/**
	* Invoked when the user has published a set of research areas items.
	*/	
	function publish(){		
            $model = $this->getModel('Researcharea', 'JResearchAdminModel');
            if(!$model->publish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
            }
            $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
	}
	
		/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){		
            $model = $this->getModel('Researcharea', 'JResearchAdminModel');
            if(!$model->unpublish()){
                JError::raiseWarning(1, JText::_('JRESEARCH_PUBLISHED_FAILED').': '.implode('<br />', $model->getErrors()));
            }
            $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::_('JRESEARCH_AREAS_UNPUBLISHED_SUCCESSFULLY'));
	}
	
	/**
	* Invoked when an administrator has decided to remove one or more items.
	* @access	public
	*/ 
	function remove(){
			if(!JRequest::checkToken()){
                $this->setRedirect('index.php?option=com_jresearch');
                return;
            }
	
            $model = $this->getModel('Researcharea', 'JResearchAdminModel');
            $n = $model->delete();
            $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::sprintf('JRESEARCH_AREA_SUCCESSFULLY_DELETED', $n));
	}


	/**
	* Invoked when the administrator has decided to edit/create a research area.
	*
	* @access public
	*/
	function edit(){
            $cid = JRequest::getVar('cid');
            $view = $this->getView('ResearchArea', 'html', 'JResearchAdminView');
            $model = $this->getModel('ResearchArea', 'JResearchAdminModel');
				
            if($cid){
                $area = $model->getItem();
                if(!empty($area->id)){
                    $user = JFactory::getUser();
                    // Verify if it is checked out
                    if($area->isCheckedOut($user->get('id'))){
                        $this->setRedirect('index.php?option=com_jresearch&controller=researchareas', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
                    }else{
                        $area->checkout($user->get('id'));
                        $view->setLayout('default');
                        $view->setModel($model, true);
                        $view->display();
                    }
                }else{
                    JError::raiseError(404, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                    $this->setRedirect('index.php?option=com_jresearch&controller=researchareas');
                }
            }else{
                $session = JFactory::getSession();
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
            $model = $this->getModel('Researcharea', 'JResearchAdminModel');
            $app = JFactory::getApplication();
            if(!$model->checkin()){            	
                JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
            }
            
            $app->setUserState('com_jresearch.edit.researcharea.data', array());
            $this->setRedirect('index.php?option=com_jresearch&controller=researchareas');
	}

        	/**
	* Save the item(s) to the menu selected
	*/
	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('JInvalid_Token');

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0])
		{
			$id = $cid[0];
		}
		else
		{
			$this->setRedirect( 'index.php?option=com_jresearch&controller=researchareas', JText::_('No Items Selected') );
			return false;
		}

		$model = $this->getModel('Researcharea', 'JResearchAdminModel');

		if ($model->orderItem($id, -1))
		{
			$msg = JText::_( 'Member Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}

		$this->setRedirect( 'index.php?option=com_jresearch&controller=researchareas', $msg );
	}

	/**
	* Save the item(s) to the menu selected
	*/
	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('JInvalid_Token');

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0])
		{
			$id = $cid[0];
		}
		else
		{
			$this->setRedirect( 'index.php?option=com_jresearch&controller=researchareas', JText::_('No Items Selected') );
			return false;
		}

		$model = $this->getModel('Researcharea', 'JResearchAdminModel');
		if ($model->orderItem($id, 1))
		{
			$msg = JText::_( 'Item Moved Up' );
		}
		else
		{
			$msg = $model->getError();
		}

		$this->setRedirect( 'index.php?option=com_jresearch&controller=researchareas', $msg );
	}

	/**
	* Save the item(s) to the menu selected
	*/
	function saveorder()
	{
            // Check for request forgeries
            JRequest::checkToken() or jexit( 'Invalid Token' );

            $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
            JArrayHelper::toInteger($cid);

            $model = $this->getModel('Researcharea', 'JResearchAdminModel');

            if ($model->setOrder($cid))
            {
                    $msg = JText::_( 'New ordering saved' );
            }
            else
            {
                    $msg = $model->getError();
            }

            $this->setRedirect( 'index.php?option=com_jresearch&controller=researchareas', $msg );
	}

	
}
?>
