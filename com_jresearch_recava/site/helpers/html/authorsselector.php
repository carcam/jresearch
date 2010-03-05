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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');

/**
 * JHTML helper class that implements a control which allow to select
 * authors from staff members.
 *
 */
class JHTMLAuthorsSelector{
	
	
	/**
	* Renders the control. It allows to select authors from staff members. For external
	* authors it is possible to use simple text fields.
	*
	* @param string $baseName Base name of the control. It represents the name of the fields that
	* will be sent when submitting the form. For internal authors it will be a list. For external 
	* ones, it will be a text input. Only letters and _ are allowed.
	* @param array Mixed sorted array. JResearchMember instances will be considered as internal 
	* staff member's while strings are considered as external authors names.
	* @param boolean $allowPrincipals If true, a checkbox will be displayed for each entry to define
	* if the member is a principle investigator in the activity.
	* @param boolean $isPrincipal Array with the flags indicating if every author stored in $values
	* is a principal author.
	*/
	static function _($baseName, $values = null, $allowPrincipals=false, $isPrincipalsArray=null){
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
		$class = "";
		$result  = "<div id=\"div_$controlName\"><input type=\"text\" class=\"$class\" name=\"$controlName\" id=\"$controlName\" value=\"$authorName\" size=\"15\" maxlength=\"255\" />";
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

		$list = JHTML::_('select.genericlist', $options, $controlName, 'class="inputbox"', 'value', 'text', $author?$author->id:null);
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
	 * Control to add recava groups
	 * @param $name
	 * @param $value
	 * @param $show
	 * @return string
	 */
	public function recavagroups($name, $value, $visible){
		global $mainframe;
		$doc =& JFactory::getDocument();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($url."components/com_jresearch/helpers/html/authorsselector.js");
		$delete = JText::_('Delete');		
		$doc->addScriptDeclaration("var deleteMessage = '$delete'");
		
		$output = '<div id="div_'.$name.'" style="padding:0px;display:'.($visible?'block':'none').'">';
		$group = JText::_('JRESEARCH_GROUP');
		$author = JText::_('JRESEARCH_AUTHOR');
		$output .= "<table style=\"vertical-align:top;width:80%;text-align:center;\"><thead><tr><th style=\"width:30%;\">$group</th><th style=\"width:60%;\">$author</th><th></th></tr></thead><tbody id=\"body_$name\">";
		
		$rows = explode(';', $value);
		$j = 0;
		foreach($rows as $row){
			$output .= '<tr id="tr_'.$name.$j.'">';
			$cols = explode(',', $row);
			$output .= '<td><input name="group_'.$name.$j.'" value="'.$cols[0].'" type="text" size="10" /></td>';
			$output .= '<td><input name="author_'.$name.$j.'" value="'.$cols[1].'" type="text" size="10" /></td>';			
			$output .= '<td><a href="javascript:removeRow(\'tr_'.$name.$j.'\', \''.$name.'\')" >'.$delete.'</a></td>';					
			$output .= '</tr>';				
			$j++;
		}
		
		$add = JText::_('Add');
		$output .= '</tbody></table>';
		$output .= '<div style="width:80%;text-align:center;"><a href="javascript:addGroupRow(\'body_'.$name.'\', \''.$name.'\');">'.$add.'</a><input type="hidden" value="'.$j.'" id="count_'.$name.'" name="count_'.$name.'" /></div>';
		$output .= '</div>';		
				
		return $output;
	}
	
	/**
	 * 
	 * @param $name
	 * @param $value
	 * @param $visible
	 * @return unknown_type
	 */
	public static function recavaplatforms($name, $value, $visible){
		global $mainframe;
		$doc =& JFactory::getDocument();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$doc->addScript($url."components/com_jresearch/helpers/html/authorsselector.js");
		$delete = JText::_('Delete');		
		$doc->addScriptDeclaration("var deleteMessage = '$delete'");
		
		$output = '<div id="div_'.$name.'" style="display:'.($visible?'block':'none').'">';
		$platform = JText::_('JRESEARCH_PLATFORM_NAME');
		$output .= "<table style=\"vertical-align:top;width:80%;text-align:center;\"><thead><tr><th style=\"width:90%;\">$platform</th><th></th></tr></thead><tbody id=\"body_$name\">";
		
		$rows = explode(';', $value);
		$j = 0;
		foreach($rows as $row){
			$output .= '<td><input name="platform_'.$name.$j.'" value="'.$row.'" type="text" size="18" /></td>';			
			$output .= '<td><a href="javascript:removeRow(\'tr_'.$name.$j.'\', \''.$name.'\')" >'.$delete.'</a></td>';					
			$output .= '</tr>';				
			$j++;
		}
		
		$add = JText::_('Add');
		$output .= '</tbody></table>';
		$output .= '<div style="width:80%;text-align:center;"><a href="javascript:addPlatformRow(\'body_'.$name.'\', \''.$name.'\');">'.$add.'</a><input type="hidden" value="'.$j.'" id="count_'.$name.'" name="count_'.$name.'" /></div>';
		$output .= '</div>';		
		
		return $output;
		
	}
	
	/**
	 * 
	 * @param $name
	 * @param $value
	 * @return unknown_type
	 */
	public static function recavaotherlines($name, $value){
		$lines = explode(";", $value);
		$output = "<div><ul>";		
		foreach($lines as $line){
			$lineValues = explode("=", $line);
			$lineValues[0] = trim($lineValues[0]);
			$lineValues[1] = trim($lineValues[1]);			

			$checked = ($lineValues[1] == 1)?'checked="checked"':'';
			
			$output .= '<li>';
			$output .= '<input type="checkbox" id="'.$name.'_'.$lineValues[0].'" name="'.$name.'_'.$lineValues[0].'" '.$checked.' />';			
			$output .= '<label for="'.$name.'_'.$lineValues[0].'" />'.JText::_('JRESEARCH_'.strtoupper($lineValues[0]));			
			$output .= '</li>';			
		}
		
		$output .= "</ul></div>";
		return $output;
	}
	
	/**
	 * 
	 * @param $memberId
	 * @return JResearchMember
	 */
	private static function _getMember($memberId){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
		$member = new JResearchMember(JFactory::getDBO());
		$member->load($memberId);
		return $member;
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
		
		$doc->addScript($urlBase.'components/com_jresearch/helpers/html/bsn.AutoSuggest_c_2.0.js');
		$upImage = $urlBase.'administrator/components/com_jresearch/assets/up_16.png';
		$downImage = $urlBase.'administrator/components/com_jresearch/assets/down_16.png';		
		$textField = $baseName.'field';
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
        		});
	        	        	            
            	function appendAuthor(){
            		if(as_xml1_$baseName){
            			as_xml1_$baseName.setHighlightedValue();
					}
				}");
		$doc->addStyleSheet($urlBase.'components/com_jresearch/helpers/html/autosuggest_inquisitor.css');
		$button = '<input style="margin-left:8px;" type="button" onclick="javascript:appendAuthor();" value="'.JText::_('Add').'" />';
		$output = "<div class=\"divTdl\"><input type=\"text\" name=\"$textField\" id=\"$textField\" class=\"validate-integrante\" size=\"15\" />$button</div>";
		
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
				$authorText = $author instanceof JResearchMember?$author->__toString():$author;
				$authorValue = $author instanceof JResearchMember?$author->id:$author;
				$output .= "<span id=\"span$textField$j\" style=\"padding: 2px;\">$authorText</span>";
				$output .= "<input type=\"hidden\" id=\"$textField".$j."\" name=\"$textField".$j."\" value=\"$authorValue\" />";
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
	
	public static function autoSuggest2($baseName, $values = null, $allowPrincipals=false, $isPrincipalsArray=null){
		global $mainframe;
//		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'member.php');
		$doc = JFactory::getDocument();
		$urlBase = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$db = JFactory::getDBO();
		
		$doc->addScript($urlBase.'components/com_jresearch/helpers/html/bsn.AutoSuggest_c_2.0.1.js');
		$upImage = $urlBase.'administrator/components/com_jresearch/assets/up_16.png';
		$downImage = $urlBase.'administrator/components/com_jresearch/assets/down_16.png';		
		$textField = $baseName.'field';
		$projectLeader = JText::_('JRESEARCH_PROJECT_LEADER');
		$delete = JText::_('JRESEARCH_REMOVE');
		$repeatedAuthors = JText::_('JRESEARCH_AUTHOR_ADDED_BEFORE');
		$minAuthorLengthMessage = JText::_('JRESEARCH_MIN_AUTHOR_LENGTH_MESSAGE');   	
		$noResults = JText::_('JRESEARCH_NO_RESULTS');
		$belongsRecava = JText::_('JRESEARCH_BELONGS_RECAVA');

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
	            as_xml1_$baseName.lbl_belongs_recava = '$belongsRecava';            
        		});
	        	        	            
            	function appendAuthor(){
            		if(as_xml1_$baseName){
            			as_xml1_$baseName.setHighlightedValue();
					}
				}
				
				function toogleTeamsControl(index, fieldText){
					booleanControl = document.getElementById('boolean_'+fieldText+index);
					teamsControl = document.getElementById('teams_'+fieldText+index);
					if(booleanControl && teamsControl){
						if(booleanControl.value == 'yes'){
							teamsControl.style.display = 'inline';
						}else{
							teamsControl.style.display = 'none';
							teamsControl.selectedIndex = 0;
						}
					}
					
				}
			");
		$doc->addStyleSheet($urlBase.'components/com_jresearch/helpers/html/autosuggest_inquisitor.css');
		$button = '<input style="margin-left:8px;" type="button" onclick="javascript:appendAuthor();" value="'.JText::_('Add').'" />';
		$output = "<div class=\"divTdl\"><input type=\"text\" name=\"$textField\" id=\"$textField\" class=\"validate-integrante\" size=\"15\" />$button</div>";
		
		// Here we verify if there are authors
		$output .= "<input type=\"hidden\" id=\"$baseName\" value=\"\" />";
		
		$teamOptions = array();
		$db->setQuery('SELECT * FROM '.$db->nameQuote('#__jresearch_team').' WHERE '.$db->nameQuote('published').' = 1');
		$result = $db->loadAssocList();
		$teamOptions[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_ALL_TEAMS'));
		foreach($result as $team){
			$teamOptions[] = JHTML::_('select.option', $team['id'], $team['name']);
		}
		
		$booleanOptions = array();
		$booleanOptions[] = JHTML::_('select.option', 'yes', JText::_('JRESEARCH_YES'));
		$booleanOptions[] = JHTML::_('select.option', 'no', JText::_('JRESEARCH_NO'));
						
		if(empty($values)){
			$output .= "<input type=\"hidden\" id=\"n$textField\" name=\"n$textField\" value=\"0\" />";
			$output .= "<div class=\"divTdl\"><ul id=\"".$textField."result\"></ul>";
		}else{
			$output .= "<div class=\"divTdl\"><ul id=\"".$textField."result\">";			
			$j = 0;
			foreach($values as $author){
				$output .= "<li id=\"li".$textField.$j."\">";
				$authorText = null;
				$authorValue = null;
				$team = null;
				if($author instanceof JResearchMember){
					$team = $author->getTeam(); 
					$authorText = $author->__toString();
					$authorValue = $author->id;					
				}else{
					$team = JResearchMember::getTeamByAuthorName($author);	
					$authorText = $author;
					$authorValue = $author;										
				}
				$booleanControl = JHTML::_('select.genericlist', $booleanOptions ,'boolean_'.$textField.$j, 'class="inputbox" onchange="javascript:toogleTeamsControl('.$j.', \''.$textField.'\');" id="boolean_'.$textField.$j.'"' ,'value', 'text', !empty($team)? 'yes': 'no');	
				$teamsList = JHTML::_('select.genericlist', $teamOptions ,'teams_'.$textField.$j, 'class="inputbox" '.(!empty($team)? 'style="display:inline;"' : 'style="display:none";').' id="teams_'.$textField.$j.'"' ,'value', 'text', !empty($team)? $team->id: '-1');	
				 
				$output .= "<span id=\"span$textField$j\" style=\"padding: 2px;\">$authorText</span>";
				$output .= "<input type=\"hidden\" id=\"$textField".$j."\" name=\"$textField".$j."\" value=\"$authorValue\" />";
				$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:removeAuthor('li$textField$j')\">$delete</a></span>";
				$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:moveUp('li$textField$j')\"><img style=\"width:16px;height:16px\" src=\"$upImage\" alt=\"\" /></a></span>";
				$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:moveDown('li$textField$j')\"><img style=\"width:16px;height:16px\" src=\"$downImage\" alt=\"\" /></a></span>";
				$output .= "<span>$belongsRecava: </span><span style=\"padding: 2px;\">$booleanControl</span><span style=\"padding: 2px;\">$teamsList</span>";				
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
		}
		
		$output .= JHTML::_('select.genericlist', $teamOptions ,'masterteamslist_'.$textField, 'class="inputbox" style="display:none;" id="masterlist_'.$textField.'"' ,'value', 'text', '-1');	
		$output .= JHTML::_('select.genericlist', $booleanOptions ,'masterbooleanlist_'.$textField, 'class="inputbox" style="display:none;" id="masterbooleanlist_'.$textField.'"' ,'value', 'text', 'no');
		$output .= "</div>";
		

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
			$memberObj = JTable::getInstance('Member', 'JResearch');
			$memberObj->load($member['id']);
			$team = $memberObj->getTeam();
			$teamId = -1;
			if(!empty($team))
				$teamId = $team->id;
			$arr[] = "{\"id\": \"".$member['id']."\", \"value\": \"".$member['firstname'].' '.$member['lastname']."\", \"info\": \"".$member['email']."\", \"teamid\": ".$teamId."}";
		}
		$output .= implode(", ", $arr);
		$output .= "]}";
		
		return $output;
				
	}
	
	
}

?>