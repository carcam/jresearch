<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Cooperations
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of cooperations in the backend interface.
*/

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'journal.php');

/**
* JResearch Journal Backend Controller
*
* @package		JResearch
* @subpackage	Cooperations
*/
class JResearchAdminJournalsController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct()
	{
		parent::__construct();

		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.journals');
		
		$this->registerDefaultTask('display');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');
                $this->registerTask('getImpactFactor', 'getImpactFactor');

		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'journals');
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'journals');
	}

	/**
	 * Default method, it shows the control panel for JResearch component.
	 *
	 * @access public
	 */
	function display()
	{
		$view = $this->getView('Journals', 'html', 'JResearchAdminView');
		$model = $this->getModel('Journals', 'JResearchModel');
		
		$view->setModel($model,true);
		$view->display();
	}

	function edit()
	{
		$cid = JRequest::getVar('cid', array());

		$view = $this->getView('Journal', 'html', 'JResearchAdminView');
		$model = $this->getModel('Journal', 'JResearchModel');

		if(!empty($cid)){
			$journal = $model->getItem($cid[0]);
			if(!empty($journal)){
				$user = JFactory::getUser();
				//Check if it is checked out
				if($journal->isCheckedOut($user->get("id")))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=journals', JText::_('You cannot edit this item. Another user has locked it.'));
				}
				else
				{
					$journal->checkout($user->get("id"));
					$view->setModel($model,true);
					$view->display();					
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				$this->setRedirect('index.php?option=com_jresearch&controller=journals');
			}			
		}else{
			$view->setModel($model,true);
			$view->display();
		}
	}

	function publish()
	{
		// Array of ids
		$cid = JRequest::getVar('cid');
		$journal = JTable::getInstance('Journal', 'JResearch');
		$journal->publish($cid, 1);

		$this->setRedirect('index.php?option=com_jresearch&controller=journals', JText::_('The items were successfully published'));
	}

	function unpublish()
	{
		$cid = JRequest::getVar('cid');

		$journal = JTable::getInstance('Journal', 'JResearch');
		$journal->publish($cid, 0);
		
		$this->setRedirect('index.php?option=com_jresearch&controller=journals', JText::_('The items were successfully unpublished'));
	}

	function remove()
	{
		$cid = JRequest::getVar('cid');
		$n = 0;

		$journal = JTable::getInstance('Journal', 'JResearch');
		
		foreach($cid as $id)
		{
			if(!$journal->delete($id))
			{
				JError::raiseWarning(1, JText::sprintf('Journal with id %d could not be deleted', $id));
			}
			else
			{
				$n++;
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=journals', JText::sprintf('%d successfully deleted.', $n));
	}

	function save()
	{
		global $mainframe;
		if(!JRequest::checkToken())
		{
		    $this->setRedirect('index.php?option=com_jresearch');
		    return;
		}
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');
		
		$journal = JTable::getInstance('Journal', 'JResearch');

		// Bind request variables
		$post = JRequest::get('post');

		$journal->bind($post);
		$task = JRequest::getVar('task');
                // Read history
                $historyCount = JRequest::getInt('history_count', 0);
                echo $historyCount;
                for($j = 0; $j < $historyCount; $j++){
                    $entry = array();
                    $entry['year'] = JRequest::getInt('historyyear'.$j, -1);
                    $entry['impact_factor'] = JRequest::getVar('historyfactor'.$j, -1);
                    if($entry['year'] > 0 && $entry['impact_factor'] >= 0){
                        $journal->addHistory($entry);
                    }
                }

		if($journal->check())
		{

			if($journal->store())
			{
				//Specific redirect for specific task
				if($task == 'save')
					$this->setRedirect('index.php?option=com_jresearch&controller=journals', JText::_('The journal was successfully saved.'));
				elseif($task == 'apply')
					$this->setRedirect('index.php?option=com_jresearch&controller=journals&task=edit&cid[]='.$journal->id, JText::_('The journal was successfully saved.'));

				// Trigger event
				$arguments = array('journal', $journal->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);

			}
			else
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_SAVE_FAILED').': '.$journal->getError());								
				$idText = !empty($journal->id) && $task == 'apply'?'&cid[]='.$journal->id:'';
				$this->setRedirect('index.php?option=com_jresearch&controller=journals&task=edit'.$idText);
			}
		}
		else
		{
			$idText = !empty($journal->id) && $task == 'apply'?'&cid[]='.$journal->id:'';			
			for($i=0; $i<count($journal->getErrors()); $i++)
				JError::raiseWarning(1, $journal->getError($i));
			$this->setRedirect('index.php?option=com_jresearch&controller=journals&task=edit'.$idText);
		}

		//Reordering ordering of other cooperations
		$journal->reorder();
		
		//Unlock record
		if(!empty($journal->id)){
			$user = JFactory::getUser();
			if(!$journal->isCheckedOut($user->get('id')))
			{
				if(!$journal->checkin())
					JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
	}

	function cancel()
	{
		$id = JRequest::getInt('id');
		$model = $this->getModel('Journal', 'JResearchModel');

		if($id != null)
		{
			$journal = $model->getItem($id);

			if(!$journal->checkin())
			{
				JError::raiseWarning(1, JText::_('The record could not be unlocked.'));
			}
		}

		$this->setRedirect('index.php?option=com_jresearch&controller=journals');
	}

        function getImpactFactor(){
            $doc = JFactory::getDocument();
            $db = JFactory::getDBO();
            
            $doc->setMimeEncoding('text/plain');
            $journalId = JRequest::getInt('journalId');
            $year = JRequest::getInt('year', 0);

            //Look in the history
            $query = 'SELECT impact_factor FROM '.$db->nameQuote('#__jresearch_journal_history').' WHERE id_journal = '.$db->Quote($journalId)
                   .' AND year <= '.$db->Quote($year).' ORDER BY year DESC';

            $db->setQuery($query);
            $result = $db->loadResult();
            if(empty($result)){
                $db->setQuery('SELECT impact_factor FROM #__jresearch_journals WHERE id = '.$db->Quote($journalId));
                $result = $db->loadResult();
            }

            echo $result;
        }
}
?>
