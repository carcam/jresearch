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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'language.php');

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
						echo '<a href="index.php?option=com_jresearch&view=publication&task='.$task.(($itemid > 0)?'&id='.$itemid:'').$modelKeyText.$MenuidText.'" title="Edit publication">'
						.(($task == 'new')?JText::_(ucfirst($task)).' ':'').'<img src="'.JURI::base().'/components/com_jresearch/assets/'.$task.'.png" alt="'.ucfirst($task).' '.$controller.' Image"/>'
						.'</a>';
						break;
					default:
						break;
				}
			}
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
							$pub = new JResearchPublication($db);
							$pub->load($itemid);
							
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
	* Renders the control. It allows to select authors from staff members. For external
	* authors it is possible to use simple text fields.
	*
	* @param string $baseName Base name of the control. It represents the name of the fields that
	* will be sent when submitting the form. For internal authors it will be a list. For external 
	* ones, it will be a text input.
	* @param array Mixed sorted array. JResearchMember instances will be considered as internal 
	* staff member's while strings are considered as external authors names.
	* @param boolean $allowPrincipals If true, a checkbox will be displayed for each entry to define
	* if the member is a principle investigator in the activity.
	* @param boolean $isPrincipal Array with the flags indicating if every author stored in $values
	* is a principal author.
	*/
	static function authorsSelector($baseName, $values = null, $allowPrincipals=false, $isPrincipalsArray=null){
		global $mainframe;
		$doc =& JFactory::getDocument();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($url."components/com_jresearch/helpers/html/authorsselector.js");
		
		$result = self::_internalAuthor(null, 'sample', true, $allowPrincipals, false);
		$result .= '<div id="'.$baseName.'" >';
		$j = 0;
		
		if(empty($values)){
			$result .= self::_externalAuthor('', $baseName.$j, false, $allowPrincipals, false);
			$j++;
		}else{
			foreach($values as $value){
				if($value instanceof JResearchMember){
					// Staff id
					if($j > 0)
						$result .= self::_internalAuthor($value, $baseName.$j, true, $allowPrincipals, $isPrincipalsArray[$j]);	
					else
						$result .= self::_internalAuthor($value, $baseName.$j, false, $allowPrincipals, $isPrincipalsArray[$j]);	
				}elseif(is_string($value)){
					// Author name
					if($j > 0)
						$result .= self::_externalAuthor($value, $baseName.$j, true, $allowPrincipals, $isPrincipalsArray[$j]);
					else
						$result .= self::_externalAuthor($value, $baseName.$j, false, $allowPrincipals, $isPrincipalsArray[$j]);	
				}
				$result .= '<br />';
				$j++;
			}
		}
		$result .= self::_fetchHiddenField($j - 1, "max".$baseName);
		$result .= '</div>';
		$result .= '<div>'.self::_fetchAddControl($baseName.($j-1), "max".$baseName, $baseName, $allowPrincipals).'</div>';
		return $result;
		
	}
	
	/**
	* Renders the control used for entering external authors. It includes a text field
	* and two links: one for adding a new author and the other to delete the current one.
	*
	* @param $authorName Value of the text field
	* @param $controlName Name of the control (used for name and id HTML attributes)
	* @param $deleteLink If true, the link for deleting the current author is rendered.
	* @param boolean $allowPrincipals If true, a checkbox will be displayed for each entry to define
	* if the member is a principle investigator in the activity.
	* @param boolean $isPrincipal If true, the author is marked as principal investigator in the activity
	* is assigned to. This parameter makes sense if $allowPrincipals = true. 
	* @return string 
	*/
	private static function _externalAuthor($authorName, $controlName, $deleteLink=true, $allowPrincipals, $isPrincipal){
		// Value is a mixed array	
		$select = JText::_('JRESEARCH_SELECT_FROM_STAFF');
		$enterName = JText::_('JRESEARCH_ENTER_NAME');
		$authorText = JText::_('JRESEARCH_ADD_AUTHOR');
		$deleteText = JText::_('Delete');
		$js = "javascript:switchType('$controlName', '$select', '$enterName')";
		$result  = "<div id=\"div_$controlName\"><input type=\"text\" $class name=\"$controlName\" id=\"$controlName\" value=\"$authorName\" size=\"15\" maxlength=\"255\" />";
		$result .= "&nbsp;";
		$result .= "<a href=\"$js\" id=\"a_$controlName\">$select</a>";
		
		$result .= "<br />";
		if($deleteLink)	
			$result .= self::_fetchDeleteControl($controlName);
			
		if($allowPrincipals){
			$checked = $isPrincipal?'checked="checked"':'';
			$principalMember = JText::_('JRESEARCH_PROJECT_LEADER');
			$principalCheck = "&nbsp;&nbsp;$principalMember: <input type=\"checkbox\" name=\"check_$controlName\" id=\"check_$controlName\" $checked >";		
			$result .= $principalCheck;
		}
					
		$result .= '</div>';
		return $result;

	}
	
	/**
	* Renders the control used for selecting internal authors. It includes a list
	* with published staff members.
	*
	* @param $author JResearchMember instance
	* @param $controlName HTML name of the rendered control.
	* @param $addLink If true, the link for adding new authors is rendered.
	* @param $deleteLink If true, the link for deleting the current author is rendered.
	* @param boolean $allowPrincipals If true, a checkbox will be displayed for each entry to define
	* if the member is a principle investigator in the activity. 
	* @param boolean $isPrincipal If true, the author is marked as principal investigator in the activity
	* is assigned to. This parameter makes sense if $allowPrincipals = true.
	* @return string HTML text of the control.
	*/
	private static function _internalAuthor($author, $controlName, $deleteLink=true, $allowPrincipals, $isPrincipal){
		$db =& JFactory::getDBO();
		$enterName = JText::_('JRESEARCH_ENTER_NAME');
		$select = JText::_('JRESEARCH_SELECT_FROM_STAFF');
		$js = "javascript:switchType('$controlName', '$select', '$enterName')";

		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('published').'='.$db->Quote('1').' ORDER BY lastname';
		$db->setQuery($query);
		$result = $db->loadAssocList();
		$options = array();
		
		foreach($result as $r){
			$options[] = JHTML::_('select.option', $r['id'], $r['lastname'].' '.$r['firstname']);
		}
		
		$list = JHTML::_('select.genericlist', $options, $controlName, 'class="inputbox"', 'value', 'text', $author->id);
		if($deleteLink)	
			$deleteControl = self::_fetchDeleteControl($controlName);

		if($allowPrincipals){	
			$principalMember = JText::_('JRESEARCH_PROJECT_LEADER');			
			$checked = $isPrincipal?'checked="checked"':'';
			$principalCheck = "$principalMember: <input type=\"checkbox\" name=\"check_$controlName\" id=\"check_$controlName\" $checked >";	
		}else{
			$principalCheck = '';
		}
		
		$a = "<a href=\"$js\" id=\"a_$controlName\" >$enterName</a>";

		return "<div id=\"div_$controlName\">$list&nbsp;&nbsp;$a<br />$deleteControl&nbsp;&nbsp;$principalCheck</div>";
	}
	
	/**
	* Renders a link to add an additional input for a new author.
	* @param $controlName HTML name tag of the control the link is next to.
	* @return string HTML text
	*/
	private static function _fetchAddControl($controlName, $maxName, $basename, $allowPrincipals){
		$enterName = JText::_('JRESEARCH_ENTER_NAME');
		$text = JText::_('Add');			
		$deleteText = JText::_('Delete');
		$select = JText::_('JRESEARCH_SELECT_FROM_STAFF');
		$principalMember = JText::_('JRESEARCH_PROJECT_LEADER');
		$result =  '<a id="add'.$basename.'" href="javascript:addControl(\''.$controlName.'\', \''.$text.'\', \''.$deleteText.'\', \''.$select.'\', \''.$enterName.'\', \''.$maxName.'\', \''.$allowPrincipals.'\', \''.$principalMember.'\')">'.$text.'</a>';
		return $result;
	}
	
	/**
	* Renders a link to delete the current author.
	* @param $controlName HTML name tag of the control the link is next to.
	* @return string HTML text
	*/
	private static function _fetchDeleteControl($controlName){
		$text = JText::_('Delete');
		$result =  '<a href="javascript:deleteControl(\''.$controlName.'\')">'.$text.'</a>';
		return $result;
	}
	
	/**
	* Renders the hidden field that stores the greater index used for control.
	* @param int $value
	*/
	private static function _fetchHiddenField($value, $name){
		return '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'" />';
	}
	
	/**
	* Renders a HTML control for importing staff members from users table.
	* @param $name HTML name of the control which holds the selected users.
	*/
	public function staffImporter($name){
		$db =& JFactory::getDBO();
		$fields = $db->nameQuote('username').', '.$db->nameQuote('lastname').', '.$db->nameQuote('firstname');
		$db->setQuery('SELECT '.$fields.' FROM '.$db->nameQuote('#__jresearch_member'));

		$members = $db->loadAssocList();
		
		$usernames = array();
		foreach($members as $m)
			$usernames[] = $m['username'];
	
		$query = 'SELECT * FROM '.$db->nameQuote('#__users').' WHERE '.$db->nameQuote('block').' = '.$db->Quote('0');
		

		$db->setQuery($query);
		$users = $db->loadAssocList();			
		$joomlaUsers = array();
		
		foreach($users as $u){
			if(!in_array($u['username'], $usernames))
				$joomlaUsers[] = $u;
		}
		

		
		$output = '<table class="staffimporter"><thead><tr><th>'.JText::_('JRESEARCH_MEMBERS_NOT_IN_STAFF').'</th><th></th><th>'.JText::_('JRESEARCH_NEW_STAFF_MEMBERS').'</th></tr></thead><tbody><tr><td>';		
		$output .= '<select name="users" id="users" size="15" class="inputbox staffimporter">';

		foreach($joomlaUsers as $user){
			$value = $user['username'];
			$nameComponents = JResearchPublicationsHelper::getAuthorComponents($user['name']);
			$lastname = $nameComponents['lastname'];
			$firstname = $nameComponents['firstname'];
			$output .= "<option id=\"$value\" value=\"$value\">$lastname, $firstname</option>";
		}
		
		$output .= '</select></td>';
		$output .= '<td align="center"><a style="font-size:14px;font-weight:bold;" href="javascript:addHiddenField(document.adminForm.users.options[document.adminForm.users.selectedIndex].value);moveFrom(\'users\', \''.$name.'\');">&gt;&gt;</><br />';
		$output .= '<a style="font-size:14px;font-weight:bold;" href="javascript:removeHiddenField(document.adminForm.'.$name.'.options[document.adminForm.'.$name.'.selectedIndex].value);moveFrom(\''.$name.'\', \'users\');">&lt;&lt;</a><br />';
		$output .= '<a style="font-size:14px;font-weight:bold;" href="javascript:moveAllFrom(\'users\', \''.$name.'\', true);">'.JText::_('All').'>>'.'</a></td>';
		$output .= '<td><select size="15" class="inputbox staffimporter" name="'.$name.'" id="'.$name.'"></select></td>';
		$output .= '</tr></tbody></table>';		
		$output .= '<input type="hidden" name="staffCount" id="staffCount" value="0" />';
		
		return $output;		
	}
	
	/**
	* Renders the DHTML code needed to enable validation in JResearch forms.
	*/
	static function validation(){
		$doc =& JFactory::getDocument();
		$token = JUtility::getToken();
		JHTML::_('behavior.formvalidation');
		$message = JText::_('JRESEARCH_FORM_NOT_VALID');
    	$doc->addScriptDeclaration("function validate(f) {
			if(document.adminForm.task.value != 'cancel'){
	    		if (document.formvalidator.isValid(f)) {
					return true; 
				}else {
					alert('$message');
					return false;
				}
    		}else
    			return true;
		}");
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'date\', function(value) {
			regex=/^\d{4}(-\d{2}){2}$/;
			return regex.test(value); })
		})');				
    	
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'url\', function(value) {
			regex=/^(ftp|http|https|ftps):\/\/([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}|localhost|\d{1,3}(\.\d{1,3}){3})(:\d{2,5})?(([0-9]{1,5})?\/.*)?$/i;
			return regex.test(value); })
		})');
    	
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'year\', function(value) {
			regex=/^\d{4}$/i;
			return regex.test(value); })
		})');
    	require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'language.php');
		$extra = extra_word_characters();
    	$doc->addScriptDeclaration("window.onDomReady(function() {
			document.formvalidator.setHandler('keywords', function(value) {
			regex=/^[-_'\w$extra\s\d]+([,;][-_'\w$extra\s\d]+)*[,;]*$/i;
			return regex.test(value); })
		})");
    	
		$doc->addScriptDeclaration("window.onDomReady(function() {
			document.formvalidator.setHandler('issn', function(value) {
			regex=/^\d{4}-?\d{4}$/i;
			return regex.test(value); })
		})");
		
		$doc->addScriptDeclaration("window.onDomReady(function() {
			document.formvalidator.setHandler('isbn', function(value) {
			regex=/^(\d{10}|\d{13}|\d{9}x)$/i;
			return regex.test(value); })
		})");
    	
		$doc->addScriptDeclaration("window.onDomReady(function() {
			document.formvalidator.setHandler('doi', function(value) {
			regex=/^\d+\.\d+\/\d+$/i;
			return regex.test(value); })
		})");
    	
	}
	
	/**
	 * Renders a control that allows to upload one or more files in a form.
	 *
	 * @param string $name Control name
	 * @param string $filesFolder Path to the root folder containing the files.	 
	 * @param string $value Text field value
	 * @param string $options Text field options
	 * @param boolean $oneFile If true, the control allows the selection of a single file.
	 * @param array $uploadedFiles List of previously uploaded files.
	 */
	public static function fileUpload($name, $filesFolder, $options="", $oneFile=true, $uploadedFiles = array()){
		global $mainframe;
		$doc =& JFactory::getDocument();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($url."components/com_jresearch/helpers/html/fileuploader.js");
		
		$k = count($uploadedFiles);
		$textFields = '<input type="hidden" name="count_'.$name.'" id="count_'.$name.'" value="'.$k.'" />';
		
		
		$uploadField = '<div id="div_upload_'.$name.'">';
		$uploadField .= '<input id="file_'.$name.'_'.$k.'" name="file_'.$name.'_'.$k.'" type="file" />&nbsp;';
		if(!$oneFile)
			$uploadField .= '<a id="add_'.$name.'" href="javascript:addUploader(\''.$name.'\', \''.JText::_('Delete').'\')">'.JText::_('Add').'</a>';

		$uploadField .= '</div>';
		
		//Render the uploaded files
		$baseUrl = $url.'administrator/components/com_jresearch/'.str_replace(DS, '/', $filesFolder);
		$result = '<ul style="list-style:none;">';
		$n = 0;
		foreach($uploadedFiles as $file){
			$result .= '<li><a href="'.$baseUrl.'/'.$file.'">'.$file.'</a>&nbsp;&nbsp;<label for="delete_'.$name.'_'.$n.'">'.JText::_('Delete').'</label><input type="checkbox" name="delete_'.$name.'_'.$n.'" id="delete_'.$name.'_'.$n.'" /></li>';
			$n++;
		}
		$result .= '</ul>';
		
		return $textField.' '.$uploadField.' '.$textFields.$result;
	}
	
	/**
	 * Renders a HTML generic select list with researchareas
	 * 
	 * @param array $attributes Attributes for the select element, keys 'name' and 'selected' are currently used
	 * @param array $additionElements Additional elements for the list element, each element must include the keys 'id' and 'name'
	 * 
	 * @return JHTMLSelect
	 */
	public static function researchareas(array $attributes=array(), array $additionalElements=array())
	{
		//Get research areas
		$db =& JFactory::getDBO();
		$sql = "SELECT id, name FROM #__jresearch_research_area WHERE published=1";
		
		$db->setQuery($sql);
		$areas = $db->loadObjectList();
	
		//Adds additional options
		foreach($additionalElements as $area)
		{
			if(array_key_exists('id', $area) && array_key_exists('name', $area))
			{
				$areasOptions[] = JHTML::_('select.option', $area['id'], $area['name']);
			}
		}
		
		//Add research areas
		foreach($areas as $area)
		{
			$areasOptions[] = JHTML::_('select.option', $area->id, $area->name);
		}
		
		return JHTML::_('select.genericlist', $areasOptions, self::getKey('name', $attributes, uniqid('select-')), 'class="inputbox" size="10"', 'value', 'text', self::getKey('selected', $attributes));
	}
	
	/**
	 * Renders an attachment download link
	 *
	 * @param string $url Resource URL
	 */
	public static function attachment($url){
		$filename = basename($url);
		$extension = explode('.', $filename);
		$supportedExtensions = array('doc', 'docx', 'pdf', 'ps', 'odt', 'txt');
		$assetsUrl = 'administrator/components/com_jresearch/assets/extensions/';
	
		if(in_array($extension[1], $supportedExtensions)){
			$img = $assetsUrl.$extension[1].'.png';
		}else{
			$img = $assetsUrl.'default.png';				
		}
		
		return "<a href=\"$url\" ><img style=\"border: 0px;\" src=\"$img\" />$filename</a>";
	}
	
	/**
	 * Gets value of array from given key if it exists, otherwise $default
	 */
	private static function getKey($key, array &$arr, $default=null)
	{
		return (array_key_exists($key, $arr)?$arr[$key]:$default);
	}
}
?>
