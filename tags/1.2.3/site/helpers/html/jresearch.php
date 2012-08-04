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

require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'member.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'publication.php');

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

			if($authorized) //Changes by Pablo Moncada
			{
				switch($controller)
				{
					case 'publications':
						$task = ($task == 'add')?'new':$task;
						$msg = '';
						switch($task){
							case 'new':
								$msg = JText::_('JRESEARCH_NEW_PUBLICATION');
								break;
							case 'edit':
								$msg = JText::_('JRESEARCH_EDIT_PUBLICATION');	
								break;
							case 'remove':
								$msg = JText::_('JRESEARCH_REMOVE');
								break;	
						}
						return '<a href="index.php?option=com_jresearch&view=publication&task='.$task.(($itemid > 0)?'&id='.$itemid:'').$modelKeyText.$MenuidText.'" title="'.$msg.'">'.(($task == 'new')?JText::_(ucfirst($task)).' ':'').'<img src="'.JURI::base().'/components/com_jresearch/assets/'.$task.'.png" alt="'.ucfirst($task).' '.$controller.' Image"/></a>';
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

                                                        if($canDo || $pub->created_by == $user->id)
                                                                return true;
							
							foreach($authors as $author)
							{
								//Return true if I'm able to edit all publications or only mine
								if(is_a($author, 'JResearchMember'))
								{
									if($canDoOwn && $author->username == $user->username)
									{
										return true;
									}
									
									//Check teams of author 
									//If user is a team leader, he can edit all publications from the team
									$teams = $author->getTeams();
									
									foreach($teams as $team)
									{
                                                                            $leader = $team->getLeader();
                                                                            if($team->isMember($user->id) && $leader->username == $user->username)
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
	 * Returns div-container with publication filters, can be activated with given parameter switches
	 *
	 * @param string $layout
	 * @param bool $bTeams
	 * @param bool $bAreas
	 * @param bool $bYear
	 * @param bool $bSearch
	 * @param bool $bType
	 * @param bool $bAuthors
	 * @return string
	 */
	public static function publicationfilter($layout, $bTeams = true, $bAreas = true, $bYear = true, $bSearch = true, $bType = true, $bAuthors = true)
	{
		global $mainframe;
		
		$lists = array();
		$layout = JFilterInput::clean($layout);
		$js = 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit()"';
		
		if($bSearch === true)
        {
    		$filter_search = $mainframe->getUserStateFromRequest($layout.'publicationsfilter_search', 'filter_search');
     		$lists['search'] = JText::_('Filter').': <input type="text" name="filter_search" id="filter_search" value="'.$filter_search.'" class="text_area" onchange="document.adminForm.submit();" />
								<button onclick="document.adminForm.submit();">'.JText::_('Go').'</button> <button onclick="document.adminForm.filter_search.value=\'\';document.adminForm.submit();">'
								.JText::_('Reset').'</button>';
    	}
    	
		if($bType === true)
    	{
    		// Publication type filter
    		$typesHTML = array();
    		
			$filter_pubtype = $mainframe->getUserStateFromRequest($layout.'publicationsfilter_pubtype', 'filter_pubtype');    		
			$types = JResearchPublication::getPublicationsSubtypes();
			
			$typesHTML[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_PUBLICATION_TYPE'));
			foreach($types as $type)
			{
				$typesHTML[] = JHTML::_('select.option', $type, JText::_('JRESEARCH_'.strtoupper($type)));
			}
			$lists['pubtypes'] = JHTML::_('select.genericlist', $typesHTML, 'filter_pubtype', 'class="inputbox" size="1" '.$js, 'value','text', $filter_pubtype);
    	}
    	
		if($bYear === true)
    	{
			// Year filter
			$yearsHTML = array();
			$db = &JFactory::getDBO();
			
			$filter_year = $mainframe->getUserStateFromRequest($layout.'publicationsfilter_year', 'filter_year');			
			
			$db->setQuery('SELECT DISTINCT year FROM '.$db->nameQuote('#__jresearch_publication').' ORDER BY '.$db->nameQuote('year').' DESC ');
			$years = $db->loadResultArray();
			
			$yearsHTML[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_YEAR'));
			foreach($years as $y)
			{
				$yearsHTML[] = JHTML::_('select.option', $y, $y);
			}
				
			$lists['years'] = JHTML::_('select.genericlist', $yearsHTML, 'filter_year', 'class="inputbox" size="1" '.$js, 'value','text', $filter_year);
    	}
    	
    	if($bAuthors === true)
    	{
    		JModel::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'publications');
    		
			$authorsHTML = array();
			$model = JModel::getInstance('PublicationsList', 'JResearchModel');
    		$filter_author = $mainframe->getUserStateFromRequest($layout.'publicationsfilter_author', 'filter_author');
			$authors = $model->getAllAuthors();

			$authorsHTML[] = JHTML::_('select.option', 0, JText::_('JRESEARCH_AUTHORS'));	
			foreach($authors as $auth)
			{
				$authorsHTML[] = JHTML::_('select.option', $auth['id'], $auth['name']); 
			}
			$lists['authors'] = JHTML::_('select.genericlist', $authorsHTML, 'filter_author', 'class="inputbox" size="1" '.$js, 'value','text', $filter_author);    		
    	}
		
		if($bTeams === true)
		{
			JModel::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'teams');
			
			//Team filter
			$teamsOptions = array();  
			$teamsModel = JModel::getInstance('Teams', 'JResearchModel');
	    	$filter_team = $mainframe->getUserStateFromRequest($layout.'publicationsfilter_team', 'filter_team');    		
    		$teams = $teamsModel->getData();
        	      
	        $teamsOptions[] = JHTML::_('select.option', -1 ,JText::_('JRESEARCH_ALL_TEAMS'));
	        foreach($teams as $t)
	        {
	    		$teamsOptions[] = JHTML::_('select.option', $t->id, $t->name);
	    	}    		
	    	$lists['teams'] = JHTML::_('select.genericlist',  $teamsOptions, 'filter_team', 'class="inputbox" size="1" '.$js, 'value', 'text', $filter_team );
    	}
    	
    	if($bAreas === true)
    	{
    		JModel::addIncludePath(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'researchareas');
    		
    		//Researchareas filter
    		$areasOptions = array();
    		$areasModel = JModel::getInstance('Researchareaslist', 'JResearchModel');
    		
			$filter_area = $mainframe->getUserStateFromRequest($layout.'publicationsfilter_area', 'filter_area');    		
    		$areas = $areasModel->getData();        
	        $areasOptions[] = JHTML::_('select.option', 0 ,JText::_('JRESEARCH_RESEARCH_AREAS'));
	        foreach($areas as $a)
	        {
	    		$areasOptions[] = JHTML::_('select.option', $a->id, $a->name);
	    	}    		
	    	$lists['areas'] = JHTML::_('select.genericlist',  $areasOptions, 'filter_area', 'class="inputbox" size="1" '.$js, 'value', 'text', $filter_area );
    	}
    	
    	return '<div style="float: left">'.implode('</div><div style="float: left;">', $lists).'</div>';
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
