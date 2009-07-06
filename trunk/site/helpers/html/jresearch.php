<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	HTML
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'publication.php');

class JHTMLJresearch
{
	/**
	 * Renders task icon for specific item, if user is authorized for it
	 *
	 * @param string $task
	 * @param string $controller
	 * @param int $id
	 * @param int $userid
	 */
	public static function icon($task, $controller, $itemid=0, $userid=null)
	{
		$authorized = false;
		$availableController = array('publications');
		// Menu ID retention
		$Menuid = JRequest::getVar('Itemid');
		$MenuidText = !empty($Menuid)?'&Itemid='.$Menuid:'';

		$modelKey = JRequest::getVar('modelkey');
		$modelKeyText = !empty($modelKey)?'&modelkey='.$modelKey:'';
		
		if(in_array($controller, $availableController))
		{
			$authorized = JHTMLJResearch::authorize($task, $controller, $itemid, $userid);

			if($authorized)
			{
				switch($controller)
				{
					case 'publications':
						$task = ($task == 'add')?'new':$task;
						return '<a href="index.php?option=com_jresearch&view=publication&task='.$task.(($itemid > 0)?'&id='.$itemid:'').$modelKeyText.$MenuidText.'" title="Edit publication">'
						.(($task == 'new')?JText::_(ucfirst($task)).' ':'').'<img src="'.JURI::base().'/components/com_jresearch/assets/'.$task.'.png" alt="'.ucfirst($task).' '.$controller.' Image"/>'
						.'</a>';
						break;
					default:
						break;
				}
			}
		}
		
		return '';
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
	public static function authorize($task, $controller, $itemid=0, $userid=null)
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
				$member->bindFromUsername($user->username);
				
				switch($controller)
				{
					case 'publications':
						if($itemid > 0)
						{
							$pub = JResearchPublication::getById($itemid);
							
							$authors = $pub->getAuthors();
							
							foreach($authors as $author)
							{
								//Return true if I'm able to edit all publications or only mine
								if(is_a($author, 'JResearchMember'))
								{
									if($canDo || ($canDoOwn && ($author->id == $user->id)) || $pub->created_by == $user->id)
									{
										return true;
									}
									
									//Check teams of author 
									//If user is member of one team of the author, 
									//he will get authorized
									$teams = $author->getTeams();
									
									foreach($teams as $team)
									{
										//If user is member of one team, he is authorized to do the task
										if($team->isMember($user->id))
										{
											return true;
										}
									}
								}
							}
						}
						elseif($itemid <= 0 && $canDo)
						{
							return true;
						}
						break;
					default:
						break;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Creates a frontend link for com_jresearch with view, task, id and itemid parameter
	 *
	 * @param string $view
	 * @param string $task
	 * @param int $id
	 * @param bool $itemId
	 * @param array $additional Key-value pair for additional url parameters
	 */
	public static function link($text, $view='cooperations', $task='display', $id=null, $bItemId = true, array $additional=array())
	{
		$itemid = JRequest::getVar('Itemid');
		$view = JFilterOutput::stringURLSafe($view);
		$task = JFilterOutput::stringURLSafe($task);
		JFilterOutput::cleanText($text);
		
		$url = "index.php?option=com_jresearch&view=$view&task=$task".((!empty($id))?'&id='.intval($id):'').(($bItemId)?'&Itemid='.intval($itemid):'').((count($additional) > 0)?self::_getKeyValueString($additional):'');
		return JFilterOutput::linkXHTMLSafe('<a href="'.$url.'">'.$text.'</a>');
	}
	
	/**
	 * Gets value of array from given key if it exists, otherwise $default
	 */
	private static function getKey($key, array &$arr, $default=null)
	{
		return (array_key_exists($key, $arr)?$arr[$key]:$default);
	}
	
	private static function _getKeyValueString(array $pairs)
	{
		$string = array();
		
		foreach($pairs as $key=>$value)
		{
			$string[] = ((string) JFilterOutput::stringURLSafe($key)).'='.((string) JFilterOutput::stringURLSafe($value));
		}
		
		return implode('&', $string);
	}
}
?>
