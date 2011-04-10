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

jresearchimport('tables.member', 'jresearch.admin');
jresearchimport('tables.publication', 'jresearch.admin');


class JHTMLjresearchfrontend
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
			$authorized = self::authorize($task, $controller, $itemid, $userid);

			if($authorized) //Changes by Pablo Moncada
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
		return true;
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
		$itemid = JRequest::getVar('Itemid', null);
		$view = JFilterOutput::stringURLSafe($view);
		$task = JFilterOutput::stringURLSafe($task);
		JFilterOutput::cleanText($text);
		
		$url = "index.php?option=com_jresearch&view=$view&task=$task".((!empty($id))?'&id='.intval($id):'').(($bItemId && !empty($itemid))?'&Itemid='.intval($itemid):'').((count($additional) > 0)?self::_getKeyValueString($additional):'');
		return JFilterOutput::linkXHTMLSafe('<a href="'.$url.'">'.$text.'</a>');
	}
	
	/**
	 * 
	 * Constructs a list of research area links
	 * @param array $researchAreas
	 */
	public static function researchareaslinks($researchAreas, $display = 'list'){
		$itemid = JRequest::getVar('Itemid', null);
		$linksText = '';
		
		if($display != 'list' && $display != 'inline')
			$display = 'list';
		
		foreach($researchAreas as $area){
			if($area->id > 1){
				if($area->published){
					$link = self::link($area->name, 'researcharea', 'show', $area->id);					
					if($display == 'list')
						$linksText .= '<li>'.$link.'</li>';
					else 
						$linksText .= ', '.$link;	
				}else{
					if($display == 'list')
						$linksText .= '<li>'.$area->name.'</li>';
					else
						$linksText .= ', '.$area->name;							
				}
			}
		}
		
		if(empty($linksText)){
			if($display == 'list')
				$linksText .= '<li>'.JText::_('JRESEARCH_UNCATEGORIZED').'</li>';
			else
				$linksText .= JText::_('JRESEARCH_UNCATEGORIZED');	
		}
		
		
		if($display == 'list')	
			$result = '<ul>'.$linksText.'</ul>'; 	
		else
			$result = ltrim(ltrim($linksText, ','));
			
		return $result;	
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
