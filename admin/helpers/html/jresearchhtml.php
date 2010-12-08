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

class JHTMLjresearchhtml
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
		else
			$deleteControl = '';	

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
	 * Renders an authors selector control which implements a kind of autocomplete
	 * functionality
	 *
	 * @param string $baseName Base name of the control. It represents the name of the fields that
	 * will be sent when submitting the form. For internal authors it will be a list. For external 
	 * ones, it will be a text input.
	 * @param array Mixed sorted array. JResearchMember instances will be considered as internal 
	 * staff member's while strings are considered as external authors names.
	 * @param boolean $allowPrincipals If true, a checkbox will be displayed for each entry to define
	 * if the member is a principle investigator in the activity.
	 * @param boolean $isPrincipalsArray Array with the flags indicating if every author stored in $values
	 * is a principal author.
	 */
	
	public static function autoSuggest($baseName, $values = null, $allowPrincipals=false, $isPrincipalsArray=null){
		global $mainframe;
		
		$doc = JFactory::getDocument();
		$urlBase = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		
		$doc->addScript($urlBase.'components/com_jresearch/js/bsn.AutoSuggest_c_2.0.js');
		$upImage = $urlBase.'administrator/components/com_jresearch/assets/up_16.png';
		$downImage = $urlBase.'administrator/components/com_jresearch/assets/down_16.png';		
		$emailWarningImage = $urlBase.'administrator/components/com_jresearch/assets/messagebox_warning.png';
		$emailWarningMessage = JText::_('JRESEARCH_PROVIDE_EMAIL');
		$textField = $baseName.'field';
		$emailLabel = JText::_('Email');
		$projectLeader = JText::_('JRESEARCH_PROJECT_LEADER');
		$delete = JText::_('Delete');
		$repeatedAuthors = JText::_('JRESEARCH_AUTHOR_ADDED_BEFORE');
		$minAuthorLengthMessage = JText::_('JRESEARCH_MIN_AUTHOR_LENGTH_MESSAGE');   	
		$noResults = JText::_('JRESEARCH_NO_RESULTS');

		$doc->addScriptDeclaration("
	        	var options_xml1_$baseName;
	        	
			window.onDomReady(function() {
	                options_xml1_$baseName = {
	                script:'index.php?option=com_jresearch&controller=staff&task=autoSuggestMembers&format=json&' ,
	                varname:'key',
	                json:true,
	                cache:false,
	                callback: function (obj) {
	                    document.getElementById('$baseName').value = obj?obj.id:'';
	                }
	            };
	            as_xml1_$baseName = new AutoSuggest('$textField', options_xml1_$baseName);
	            as_xml1_$baseName.lbl_projectLeader = '$projectLeader';
	            as_xml1_$baseName.lbl_delete = '$delete';
	            as_xml1_$baseName.projectLeaders = '$allowPrincipals';
	            as_xml1_$baseName.lbl_repeatedAuthors = '$repeatedAuthors';
	            as_xml1_$baseName.lbl_minAuthorLengthMessage = '$minAuthorLengthMessage';
	            as_xml1_$baseName.lbl_noresults = '$noResults';
	            as_xml1_$baseName.lbl_up_image = '$upImage';
	            as_xml1_$baseName.lbl_down_image = '$downImage';
	            as_xml1_$baseName.lbl_email_warning_image = '$emailWarningImage';	                        
	            as_xml1_$baseName.lbl_email_warning_message = '$emailWarningMessage';
	            as_xml1_$baseName.lbl_email = '$emailLabel';	            
        		});
	        	        	            
            	function appendAuthor(){
            		if(as_xml1_$baseName){
            			as_xml1_$baseName.setHighlightedValue();
					}
				}");
		$doc->addStyleSheet($urlBase.'components/com_jresearch/css/autosuggest_inquisitor.css');
		$button = '<input style="margin-left:8px;" type="button" onclick="javascript:appendAuthor();" value="'.JText::_('Add').'" />';
		$output = "<div class=\"divTdl\"><input type=\"text\" name=\"$textField\" id=\"$textField\" class=\"validate-integrante\" size=\"25\" />$button</div>";
		
		// Here we verify if there are authors
		$output .= "<input type=\"hidden\" id=\"$baseName\" value=\"\" />";
		if(empty($values)){
			$output .= "<input type=\"hidden\" id=\"n$textField\" name=\"n$textField\" value=\"0\" />";
			$output .= "<div class=\"divTdl\"><ul id=\"".$textField."result\"></ul></div>";
		}else{
			$output .= "<div class=\"divTdl\"><ul id=\"".$textField."result\">";			
			$j = 0;
			foreach($values as $author){
				$output .= "<li id=\"li".$textField.$j."\">";
				$authorText = $author instanceof JResearchMember?$author->__toString():$author['author_name'];
				$authorValue = $author instanceof JResearchMember?$author->id:$author['author_name'];
				$authorEmail = $author instanceof JResearchMember?$author->email:$author['author_email'];				
				$output .= "<span id=\"span$textField$j\" style=\"padding: 2px;\">$authorText</span>";
				$output .= "<input type=\"hidden\" id=\"$textField".$j."\" name=\"$textField".$j."\" value=\"$authorValue\" />";
				$output .= ', <strong>'.JText::_('Email').'</strong>'.": <input type=\"text\" class=\"validate-email\" id=\"$textField"."email".$j."\" name=\"$textField"."email".$j."\" value=\"$authorEmail\" size=\"10\" maxlength=\"60\" style=\"margin-left:3px;\" />";
				$output .= JHTML::_('jresearchhtml.formWarningMessage', $textField.'email'.$j, JText::_('JRESEARCH_PROVIDE_EMAIL'));				
				$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:removeAuthor('li$textField$j')\">$delete</a></span>";
				$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:moveUp('li$textField$j')\"><img style=\"width:16px;height:16px\" src=\"$upImage\" alt=\"\" /></a></span>";
				$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:moveDown('li$textField$j')\"><img style=\"width:16px;height:16px\" src=\"$downImage\" alt=\"\" /></a></span>";				
				if($allowPrincipals){
					if($isPrincipalsArray != null)
						$onText = $isPrincipalsArray[$j]?'value="on" checked="checked"':''; 
					else
						$onText = '';	

					$output .= "<label for=\"check_".$textField."$j\">$projectLeader</label><input type=\"checkbox\" id=\"check_".$textField."$j\" name=\"check_".$textField."$j\" ".$onText."  />";
				}
				$output .= "</li>";
				$j++;
			}
	    	$output .= "</ul><input type=\"hidden\" id=\"n$textField\" name=\"n$textField\" value=\"$j\" />";
			$output .= "</div>";
		}

		return $output;
	}
	
	public static function jsonMembers($key){
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').' WHERE '.$db->nameQuote('published').' = '.$db->Quote('1');
		$query .= ' AND ('.$db->nameQuote('firstname').' LIKE '.$db->Quote( '%'.$db->getEscaped( $key, true ).'%', false );
		$query .= ' OR '.$db->nameQuote('lastname').' LIKE '.$db->Quote( '%'.$db->getEscaped( $key, true ).'%', false ).')';
		$db->setQuery($query);
		$members = $db->loadAssocList(); 		
		$output = "{\"results\": [";
		$arr = array();
		foreach($members as $member){
			$arr[] = "{\"id\": \"".$member['id']."\", \"value\": \"".$member['firstname'].' '.$member['lastname']."\", \"info\": \"".$member['email']."\"}";
		}
		$output .= implode(", ", $arr);
		$output .= "]}";
		
		return $output;
				
	}
	
	/**
	* Renders a HTML control for importing staff members from users table.
	* @param $name HTML name of the control which holds the selected users.
	*/
	public function staffImporter($name){
		global $mainframe;
		
		require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'publications.php');

		$doc = JFactory::getDocument();
		$urlBase = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($urlBase.'components/com_jresearch/js/staffimporter.js');
		
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
			$firstname = isset($nameComponents['firstname'])?$nameComponents['firstname']:'';
			$output .= "<option id=\"$value\" value=\"$value\">$lastname, $firstname</option>";
		}
		
		$output .= '</select></td>';
		$output .= '<td class="staffoptions"><a style="font-size:14px;font-weight:bold;" href="javascript:addHiddenField(document.adminForm.users.options[document.adminForm.users.selectedIndex].value);moveFrom(\'users\', \''.$name.'\');">&gt;&gt;</><br />';
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
			document.formvalidator.setHandler(\'alias\', function(value) {
			regex=/^[\w\s_-]+$/;
			return regex.test(value); })
		})');
    	
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'url\', function(value) {
			regex=/^(ftp|http|https|ftps):\/\/([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}|localhost|\d{1,3}(\.\d{1,3}){3})(:\d{2,5})?(([0-9]{1,5})?\/.*)?$/i;
			return regex.test(value); })
		})');
    	
    	$doc->addScriptDeclaration('window.onDomReady(function() {
			document.formvalidator.setHandler(\'year\', function(value) {
			regex=/^([1-9]\d{3}|0)$/i;
			return regex.test(value); })
		})');
    	require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'language.php');
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
		$doc->addScript($url."components/com_jresearch/js/fileuploader.js");
		
		$k = count($uploadedFiles);
		$textFields = '<input type="hidden" name="count_'.$name.'" id="count_'.$name.'" value="'.$k.'" />';
		
		
		$uploadField = '<div id="div_upload_'.$name.'">';
		$uploadField .= '<input id="file_'.$name.'_'.$k.'" name="file_'.$name.'_'.$k.'" type="file" /><div></div>';
		if(!$oneFile)
			$uploadField .= '<a id="add_'.$name.'" href="javascript:addUploader(\''.$name.'\', \''.JText::_('Delete').'\')">'.JText::_('Add').'</a>';

		$uploadField .= '</div>';
		
		//Render the uploaded files
		$baseUrl = $url.'administrator/components/com_jresearch/'.str_replace(DS, '/', $filesFolder);
		$result = '<ul style="padding:0px;margin:0px;">';
		$n = 0;
		foreach($uploadedFiles as $file){
			$result .= '<li><a href="'.$baseUrl.'/'.$file.'">'.$file.'</a>&nbsp;&nbsp;<label for="delete_'.$name.'_'.$n.'">'.JText::_('Delete').'</label><input type="checkbox" name="delete_'.$name.'_'.$n.'" id="delete_'.$name.'_'.$n.'" />';
			$result .= '<input type="hidden" name="old_'.$name.'_'.$n.'" id="old_'.$name.'_'.$n.'" value="'.$file.'" />';
			$result .= '</li>';
			$n++;
		}
		$result .= '</ul>';
		
		return ' '.$uploadField.' '.$textFields.$result;
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
			if(array_key_exists('id', $area) && array_key_exists('name', $area))
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
	 * Renders a HTML generic select list with member positions
	 */
	public static function memberpositions(array $attributes=array(), array $additional=array())
	{
		$db = JFactory::getDBO();

		$db->setQuery('SELECT * FROM #__jresearch_member_position WHERE published = 1');
		$positions = $db->loadAssocList();
		
		//Additional elements
		$positionOptions = array();
		foreach($additional as $position)
		{
			if(array_key_exists('id', $position) && array_key_exists('name'))
				$positionOptions[] = JHTML::_('select.option', $position['id'], $position['name']);
		}
		
		//Add research areas
		foreach($positions as $position)
		{
			$positionOptions[] = JHTML::_('select.option', $position['id'], $position['position']);
		}
		
		return self::htmllist($positionOptions, $attributes);
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
    	$statusOptions[] = JHTML::_('select.option', 'in_progress', JText::_('JRESEARCH_IN_PROGRESS'));
    	$statusOptions[] = JHTML::_('select.option', 'finished', JText::_('JRESEARCH_FINISHED'));
    	
    	return self::htmllist($statusOptions, $attributes);
	}
	
	/**
	 * Renders a HTML generic select list with status options for publications
	 */
	public static function publicationsstatuslist(array $attributes=array())
	{
		//Status options
    	$statusOptions = array();
    	$statusOptions[] = JHTML::_('select.option', 'protocol', JText::_('JRESEARCH_PROTOCOL'));
    	$statusOptions[] = JHTML::_('select.option', 'in_progress', JText::_('JRESEARCH_IN_PROGRESS'));
    	$statusOptions[] = JHTML::_('select.option', 'finished', JText::_('JRESEARCH_FINISHED'));
    	$statusOptions[] = JHTML::_('select.option', 'rejected', JText::_('JRESEARCH_REJECTED'));
     	$statusOptions[] = JHTML::_('select.option', 'for_reevaluation', JText::_('JRESEARCH_FOR_REEVALUATION'));   	    	
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
		$resourcesOptions[] = JHTML::_('select.option', '0', JText::_('None'));    	
    	$resourcesOptions[] = JHTML::_('select.option', 'cdrom', JText::_('JRESEARCH_CDROM'));
    	$resourcesOptions[] = JHTML::_('select.option', 'film', JText::_('JRESEARCH_FILM'));
    	$resourcesOptions[] = JHTML::_('select.option', 'file', JText::_('JRESEARCH_FILE'));    	

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
		include_once(JPATH_COMPONENT_SITE.DS.'includes'.DS.'CurrencyConvertor.php');
		
		//Currency options
    	$currencyOptions = array();

 		$currencyOptions[] = JHTML::_('select.option', Currency::AUD, Currency::AUD);
 		$currencyOptions[] = JHTML::_('select.option', Currency::CAD, Currency::CAD);
 		$currencyOptions[] = JHTML::_('select.option', Currency::CHF, Currency::CHF);
    	$currencyOptions[] = JHTML::_('select.option', Currency::EUR, Currency::EUR);
    	$currencyOptions[] = JHTML::_('select.option', Currency::GBP, Currency::GBP);
    	$currencyOptions[] = JHTML::_('select.option', Currency::JPY, Currency::JPY);
    	$currencyOptions[] = JHTML::_('select.option', Currency::SEK, Currency::SEK);
    	$currencyOptions[] = JHTML::_('select.option', Currency::USD, Currency::USD);
    	
    	return self::htmllist($currencyOptions, $attributes);
	}
	
	public static function teamshierarchy(array $list, array $attributes=array())
	{
		$teamOptions = array();
		$hierarchy = JResearch::hierarchy($list);
		
		$teamOptions[] = JHTML::_('select.option', null, '['.JText::_('Parent').']');
		
		foreach($hierarchy as $obj)
		{
			$teamOptions[] = JHTML::_('select.option', $obj->object->id, $obj->treename);
			$team = $obj;
			
			//Go through children
			while(@count($team->children) > 0)
			{
				foreach($team->children as $child)
				{
					$teamOptions[] = JHTML::_('select.option', $child->object->id, $child->treename);
				}
				
				$team = $child->children;
			}
		}
		
		return self::htmllist($teamOptions, $attributes);
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
	
    public static function input($name, $value='', $type='text', array $attributes = array())
    {
        $_types = array('text', 'hidden', 'radio', 'checkbox', 'password', 'button', 'image', 'submit', 'reset', 'file');
        
        //Clean parameters
        JFilterOutput::cleanText($name);
        JFilterOutput::cleanText($value);
        JFilterOutput::cleanText($type);
        
        if(!in_array($type, $_types))
        {
            JError::raiseError(0, 'Input-field with type: '.$type.' isn\'t valid');
            return '';
        }
        
        $input = '<input type="'.$type.'" name="'.$name.'" value="'.$value.'" '.self::_makeAttributeString($attributes).' />';
        
        return $input;
    }
    
    private static function _makeAttributeString(array $attributes=array())
    {
        $string = array();
        
        foreach($attributes as $key=>$value)
        {
            JFilterOutput::cleanText($key);
            JFilterOutput::cleanText($value);
            
            $string[] = $key.'="'.$value.'"';
        }
        
        return implode(' ', $string);
    }
	
	/**
	 * Gets value of array from given key if it exists, otherwise $default
	 */
	private static function getKey($key, array &$arr, $default=null)
	{
		return (array_key_exists($key, $arr)?$arr[$key]:$default);
	}
	
	/**
	 * Returns the HTML needed to render the warning image appearing in forms
	 * for client side validation of fields.
	 * 
	 * @param string $name The name of the form field for which the message is rendered.
	 * @param string $message The error message
	 * @return string
	 */
	public static function formWarningMessage($name, $message){
		global $mainframe;
		
		$base = $mainframe->isAdmin()?$mainframe->getSiteUrl():JURI::base();
		$image = $base.'administrator/components/com_jresearch/assets/messagebox_warning.png';

		return '<label for="'.$name.'" class="labelform">
		        <img alt="!!!" src="'.$image.'" width="20" height="20" style="vertical-align: middle;"
		                 title="'.$message.'" /></label>';
		        
	}
	
	/**
	 * Renders an HTML list with all publication subtypes
	 * @param $name Control name.
	 * @return string
	 */
	public static function publicationstypeslist($name, $options = '', $value=''){	
		// Publication type filter
		$types = JResearchPublication::getPublicationsSubtypes();
		$typesHTML = array();
		$typesHTML[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_PUBLICATION_TYPE'));
		foreach($types as $type){
			$typesHTML[] = JHTML::_('select.option', $type, JText::_('JRESEARCH_'.strtoupper($type)));
		}
		
		return JHTML::_('select.genericlist', $typesHTML, $name, $options, 'value','text', $value);		
		
	}
	
	/**
	 * Renders an HTML list with all publication subtypes
	 * @param $name Control name.
	 * @return string
	 */
	public static function publicationsosteopathictypeslist($name, $options = '', $value=''){	
		// Publication type filter
		$types = JResearchPublication::getPublicationsOsteopathicSubtypes();
		$typesHTML = array();
		$typesHTML[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_PUBLICATION_TYPE'));
		foreach($types as $type){
			$typesHTML[] = JHTML::_('select.option', $type, JText::_('JRESEARCH_'.strtoupper($type)));
		}
		
		return JHTML::_('select.genericlist', $typesHTML, $name, $options, 'value','text', $value);
	}
	
	/**
	 * Returns the HTML needed to render a hits control: just a number with a checkbox used
	 * to indicate the hits counter must be reseted.
	 * @param $name Control name (used for the checkbox). Input field holding hits value will be always called 'hits'
	 * @param $value Hits until that moment
	 * @return string
	 */
	public static function hitsControl($name, $value){
		$result = '<span class="hits"><span>'.$value.'</span><span><label for="'.$name.'">(';
		$result .= JText::_('Reset').': </label></span><span><input type="checkbox" name="'.$name.'" id="'.$name.'" />)</span></span>';
		return $result;
	}
	
	/**
	 * Returns the HTML needed to render language select list.
	 * @param string $name Control name
	 * @param string $options Extra HTML options
	 * @param string $key The language information that will be used as key in the list. It can be
	 * 'id' for J!Research table numeric ID or 'isocode'
	 * @param string $display The displayed value for list items, it can be 'name' for the English name or 'native_name'
	 * for the native or vernacular name.
	 * @param string $value Selected value in the list
	 * @return string
	 */
	public static function languagelist($name, $options, $key = 'id', $display = 'name', $value=0){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'language.php');
		
		$languages = JResearchLanguageHelper::getLanguages();
		$langHtmlOptions = array();
		$langHtmlOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_LANGUAGES'));
		
		foreach($languages as $lang){
			$keyText = ($key == 'id' || $key == 'isocode')? $lang[$key] : $lang['id'];
			$valueText = ($display == 'name' || $display == 'native_name')? $lang[$display] : $lang['name'];
			$langHtmlOptions[] = JHTML::_('select.option', $keyText, $valueText);
		}
		
		return JHTML::_('select.genericlist', $langHtmlOptions, $name, $options, 'value','text', $value);	
	}
	
	/**
	 * Renders a HTML generic select list with status options for publications
	 * @param array $attributes
	 * @return string
	 */
	public static function publicationstatuslist(array $attributes=array())
	{
		//Status options
    	$statusOptions = array();
    	$statusOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_STATUS'));
    	$statusOptions[] = JHTML::_('select.option', 'protocol', JText::_('JRESEARCH_PROTOCOL'));
    	$statusOptions[] = JHTML::_('select.option', 'in_progress', JText::_('JRESEARCH_IN_PROGRESS'));
    	$statusOptions[] = JHTML::_('select.option', 'finished', JText::_('JRESEARCH_FINISHED'));
    	$statusOptions[] = JHTML::_('select.option', 'rejected', JText::_('JRESEARCH_REJECTED'));
    	$statusOptions[] = JHTML::_('select.option', 'for_reevaluation', JText::_('JRESEARCH_FOR_REEVALUATION'));
    	
    	return self::htmllist($statusOptions, $attributes);
	}
	
	/**
	 * Renders HTML list containing the publications fields in which a user might search
	 * @param array $attributes
	 * @return string
	 */
	public static function searchfieldslist(array $attributes = array()){
		//Status options
    	$fieldsOptions = array();
    	$fieldsOptions[] = JHTML::_('select.option', 'all', JText::_('JRESEARCH_ALL_FIELDS'));
    	$fieldsOptions[] = JHTML::_('select.option', 'abstract_word', JText::_('JRESEARCH_ABSTRACT_WORD'));
    	$fieldsOptions[] = JHTML::_('select.option', 'heading_word', JText::_('JRESEARCH_HEADING_WORD'));
    	$fieldsOptions[] = JHTML::_('select.option', 'institute_name', JText::_('JRESEARCH_INSTITUTION_NAME'));    	
		$fieldsOptions[] = JHTML::_('select.option', 'author_name', JText::_('JRESEARCH_AUTHOR_NAME'));    	
    	$fieldsOptions[] = JHTML::_('select.option', 'keywords', JText::_('JRESEARCH_KEYWORDS'));    	    	
    	$fieldsOptions[] = JHTML::_('select.option', 'title_word', JText::_('JRESEARCH_TITLE_WORD'));    	    	
    	
    	return self::htmllist($fieldsOptions, $attributes);
		
	}
	
	/**
	 * Renders HTML list containing possible operators used to compliment searches
	 * @param array $attributes
	 * @return string
	 */
	public static function operatorslist(array $attributes = array()){
		//Status options
    	$operatorsOptions = array();
    	$operatorsOptions[] = JHTML::_('select.option', 'and', strtoupper(JText::_('JRESEARCH_AND')));
    	$operatorsOptions[] = JHTML::_('select.option', 'or', strtoupper(JText::_('JRESEARCH_OR')));
    	$operatorsOptions[] = JHTML::_('select.option', 'not', strtoupper(JText::_('JRESEARCH_NOT')));
     	
    	return self::htmllist($operatorsOptions, $attributes);		
	}
	
	/**
	 * Renders HTML list containing possible fields to considering when defining
	 * date ranges for publications.
	 * @param array $attributes
	 * @return string
	 */
	public static function publicationdatesearchlist(array $attributes = array()){
		//Status options
    	$dateOptions = array();
    	$dateOptions[] = JHTML::_('select.option', 'publication_date', JText::_('JRESEARCH_PUBLICATION_DATE'));
    	$dateOptions[] = JHTML::_('select.option', 'entry_date', JText::_('JRESEARCH_ENTRY_DATE'));
     	
    	return self::htmllist($dateOptions, $attributes);		
	}
	
	/**
	 * HTML for ordering criteria in publication searches
	 * @param array $attributes
	 * @return string
	 */
	public static function orderbysearchlist(array $attributes = array()){
		//Status options
    	$orderOptions = array();
    	$orderOptions[] = JHTML::_('select.option', 'date_descending', JText::_('JRESEARCH_DATE_DESCENDING'));
    	$orderOptions[] = JHTML::_('select.option', 'date_ascending', JText::_('JRESEARCH_DATE_ASCENDING'));
    	$orderOptions[] = JHTML::_('select.option', 'title', JText::_('JRESEARCH_TITLE'));
    	$orderOptions[] = JHTML::_('select.option', 'author_name_ascending', JText::_('JRESEARCH_AUTHOR_NAME_ASC'));    	
    	$orderOptions[] = JHTML::_('select.option', 'author_name_descending', JText::_('JRESEARCH_AUTHOR_NAME_DESC'));    	
    	     	
    	return self::htmllist($orderOptions, $attributes);		
	}
	
	public static function instituteslist($name, $options, $value = 0){
		$db = JFactory::getDBO();
		
		$db->setQuery('SELECT id, alias FROM #__jresearch_institute WHERE published = 1 ORDER BY alias');
		$institutesHtmlOptions = array();
		$institutesHtmlOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_INSTITUTES'));
		$institutes = $db->loadAssocList();	
		foreach($institutes as $ins){
			$institutesHtmlOptions[] = JHTML::_('select.option', $ins['id'], $ins['alias']);
		}
		
		return JHTML::_('select.genericlist', $institutesHtmlOptions, $name, $options, 'value','text', $value);		
	}
	
	/**
	 * Returns the HTML needed to render language select list.
	 * @param string $name Control name
	 * @param string $options Extra HTML options
	 * @param string $value Selected value in the list
	 * @return string
	 */
	public static function countrieslist($name, $options, $value=0){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'country.php');
		
		$countries = JResearchCountryHelper::getCountries();
		$countriesHtmlOptions = array();
		$countriesHtmlOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_COUNTRIES'));
		
		foreach($countries as $country){
			$countriesHtmlOptions[] = JHTML::_('select.option', $country['id'], $country['name']);
		}
		
		return JHTML::_('select.genericlist', $countriesHtmlOptions, $name, $options, 'value','text', $value);	
	}
	
	public static function thesestypeslist(array $attributes = array()){
		//Status options
    	$orderOptions = array();
    	$orderOptions[] = JHTML::_('select.option', 'bsc', JText::_('JRESEARCH_BSCTHESIS'));
    	$orderOptions[] = JHTML::_('select.option', 'phd', JText::_('JRESEARCH_PHDTHESIS'));
    	$orderOptions[] = JHTML::_('select.option', 'masters', JText::_('JRESEARCH_MASTERSTHESIS'));
    	$orderOptions[] = JHTML::_('select.option', 'diploma', JText::_('JRESEARCH_DIPLOMATHESIS'));
     	
    	return self::htmllist($orderOptions, $attributes);		
		
	}
	
	public static function publicationsourceslist(array $attributes = array()){
		//Status options
    	$orderOptions = array();
    	$orderOptions[] = JHTML::_('select.option', 'ORW', JText::_('JRESEARCH_ORW'));
    	$orderOptions[] = JHTML::_('select.option', 'WSO', JText::_('JRESEARCH_WSO'));
     	
    	return self::htmllist($orderOptions, $attributes);		
		
	}
}
?>