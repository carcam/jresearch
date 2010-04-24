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
 * JHTML helper class that implements a control for selecting the journal of 
 * an article.
 *
 */
class JHTMLJournalsControl{
	
	
	static function journalsControl($baseName, $value = null, $selectFromList = true){
		global $mainframe;
		$doc = JFactory::getDocument();
		$db = JFactory::getDBO();
		$inputTextLabel = JText::_('JRESEARCH_ENTER_JOURNAL_MANUALLY'); 
		$selectListLabel = JText::_('JRESEARCH_SELECT_JOURNAL_FROM_LIST');
		//Journals 
		$db->setQuery('SELECT * FROM '.$db->nameQuote('#__jresearch_journals').' WHERE '.$db->nameQuote('published').' = 1 ');
		$journals = $db->loadAssocList();
		$journalsOptions = array();
		
		$journalsOptions[] = JHTML::_('select.option', '0', JText::_('JRESEARCH_ALL_JOURNALS'));
		foreach($journals as $journal){
			$journalsOptions[] = JHTML::_('select.option', $journal['id'], $journal['title']);
		}

                $js = 'onchange="javascript:bringImpactFactor();"';
		
		if($selectFromList){
			$journalsHTML = JHTML::_('select.genericlist', $journalsOptions, 'list_'.$baseName, 'class="inputbox" id="list_'.$baseName.'" style="display:inline;" '.$js, 'value', 'text', $value);
			$journalsHTML .= '<span></span>'.'<input type="text" size="15" style="display:none;" name="'.$baseName.'" id="'.$baseName.'" />';
			$mode = 'inputtext';
			$label = $inputTextLabel;
		}else{
			$mode = 'journalslist';
			$label = $selectListLabel;
			$journalsHTML = JHTML::_('select.genericlist', $journalsOptions, 'list_'.$baseName, 'class="inputbox" id="list_'.$baseName.'" style="display:none;" '.$js, 'value', 'text', $value);
			$journalsHTML .= '<span></span>'.'<input type="text" size="15" style="display:inline;" name="'.$baseName.'" value="'.$value.'" id="'.$baseName.'" />';			
		}	
		$switchControl = '<a name="a_'.$baseName.'" id="a_'.$baseName.'" href="javascript:switchTo(\''.$mode.'\')">'.$label.'</a>';
		$doc->addScriptDeclaration("
			document.jclbl_inputtext = '$inputTextLabel';
			document.jclbl_selectlist = '$selectListLabel';

                        function bringImpactFactor(){
                            var selectedIndex = document.forms['adminForm'].list_journal.selectedIndex;
                            var journalId = document.forms['adminForm'].list_journal.options[selectedIndex].value;
                            if(journalId != '0'){
                                var request = new XHR({method: 'get', onSuccess: mapImpactFactor});
                                request.send('index.php?option=com_jresearch&controller=journals&task=getImpactFactor&format=raw&journalId='+journalId);
                            }
                        }

                        function mapImpactFactor(responseText, responseXML){
                            if(responseText != ''){
                                journalId = document.forms['adminForm'].impact_factor.value = responseText;
                            }
                        }
			
			function switchTo(mode){
				switchControl = document.getElementById('a_$baseName');
				listControl = document.forms['adminForm'].list_$baseName;
				inputControl = document.forms['adminForm'].$baseName;				
				if(switchControl && listControl && inputControl){
					if(mode == 'journalslist'){
						listControl.style.display = 'inline';
						inputControl.style.display = 'none';
						switchControl.setAttribute('href', 'javascript:switchTo(\'inputtext\')');
						switchControl.removeChild(switchControl.firstChild);		
						switchControl.appendChild(document.createTextNode(document.jclbl_inputtext));
						document.forms['adminForm'].impact_factor.value = '';
					}else{
						listControl.style.display = 'none';
						inputControl.style.display = 'inline';										
						switchControl.setAttribute('href', 'javascript:switchTo(\'journalslist\')');
						switchControl.removeChild(switchControl.firstChild);						
						switchControl.appendChild(document.createTextNode(document.jclbl_selectlist));
					}
				}
			}
		");
		
		$result = '<div id="div_'.$baseName.'">'.$journalsHTML.'<div />'.$switchControl.'</div>';		
		return $result;
		
	}	
}

?>
