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

class JHTMLJresearchhtml
{
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

		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('published').'='.$db->Quote('1');
		$db->setQuery($query);
		$result = $db->loadAssocList();
		
		$options = array();
		
		foreach($result as $r){
			$options[] = JHTML::_('select.option', $r['id'], $r['firstname'].' '.$r['lastname']);
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
	public static function researchareas(array $attributes=array(), array $additional=array())
	{
		include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas'.DS.'researchareaslist.php');
		
		$model = new JResearchModelResearchAreasList();
		$areas = $model->getData(null,true);
		
		//Additional elements
		$areasOptions = array();
		foreach($additional as $area)
		{
			if(array_key_exists('id', $area) && array_key_exists('name'))
				$areasOptions[] = JHTML::_('select.option', $area['id'], $area['name']);
		}
		
		//Add research areas
		foreach($areas as $area)
		{
			$areasOptions[] = JHTML::_('select.option', $area->id, $area->name);
		}
		
		return self::htmllist($areasOptions, $attributes);
	}

	/**
	 * Renders a HTML generic select list with cooperations
	 */
	public static function cooperations(array $attributes=array())
	{
		include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'cooperations'.DS.'cooperations.php');
		
		$model = new JResearchModelCooperations();
		$coops = $model->getData(null, true);
		
		$cooperationOptions = array();
		foreach($coops as $coop)
		{
			$cooperationOptions[] = JHTML::_('select.option', $coop->id, $coop->name);	
		}
		
		return self::htmllist($cooperationOptions, $attributes);
	}
	
	/**
	 * Renders a HTML generic select list with financiers
	 */
	public static function financiers(array $attributes=array())
	{
		include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'financiers'.DS.'financiers.php');
		
		$model = new JResearchModelFinanciers();
		$financiers = $model->getData(null, true);
		
		//Financier options
    	$financierOptions = array();
    	$financierOptions[0] = JHTML::_('select.option', '', JText::_('JRESEARCH_PROJECT_NO_FINANCIERS'));
    	foreach($financiers as $fin)
    	{
    		$financierOptions[] = JHTML::_('select.option', $fin->id, $fin->name);
    	}
    	
    	return self::htmllist($financierOptions, $attributes);
	}
	
	/**
	 * Renders a HTML generic select list with published options
	 */
	
	public static function publishedlist(array $attributes=array())
	{
		$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));
    	
    	return self::htmllist($publishedOptions, $attributes);
	}

	/**
	 * Renders a HTML generic select list with status options
	 */
	public static function statuslist(array $attributes=array())
	{
		//Status options
    	$statusOptions = array();
    	$statusOptions[] = JHTML::_('select.option', 'not_started', JText::_('JRESEARCH_NOT_STARTED'));
    	$statusOptions[] = JHTML::_('select.option', 'in progress', JText::_('JRESEARCH_IN_PROGRESS'));
    	$statusOptions[] = JHTML::_('select.option', 'finished', JText::_('Finished'));
    	
    	return self::htmllist($statusOptions, $attributes);
	}
	
	/**
	 * Renders a HTML list of online resources types.
	 *
	 * @param array $attributes
	 * @return string
	 */
	public static function onlineresourcelist(array $attributes = array()){
		//Status options
    	$resourcesOptions = array();
    	$resourcesOptions[] = JHTML::_('select.option', 'website', JText::_('JRESEARCH_WEBSITE'));
    	$resourcesOptions[] = JHTML::_('select.option', 'video', JText::_('JRESEARCH_VIDEO'));
    	$resourcesOptions[] = JHTML::_('select.option', 'audio', JText::_('JRESEARCH_AUDIO'));
    	$resourcesOptions[] = JHTML::_('select.option', 'image', JText::_('JRESEARCH_IMAGE'));
    	$resourcesOptions[] = JHTML::_('select.option', 'image', JText::_('JRESEARCH_BLOG'));    	
    	
    	return self::htmllist($resourcesOptions, $attributes);		
	}
	
	/**
	 * Renders a HTML list of digital sources.
	 *
	 * @param array $attributes
	 * @return string
	 */
	public static function digitalresourcelist(array $attributes = array()){
		//Status options
    	$resourcesOptions = array();
    	$resourcesOptions[] = JHTML::_('select.option', 'cdrom', JText::_('JRESEARCH_CDROM'));
    	$resourcesOptions[] = JHTML::_('select.option', 'film', JText::_('JRESEARCH_FILM'));
    	
    	return self::htmllist($resourcesOptions, $attributes);		
	}
	
		
	
	/**
	 * Renders a HTML generic select list with degree options
	 */
	public static function degreelist(array $attributes=array())
	{
		$degreeOptions = array();
    	$degreeOptions[] = JHTML::_('select.option', 'bachelor', JText::_('JRESEARCH_BACHELOR'));
    	$degreeOptions[] = JHTML::_('select.option', 'master', JText::_('JRESEARCH_MASTER'));
    	$degreeOptions[] = JHTML::_('select.option', 'phd', JText::_('JRESEARCH_PHD'));
    	
    	return self::htmllist($degreeOptions, $attributes);
	}
	
	/**
	 * Renders a HTML generic select list with currency options
	 */
	public static function currencylist(array $attributes=array())
	{
		//Currency options
    	$currencyOptions = array();
    	$currencyOptions[] = JHTML::_('select.option', 'EUR', 'Euro');
    	$currencyOptions[] = JHTML::_('select.option', 'USD', 'US Dollar');
    	
    	return self::htmllist($currencyOptions, $attributes);
	}
	
	/**
	 * Renders HTML select list with $options and given attributes
	 * @param array $options
	 * @param array $attributes
	 */
	private static function htmllist(array $options, array $attributes=array())
	{
		return JHTML::_('select.genericlist', $options, self::getKey('name', $attributes, uniqid('select-')), self::getKey('attributes', $attributes, ''), 'value', 'text', self::getKey('selected', $attributes));
	}
	
	/**
	 * Renders HTML hidden fields for given controller, task and layout
	 */
	public static function hiddenfields($controller, $task='', $layout='default')
	{
		$str = '<input type="hidden" name="option" value="com_jresearch" />';
		
		if(is_string($controller))
			$str .= '<input type="hidden" name="controller" value="'.$controller.'" />';
			
		if(is_string($task))
			$str .= '<input type="hidden" name="task" value="'.$task.'" />';
			
		if(is_string($layout))
			$str .= '<input type="hidden" name="layout" value="'.$layout.'" />';
		
		return $str;
	}
	
	/**
	 * Renders a list of available authors ordered by last name.
	 *
	 * @param array $authors
	 */
	public static function authors(array $attributes = array()){
		include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications'.DS.'publicationslist.php');
		
		$model = new JResearchModelPublicationsList();
		$result = $model->getAllAuthors();
		
		return self::htmllist($result, $attributes);
		
		
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
