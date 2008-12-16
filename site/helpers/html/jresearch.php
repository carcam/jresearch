<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	HTML
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JHTMLJResearch
{
	/**
	 * Renders edit icon for specific item, if user is authorized for it
	 *
	 * @param string $controller
	 * @param int $id
	 * @param int $userid
	 */
	public static function edit($controller, $itemid, $userid=null)
	{
		$editAuthorized = false;
		$availableController = array('publications');
		
		if(in_array($controller, $availableController))
		{
			$editAuthorized = JHTMLJResearch::authorize('edit', $controller, $itemid, $userid);
			
			//@todo Add JRESEARCH_NOT_AUTHORIZED and JRESEARCH_CONTROLLER_NOT_AVAILABLE to language file
			if($editAuthorized)
				echo '<img src="" alt="Edit '.$controller.'"/>';
			else
				echo JText::sprintf('JRESEARCH_ACCESS_NOT_ALLOWED', $controller, 'edit', $itemid);
		}
	}
	
	/**
	 * Returns true if user is authorized to do specific task, otherwise false
	 *
	 * @param string $task
	 * @param string $controller
	 * @param int $itemid
	 * @param int $userid
	 * @return bool
	 */
	public static function authorize($task, $controller, $itemid, $userid=null)
	{
		$availableTasks = array('edit','add','remove');
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser($userid);
		$itemid = (int) $itemid;
		
		if($user->guest == 0)
		{
			//If task isn't available, return false
			if(!in_array($task, $availableTasks))
				return false;
				
			//Can do the specific task with "all" rights
			$canDo = ($user->authorize('com_jresearch',$task,$controller,'all') != 0)
						? true 
						: false;
	
			//Can do the specific task with "own" rights
			$canDoOwn = (($user->authorize('com_jresearch',$task,$controller,'own') != 0) 
							&& ($task == 'edit' || $task == 'remove'))
						? true 
						: false;
			
			//I'm able to do specific task?
			if($canDo || $canDoOwn)
			{
				$member = new JResearchMember($db);
				$member->bindFromUser($user->username);
				
				switch($controller)
				{
					case 'publications':
						$pub = new JResearchPublication($db);
						$pub->load($itemid);
						
						$authors = $pub->getAuthors();
						
						foreach($authors as $author)
						{
							//Return true if I'm able to edit all publications or only mine
							if(is_a($author, 'JResearchMember') && ($canDo || ($canDoOwn && ($author->id == $userid))))
							{
								return true;
							}
						}
						break;
					default:
						break;
				}
			}
		}
		
		return false;
	}
	
}
?>