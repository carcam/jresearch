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

/**
 * JHTML helper class that implements a control for selecting the journal of 
 * an article.
 *
 */
class JHTMLProjectsControl{
	
	static function toogleControl($baseName, $value = null, $tooltip = ''){
		//Published options
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration("
			function switchTextField$baseName(){
				if(document.forms['adminForm'].boolean_$baseName.value == 'yes')
					document.forms['adminForm'].$baseName.style.display = 'inline';
				else
					document.forms['adminForm'].$baseName.style.display = 'none';
			}					
		");
    	$booleanOptions = array();
    	$booleanOptions[] = JHTML::_('select.option', 'yes', JText::_('JRESEARCH_YES'));    	
    	$booleanOptions[] = JHTML::_('select.option', 'no', JText::_('JRESEARCH_NO'));    	
    	$booleanRadio = JHTML::_('select.genericlist', $booleanOptions , 'boolean_'.$baseName, 'class="inputbox" onchange="javascript:switchTextField'.$baseName.'();"' ,'value', 'text' , !empty($value)? 'yes': 'no');   	
    	if(empty($value))
	    	$baseControl = '<input type="text" size="20" name="'.$baseName.'" id="'.$baseName.'" style="display:none" />';
	    else{
			$baseControl = '<input type="text" size="20" name="'.$baseName.'" id="'.$baseName.'" style="display:inline" value="'.$value.'" />';
			if(!empty($tooltip)){
				$tooltipText = JHTML::_('tooltip', $tooltip);
			}	
			$baseControl .= $tooltipText;
	    }

		return $booleanRadio.'<span class="style:width: 5px;" />'.$baseControl;	
	}
	
	static function financiersControl($baseName, $value = null){
		global $mainframe;
		$doc = JFactory::getDocument();
		$db = JFactory::getDBO();

		//Financier options
		$db->setQuery('SELECT * FROM '.$db->nameQuote('#__jresearch_financier').' WHERE '.$db->nameQuote('published').' = 1');
    	$financiers = $db->loadAssocList();
		$financierOptions = array();
    	$financierOptions[0] = JHTML::_('select.option', '', JText::_('JRESEARCH_PROJECT_NO_FINANCIERS'));
    	foreach($financiers as $fin){
    		$financierOptions[] = JHTML::_('select.option', $fin['id'], $fin['name']);
    	}    	
		
    	//Published options
    	$typeOptions = array();
    	$typeOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_PROJECT_TYPE_FIN'));    	    	
    	$typeOptions[] = JHTML::_('select.option', 'private', JText::_('JRESEARCH_PROJECT_PRIVATE_FIN'));    	
    	$typeOptions[] = JHTML::_('select.option', 'public', JText::_('JRESEARCH_PROJECT_PUBLIC_FIN'));    	
    	    	
    	if(empty($value)){
    		$type = '0';
			$finHTML = JHTML::_('select.genericlist', $financierOptions, $baseName.'[]', 'class="inputbox" size="3" multiple="multiple" style="display:none;" id="'.$baseName.'"', 'value', 'text', '');
			$finHTML.= '<div /><input style="display:none;" type="text" size="30" maxlenght="255" id="text_'.$baseName.'" name="text_'.$baseName.'" />';
    	}elseif(is_array($value)){
    		$type = 'private';
			$finHTML = JHTML::_('select.genericlist', $financierOptions, $baseName.'[]', 'class="inputbox" size="3" multiple="multiple" style="display:inline;" id="'.$baseName.'"', 'value', 'text', (count($value) > 0) ? $value : '');
			$finHTML.= '<div /><input style="display:none;" type="text" size="30" maxlenght="255" id="text_'.$baseName.'" name="text_'.$baseName.'"  />';		
    	}elseif(is_string($value)){
    		$type = 'public';
			$finHTML = JHTML::_('select.genericlist', $financierOptions, $baseName.'[]', 'class="inputbox" size="3" multiple="multiple" style="display:none;" id="'.$baseName.'"', 'value', 'text', '');
			$finHTML.= '<div /><input style="display:inline;" type="text" size="30" maxlenght="255" id="text_'.$baseName.'" name="text_'.$baseName.'" value="'.$value.'" />';		
    	}   
    	 	
    	$typeRadio = JHTML::_('select.genericlist', $typeOptions , 'type_'.$baseName, 'class="inputbox" onchange="javascript:switchFinMode();"' ,'value', 'text' , $type);   	
    	$finHTML = $typeRadio.' '.$finHTML;
    			
		$doc->addScriptDeclaration("
			function switchFinMode(){
				typeControl = document.forms['adminForm'].type_$baseName;
				baseControl = document.getElementById('$baseName');
				if(typeControl.value == 'public'){
					baseControl.style.display = 'none';
					baseControl.selectedIndex = 0;
					document.forms['adminForm'].text_$baseName.style.display = 'inline';					
				}else if(typeControl.value == 'private'){
					baseControl.style.display = 'inline';
					document.forms['adminForm'].text_$baseName.style.display = 'none';
					document.forms['adminForm'].text_$baseName.value = '';
				}else{
					baseControl.style.display = 'none';
					document.forms['adminForm'].text_$baseName.style.display = 'none';
					document.forms['adminForm'].text_$baseName.value = '';				
					baseControl.selectedIndex = 0;						
				}				
			}
		");
		
		return $finHTML;
		
	}	
}

?>