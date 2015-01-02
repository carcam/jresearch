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
        $mainframe = JFactory::getApplication();

        require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');

        $doc = JFactory::getDocument();
        $urlBase = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::root();
        $doc->addScript($urlBase.'components/com_jresearch/js/staffimporter.js');

        $db =& JFactory::getDBO();
        $fields = $db->quoteName('username').', '.$db->quoteName('lastname').', '.$db->quoteName('firstname');
        $db->setQuery('SELECT '.$fields.' FROM '.$db->quoteName('#__jresearch_member'));

        $members = $db->loadAssocList();

        $usernames = array();
        foreach($members as $m) {
            $usernames[] = $m['username'];
        }

        $query = 'SELECT * FROM '.$db->quoteName('#__users').' WHERE '.$db->quoteName('block').' = '.$db->Quote('0');

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
            $lastname = trim($nameComponents['von'].' '.$nameComponents['lastname']);
            $firstname = isset($nameComponents['firstname'])?trim($nameComponents['firstname'].' '.$nameComponents['jr']):'';
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
    * Renders a HTML control for importing staff members from users table.
    * @param $name HTML name of the control which holds the selected users.
    */
    public function staffImporter2($name){
        static $dependenciesLoaded = false;
        $mainframe = JFactory::getApplication();
        require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');
        $doc = JFactory::getDocument();
        $db = JFactory::getDBO();

        if(!$dependenciesLoaded){
            $doc = JFactory::getDocument();
            $urlBase = JURI::root();
            $doc->addScript($urlBase.'components/com_jresearch/js/staffimporter2.js');
            $doc->addStyleDeclaration('th.staffcol{width: 30%;} th.staffspace{width: 20%;} select.propertiesselector{ width: 200px; min-width: 200px;} table.propertiesselector{ text-align: center; width: 60%;  margin-left: auto; margin-right: auto;} a.propertiesselector{ font-size:14px;font-weight:bold;}');
            $dependenciesLoaded = true;
        }

        //Get J!Research members
        $fields = $db->quoteName('username').', '.$db->quoteName('lastname').', '.$db->quoteName('firstname');
        $db->setQuery('SELECT '.$fields.' FROM '.$db->quoteName('#__jresearch_member'));
        $members = $db->loadAssocList();

        //Now get Joomla! users that are not members
        $usernames = array();
        foreach($members as $m)
                $usernames[] = $m['username'];
        $query = 'SELECT * FROM '.$db->quoteName('#__users').' WHERE '.$db->quoteName('block').' = '.$db->Quote('0');
        $db->setQuery($query);
        $users = $db->loadAssocList();
        $joomlaUsers = array();
        foreach($users as $u){
                if(!in_array($u['username'], $usernames))
                        $joomlaUsers[] = $u;
        }

        $not_in_staff = JText::_('JRESEARCH_MEMBERS_NOT_IN_STAFF');
        $new_staff_members = JText::_('JRESEARCH_NEW_STAFF_MEMBERS');

        $output = '<table class="propertiesselector"><thead><tr><th class="staffcol">'.$not_in_staff.'</th><th class="staffspace"></th><th class="staffcol">'.$new_staff_members.'</th><th class="staffspace"></th></tr></thead><tbody><tr><td>';
        $output .= '<select multiple="multiple" name="users" id="users" size="15" class="inputbox staffimporter">';

        foreach($joomlaUsers as $user){
            $userid = $user['username'];
            $nameComponents = JResearchPublicationsHelper::getAuthorComponents($user['name']);
            $lastname = (isset($nameComponents['von'])? $nameComponents['von'].' ' : '').$nameComponents['lastname'];
            $firstname = isset($nameComponents['firstname'])?$nameComponents['firstname']:'';
            $firstname .= isset($nameComponents['jr'])?$nameComponents['jr']:'';
            $output .= "<option id=\"$userid\" value=\"$userid\">$lastname, $firstname</option>";
        }

        $output .= '</select></td>';
        $output .= '<td align="center">';
        $output .= '<a class="propertiesselector" href="javascript:moveAllFrom(\'users\', \''.$name.'\', true);">'.JText::_('All').'&gt;&gt;'.'</a><br /><br />';
        $output .= '<a class="propertiesselector" href="javascript:moveFrom(\'users\', \''.$name.'\', true);">&gt;&gt;</a><br /><br />';
        $output .= '<a class="propertiesselector" href="javascript:moveAllFrom(\''.$name.'\', \'users\', false);">'.'&lt;&lt;'.JText::_('All').'</a><br /><br />';
        $output .= '<a class="propertiesselector" href="javascript:moveFrom(\''.$name.'\', \'users\', false);">&lt;&lt;</a></td>';
        $output .= '<td><select multiple="multiple" size="15" class="inputbox propertiesselector" name="'.$name.'" id="'.$name.'">';
        $output .= '</select></td>';
        $output .= '<td style="text-align:left;"><a class="propertiesselector" href="javascript:goUp(\''.$name.'\')">'.JText::_('JRESEARCH_GO_UP').'</a><br /><a class="propertiesselector" href="javascript:goDown(\''.$name.'\')">'.JText::_('JRESEARCH_GO_DOWN').'</a></td></tr></tbody></table>';
        $output .= '<input type="hidden" name="staffCount" id="staffCount" value="0" />';

        return $output;

    }	

    /**
    * Renders the DHTML code needed to enable validation in JResearch forms.
    */
    static function validation(){
        jresearchimport('helpers.charsets', 'jresearch.admin');
        $extra = implode('', JResearchCharsetsHelper::getLatinWordSpecialChars());
        $doc = JFactory::getDocument();
        $token = JSession::getFormToken();
        JHTML::_('behavior.tooltip');
        JHTML::_('behavior.formvalidation');
        $message = JText::_('JRESEARCH_FORM_NOT_VALID');

        $doc->addScriptDeclaration("Joomla.submitbutton = function(task){
            if (task == ''){
                return false;
            }else{
                var isValid=true;
                if (task != 'cancel' && task != 'close'){
                    var forms = $$('form.form-validate');
                    for (var i=0;i<forms.length;i++)
                    {
                        if (!document.formvalidator.isValid(forms[i]))
                        {
                            isValid = false;
                            break;
                        }
                    }
                }

                if (isValid)
                {
                    Joomla.submitform(task);
                        return true;
                }else{
                    alert(Joomla.JText._('COM_JRESEARCH_FORM_ERROR_UNACCEPTABLE','Some values are unacceptable'));
                    return false;
                }
            }
        }");

        $doc->addScriptDeclaration('window.addEvent(\'domready\', function() {
                document.formvalidator.setHandler(\'date\', function(value) {
                regex=/^\d{4}(-\d{2}){2}$/;
                return regex.test(value); })
        });');

        $doc->addScriptDeclaration('window.addEvent(\'domready\', function() {
                document.formvalidator.setHandler(\'url\', function(value) {
                regex=/^(ftp|http|https|ftps):\/\/([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}|localhost|\d{1,3}(\.\d{1,3}){3})(:\d{2,5})?(([0-9]{1,5})?\/.*)?$/i;
                return regex.test(value); })
        });');

        $doc->addScriptDeclaration('window.addEvent(\'domready\', function() {
                document.formvalidator.setHandler(\'year\', function(value) {
                regex=/^([1-9]\d{3}|0)$/i;
                return regex.test(value); })
        });');

        $doc->addScriptDeclaration('window.addEvent(\'domready\', function() {
                document.formvalidator.setHandler(\'number\', function(value) {
                regex=/^([\+\-]{0,1}\d+\.?\d*)$/i;
                return regex.test(value); })
        });');


        $doc->addScriptDeclaration("window.addEvent('domready', function() {
                document.formvalidator.setHandler('keywords', function(value) {
                regex=/^[-_'\w$extra\s\d]+([,;][-_'\w$extra\s\d]+)*[,;]*$/i;
                return regex.test(value); })
        });");

        $doc->addScriptDeclaration("window.addEvent('domready', function() {
                document.formvalidator.setHandler('issn', function(value) {
                regex=/^\d{4}-?\d{4}$/i;
                return regex.test(value); })
        });");

        $doc->addScriptDeclaration("window.addEvent('domready', function() {
                document.formvalidator.setHandler('isbn', function(value) {
                regex=/^(\d{10}|\d{13}|\d{9}x)$/i;
                return regex.test(value); })
        });");
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
        $mainframe = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $url = JURI::root();
        $doc->addScript($url."components/com_jresearch/js/fileuploader.js");

        $k = count($uploadedFiles);
        $textFields = '<input type="hidden" name="count_'.$name.'" id="count_'.$name.'" value="'.$k.'" />';


        $uploadField = '<div id="div_upload_'.$name.'">';
        $uploadField .= '<input id="file_'.$name.'_'.$k.'" name="file_'.$name.'_'.$k.'" type="file" /><div></div>';
        if(!$oneFile)
                $uploadField .= '<a id="add_'.$name.'" href="javascript:addUploader(\''.$name.'\', \''.JText::_('Delete').'\')">'.JText::_('Add').'</a>';

        $uploadField .= '</div>';

        //Render the uploaded files
        $baseUrl = $url.'administrator/components/com_jresearch/'.str_replace(DS, DS, $filesFolder);
        $result = '<ul style="padding:0px;margin:0px;">';
        $n = 0;
        foreach($uploadedFiles as $file){
                $result .= '<li><a href="'.$baseUrl.DS.$file.'">'.$file.'</a>&nbsp;&nbsp;<label for="delete_'.$name.'_'.$n.'">'.JText::_('Delete').'</label><input type="checkbox" name="delete_'.$name.'_'.$n.'" id="delete_'.$name.'_'.$n.'" />';
                $result .= '<input type="hidden" name="old_'.$name.'_'.$n.'" id="old_'.$name.'_'.$n.'" value="'.$file.'" />';
                $result .= '</li>';
                $n++;
        }
        $result .= '</ul>';

        return ' '.$uploadField.' '.$textFields.$result;
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
        $db = JFactory::getDBO();

        $query = 'SELECT id, name FROM '.$db->quoteName('#__jresearch_research_area');
        $db->setQuery($query);
        $areas = $db->loadAssocList();
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
                $areasOptions[] = JHTML::_('select.option', $area['id'], $area['name']);
        }

        return self::htmllist($areasOptions, $attributes);
    }

    /**
     * 
     * Renders a drop-down list of authors
     * @param array $data
     * @param array $attributes
     * @param array $additional
     */
    public static function authors(array $data, array $attributes=array()){
        //Add research areas
        $authOptions = array();

        $authOptions[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_AUTHORS'));

        foreach($data as $author){
            $authOptions[] = JHTML::_('select.option', $author['mid'], $author['member_name']);
        }

        return self::htmllist($authOptions, $attributes);
    }

    public static function years(array $data, array $attributes=array()){
        $yearOptions = array();

        $yearOptions[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_YEAR'));

        foreach($data as $y){
            $yearOptions[] = JHTML::_('select.option', $y, $y);
        }

        return self::htmllist($yearOptions, $attributes);		
    }

    /**
     * Renders a HTML generic select list with member positions
     */
    public static function memberpositions(array $attributes=array(), array $additional=array())
    {
        jresearchimport('helpers.memberpositions', 'jresearch.admin');	
        $positions = JResearchMemberpositionsHelper::getMemberPositions();

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
                $positionOptions[] = JHTML::_('select.option', $position->id, $position->position);
        }

        return self::htmllist($positionOptions, $attributes);
    }

    /**
     * Renders a HTML generic select list with cooperations
     */
    public static function cooperations(array $attributes=array())
    {
        include_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'cooperations'.DS.'cooperations.php');

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
        include_once(JRESEARCH_COMPONENT_ADMIN.DS.'models'.DS.'financiers'.DS.'financiers.php');

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
        $publishedOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_STATE'));		
        $publishedOptions[] = JHTML::_('select.option', 'P', JText::_('JRESEARCH_PUBLISHED'));
        $publishedOptions[] = JHTML::_('select.option', 'U', JText::_('JRESEARCH_UNPUBLISHED'));

        return self::htmllist($publishedOptions, $attributes);
    }

    /**
     * Renders a HTML generic select list with status options
     */
    public static function statuslist(array $attributes=array())
    {
        //Status options
        $statusOptions = array();
        $statusOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_STATUS'));
        $statusOptions[] = JHTML::_('select.option', 'not_started', JText::_('JRESEARCH_NOT_STARTED'));
        $statusOptions[] = JHTML::_('select.option', 'in_progress', JText::_('JRESEARCH_IN_PROGRESS'));
        $statusOptions[] = JHTML::_('select.option', 'finished', JText::_('JRESEARCH_FINISHED'));

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
        $mainframe = JFactory::getApplication();		
        $base = JURI::root();
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
        jresearchimport('helpers.publications', 'jresearch.admin');
        $types = JResearchPublicationsHelper::getPublicationsSubtypes();
        $typesHTML = array();
        $typesHTML[] = JHTML::_('select.option', '-1', JText::_('JRESEARCH_PUBLICATION_TYPE'));
        foreach($types as $type){
                $text = JText::_('JRESEARCH_'.strtoupper($type));
            $typesHTML[] = JHTML::_('select.option', $type, $text);
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
     * It loads the library tag-it http://aehlke.github.io/tag-it/
     */
    public static function tagit() {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root().'administrator/components/com_jresearch/scripts/tag-it/css/jquery.tagit.css');
        $document->addStyleSheet(JURI::root().'administrator/components/com_jresearch/scripts/tag-it/css/tagit.ui-zendesk.css');
        $document->addScript(JURI::root().'administrator/components/com_jresearch/scripts/jquery-ui.min.js');
        $document->addScript(JURI::root().'administrator/components/com_jresearch/scripts/tag-it/js/tag-it.js');
    }

    /**
     * 
     * It loads the Javascript libraries required for the tagging/autocompletion
     * field.
     * @staticvar type $done
     * @param type $id
     * @param type $source
     * @param type $ordered
     * @return type
     */
    public static function tags($id, $source, $ordered) {
        static $done;

        if ($done === null) {
            $done = array();
        }
        

        // Only display the triggers once for each control.
        if (!in_array($id, $done))
        {
            $js = "
                function shiftValues(input, oldPosition, newPosition) {                    
                    var values = input.value.split(';');
                    var shift = 0;
                    if (oldPosition == newPosition) {
                        return;
                    }
                    
                    if (newPosition > oldPosition) {
                        shift = 1;
                    } else {
                        shift = -1;
                    }
                    for (i = oldPosition; i != newPosition; i = i + shift) {
                        var tmp = values[i];                    
                        values[i] = values[i + shift];
                        values[i + shift] = tmp;
                    }
                    input.value = values.join(';');                    
                }\n";
            
            $js .= " 
            $(function(){ $('#$id').tagit({
                singleField: true,
                allowSpaces : true,
                singleFieldDelimiter : ';',
                singleFieldNode: $('#$id'),
                autocomplete : {
                    delay: 0, 
                    minLength: 2,
                    source : function (request, response) {
                        $.ajax({
                            type: \"GET\",
                            dataType : \"json\",
                            url: \"?$source&keyword=\" + request.term,
                            cache: false,
                            success: function (data) { 
                                    response($.map(data, 
                                            function (item) { 
                                                return item
                                            })
                                    );
                            },
                            error: function (request, status, error) { 
                                alert(error); 
                            }
                        });
                    }
                },
                afterTagAdded: function(event, ui) {                    
                    var parts = ui.tagLabel.split('|');
                    if (parts.length <= 1) {
                        return;
                    }
                    var value = parts[0];
                    var label = parts[1];
                    // Get the input field
                    var inputField = document.getElementById('$id');
                    var elements = inputField.nextSibling.getElementsByTagName('li');
                    // Get the second last <li> element and replace its content
                    var targetElement = elements[elements.length - 2];
                    // Make the span draggable
                    targetElement.setAttribute('draggable', 'true');
                    targetElement.setAttribute('ondragstart', 'true');                    
                    targetElement.firstChild.innerHTML = label;
                },
                afterTagRemoved : function(event, ui){
                    var inputField = document.getElementById('$id');
                    var elements = inputField.value.split(';');
                    if (elements.length == 0) {
                        return;
                    }

                    var newElements = [];
                    for (i = 0; i < elements.length; ++i) {
                        var parts = elements[i].split('|');
                        var text = null;
                        if (parts.length > 1) {
                            text = parts[1];
                        } else {
                            text = parts;
                        }
                        if (text != ui.tagLabel) {                            
                            newElements.push(text);
                        }                        
                    }
                    inputField.value = newElements.join(';');
                }
            }); });            
            
            $(document).ready(function() { 
                var inputField = document.getElementById('$id');            
                inputField.nextSibling.setAttribute('id', 'ul_$id');   
                var newPosition;
                var originalPosition;
                $(function() {
                    $(\"#ul_$id\").sortable( {
                        items : 'li.tagit-choice',                        
                        
                        start : function(event, ui) {
                            originalPosition = ui.item.index();
                        },                         
                        
                        stop: function( event, ui ) {
                            var newPosition = ui.item.index();
                            shiftValues(document.getElementById('$id'), originalPosition, newPosition);
                        }
                    });
                    $(\"#ul_$id\" ).disableSelection();
                });
                
            });";
                        
            $document = JFactory::getDocument();
            $document->addScriptDeclaration($js);
            $done[] = $id;
        }
    }
}
?>
