<?php
/**
 * @package		J!Research
 * @subpackage	Form
 * @copyright	Luis Galárraga (C) 2008
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Control for authors selection
 *
 */
class JFormFieldAuthorsselector extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'List';

    public static $loaded = false;

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		jresearchimport('helpers.staff', 'jresearch.admin');
		
		$mainframe = JFactory::getApplication();		
		$doc = JFactory::getDocument();
		$urlBase = JURI::root();
		$baseName = $this->element['name'];

		$doc->addScript($urlBase.'administrator/components/com_jresearch/models/fields/authorsselector/bsn.AutoSuggest_c_2.0.js');
		$upImage = $urlBase.'administrator/components/com_jresearch/assets/up_16.png';
		$downImage = $urlBase.'administrator/components/com_jresearch/assets/down_16.png';		
		$textField = $baseName.'field';
		$projectLeader = JText::_('JRESEARCH_PROJECT_LEADER');
		$delete = JText::_('Delete');
		$repeatedAuthors = JText::_('JRESEARCH_AUTHOR_ADDED_BEFORE');
		$minAuthorLengthMessage = JText::_('JRESEARCH_MIN_AUTHOR_LENGTH_MESSAGE');   	
		$noResults = JText::_('JRESEARCH_NO_RESULTS');
		$allowPrincipals = ($this->element['allowPrincipals'] == 'true');

		if(!empty($this->value))
			$values = explode(';', trim($this->value));
		else
			$values = array();	
			
		

		$doc->addScriptDeclaration("
	        	var options_xml1_$baseName;
	        	window.addEvent('domready', function() {
	                options_xml1_$baseName = {
	                script:'index.php?option=com_jresearch&controller=staff&task=autoSuggestMembers&format=json&' ,
	                varname:'key',
	                json:true,
	                cache:false,
	                callback: function (obj) {
	                    document.getElementById('jform[$baseName]').value = obj?obj.id:'';
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
		$doc->addStyleSheet($urlBase.'components/com_jresearch/css/autosuggest_inquisitor.css');
		$button = '<input style="margin-left:8px;" type="button" onclick="javascript:appendAuthor();" value="'.JText::_('JRESEARCH_ADD').'" />';
		$output = "<div class=\"divTdl\"><input type=\"text\" name=\"$textField\" id=\"$textField\" class=\"validate-integrante\" size=\"15\" />$button</div>";
		
		// Here we verify if there are authors
		$output .= "<input type=\"hidden\" id=\"jform[$baseName]\" value=\"".$this->value."\" />";
		if(empty($values)){
			$output .= "<input type=\"hidden\" id=\"jform[n$textField]\" name=\"jform[n$textField]\" value=\"0\" />";
			$output .= "<div class=\"divTdl\"><ul id=\"".$textField."result\"></ul></div>";
		}else{
			$output .= "<div class=\"divTdl\"><ul id=\"".$textField."result\">";			
			$j = 0;
			foreach($values as $authorEntry){
				$authorEntryArray = explode('|', $authorEntry);
				$author = $authorEntryArray[0]; 
				$output .= "<li id=\"li".$textField.$j."\">";
				$authorText = null;
				if(is_numeric($author)){
					$member = JResearchStaffHelper::getMember((int)$author);
					if(!empty($member))
						$authorText = $member->__toString();
				}else{
					$authorText = $author;
				}
				
				if(!empty($authorText)){
					$output .= "<span id=\"span$textField$j\" style=\"padding: 2px;\">$authorText</span>";
					$output .= "<input type=\"hidden\" id=\"jform[$textField".$j."]\" name=\"jform[$textField".$j."]\" value=\"$author\" />";
					$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:removeAuthor('li$textField$j')\">$delete</a></span>";
					$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:moveUp('li$textField$j')\"><img style=\"width:16px;height:16px;float:none\" src=\"$upImage\" alt=\"\" /></a></span>";
					$output .= "<span style=\"padding: 2px;\"><a href=\"javascript:moveDown('li$textField$j')\"><img style=\"width:16px;height:16px;float:none\" src=\"$downImage\" alt=\"\" /></a></span>";				
					if($allowPrincipals)
						$onText = isset($authorEntryArray[1]) && $authorEntryArray[1] == 'on'?'value="on" checked="checked"':''; 
					else
						$onText = '';	
	
					$output .= "<label for=\"jform[check_".$textField."$j]\">$projectLeader</label><input type=\"checkbox\" id=\"jform[check_".$textField."$j]\" name=\"jform[check_".$textField."$j]\" ".$onText."  />";
					$output .= "</li>";
					$j++;
				}
			}
	    	$output .= "</ul><input type=\"hidden\" id=\"jform[n$textField]\" name=\"jform[n$textField]\" value=\"$j\" />";
			$output .= "</div>";
		}

		return $output;
		
	}
}

?>