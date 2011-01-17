<?php
/**
 * @version		$Id: list.php 16210 2010-04-19 04:03:00Z infograf768 $
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
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
            $mainframe = JFactory::getApplication();
            $doc = JFactory::getDocument();
            $urlBase = JURI::root();

            if(self::$loaded){
                $doc->addScript($urlBase.'administrator/components/com_jresearch/models/fields/authorsselector/bsn.AutoSuggest_c_2.0.js');
                $upImage = $urlBase.'administrator/components/com_jresearch/assets/up_16.png';
                $downImage = $urlBase.'administrator/components/com_jresearch/assets/down_16.png';
                $doc->addStyleSheet($urlBase.'administrator/components/com_jresearch/models/fields/authorsselector/autosuggest_inquisitor.css');
                self::$loaded = true;
            }

            $textField = $this->element['name'].'field';
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
            $button = '<input style="margin-left:8px;" type="button" onclick="javascript:appendAuthor();" value="'.JText::_('JRESEARCH_ADD').'" />';
            $output = "<div class=\"divTdl\"><input type=\"text\" name=\"$textField\" id=\"$textField\" class=\"validate-integrante\" size=\"15\" />$button</div>";

            // Here we verify if there are authors
            $output .= "<input type=\"hidden\" id=\"$baseName\" value=\"\" />";
            if(empty($this->value)){
                    $output .= "<input type=\"hidden\" id=\"".$this->element['name']."\" name=\"".$this->element['name']."\" value=\"".$this->value."\" />";
                    $output .= "<div class=\"divTdl\"><ul id=\"".$textField."result\"></ul></div>";
            }else{
                    $output .= "<div class=\"divTdl\"><ul id=\"".$textField."result\">";
                    $j = 0;
                    $parts = explode(';', $this->value);
                    $values = explode(',', $parts[0]);
                    $isPrincipalsArray = array();
                    if(isset($parts[1]))
                        $isPrincipalsArray = explode($parts[1]);

                    foreach($values as $author){
                            $output .= "<li id=\"li".$textField.$j."\">";
                            if(is_numeric($author)){
                                $member = JTable::getInstance('Member', 'JResearch');
                                $member->load($author);
                                $authorText = (string)$member;
                                $authorValue = $author->id;
                            }else{
                                $authorText = $author;
                                $authorValue = $author;
                            }

                            $output .= "<span id=\"span$textField$j\" style=\"padding: 2px;\">$authorText</span>";
                            $output .= "<input type=\"hidden\" id=\"$textField".$j."\" name=\"$textField".$j."\" value=\"$authorValue\" />";
                            $output .= "<span style=\"padding: 2px;\"><a href=\"javascript:removeAuthor('li$textField$j')\">$delete</a></span>";
                            $output .= "<span style=\"padding: 2px;\"><a href=\"javascript:moveUp('li$textField$j')\"><img style=\"width:16px;height:16px\" src=\"$upImage\" alt=\"\" /></a></span>";
                            $output .= "<span style=\"padding: 2px;\"><a href=\"javascript:moveDown('li$textField$j')\"><img style=\"width:16px;height:16px\" src=\"$downImage\" alt=\"\" /></a></span>";
                            if($this->element['allowPrincipals']){
                                $onText = in_array($authorValue, $isPrincipalsArray)?'value="on" checked="checked"':'';
                                $output .= "<label for=\"check_".$textField."$j\">$projectLeader</label><input type=\"checkbox\" id=\"check_".$textField."$j\" name=\"check_".$textField."$j\" ".$onText."  />";
                            }
                            $output .= "</li>";
                            $j++;
                    }
                    $output .= "<input type=\"hidden\" id=\"".$this->element['name']."\" name=\"".$this->element['name']."\" value=\"".$this->value."\" />";
                    $output .= "</div>";
            }

            return $output;

	}
}

?>