<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage           HTML
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

                        function bringImpactFactor(year){
                            var selectedIndex = document.forms['adminForm'].list_journal.selectedIndex;
                            var journalId = document.forms['adminForm'].list_journal.options[selectedIndex].value;
                            if(journalId != '0'){
                                var request = new XHR({method: 'get', onSuccess: mapImpactFactor});
                                request.send('index.php?option=com_jresearch&controller=journals&task=getImpactFactor&format=raw&journalId='+journalId+'&year='+year);
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

        static function journalhistory($name, $history = array()){
            $output = '<ul style="list-style:none"; id="ul'.$name.'">';
            static $loaded = false;

            if(!$loaded){
                $loaded = true;
                $msgYear = JText::_('JRESEARCH_JOURNAL_PROVIDE_VALID_YEAR');
                $msgFactor = JText::_('JRESEARCH_JOURNAL_PROVIDE_VALID_IMPACT_FACTOR');
                $labelYear = JText::_('JRESEARCH_YEAR').':  ';
                $labelFactor = JText::_('JRESEARCH_IMPACT_FACTOR').':  ';
                $labelRemove = JText::_('JRESEARCH_REMOVE');

                $doc = JFactory::getDocument();
                $doc->addScriptDeclaration("
                   function addentry(name){
                        var parentlist = document.getElementById('ul'+name);

                        if(parentlist){
                            var countInput = document.getElementById(name+'_count');
                            if(!countInput)
                                return;

                            var count = parseInt(countInput.getAttribute('value'));

                            var newli = document.createElement('li');
                            newli.setAttribute('id', 'li'+name+count);
                            newli.setAttribute('name', 'li'+name+count);

                            var yearInput = document.createElement('input');
                            yearInput.setAttribute('id', name+'year'+count);
                            yearInput.setAttribute('name', name+'year'+count);
                            yearInput.setAttribute('size', '4');
                            yearInput.setAttribute('maxlength', '4');
                            yearInput.setAttribute('type', 'text');
                            yearInput.setAttribute('class', 'validate-year');

                            var labelYear = document.createElement('label');
                            labelYear.setAttribute('for', name+'year'+count);
                            labelYear.setAttribute('class', 'labelform');
                            labelYear.setAttribute('className', 'labelform');
                            labelYear.appendChild(document.createTextNode('$msgYear'));

                            var factorInput = document.createElement('input');
                            factorInput.setAttribute('id', name+'factor'+count);
                            factorInput.setAttribute('name', name+'factor'+count);
                            factorInput.setAttribute('size', '6');
                            factorInput.setAttribute('maxlength', '8');
                            factorInput.setAttribute('type', 'text');
                            factorInput.setAttribute('class', 'validate-quantity');

                            var labelFactor = document.createElement('label');
                            labelFactor.setAttribute('for', name+'factor'+count);
                            labelFactor.setAttribute('class', 'labelform');
                            labelFactor.setAttribute('className', 'labelform');
                            labelFactor.appendChild(document.createTextNode('$msgFactor'));

                            var removeLink = document.createElement('a');
                            removeLink.setAttribute('href', 'javascript:removeentry(\''+name+'\', '+ count + ')');
                            removeLink.appendChild(document.createTextNode('$labelRemove'));

                            newli.appendChild(document.createTextNode('$labelYear'));
                            newli.appendChild(yearInput);
                            newli.appendChild(labelYear);
                            newli.appendChild(document.createElement('br'));
                            newli.appendChild(document.createTextNode('$labelFactor'));
                            newli.appendChild(factorInput);
                            newli.appendChild(labelFactor);
                            newli.appendChild(document.createElement('br'));
                            newli.appendChild(removeLink);

                            countInput.setAttribute('value', count + 1);
                            parentlist.appendChild(newli);
                        }
                   }

                   function removeentry(name, id){
                        var lielement = document.getElementById('li'+name+id);
                        if(lielement){
                            var parentelement = lielement.parentNode;
                            parentelement.removeChild(lielement);
                        }
                   }
                ");
            }

            $j = 0;
            foreach($history as $year => $factor){
                $output .= '<li id="li'.$name.$j.'">';
                $output .=  $labelYear;
                $output .= '<input type="text" name="'.$name.'year'.$j.'" id="'.$name.'year'.$j.'" size="4" maxlength="4" class="validate-year" value="'.$year.'" />';
                $output .= '<label for="'.$name.'year'.$j.'" class="labelform" >'.$msgYear.'</label>';
                $output .= '<br />';
                $output .= $labelFactor;
                $output .= '<input type="text" name="'.$name.'factor'.$j.'" id="'.$name.'factor'.$j.'" size="6" maxlength="8" class="validate-quantity" value="'.$factor.'" />';
                $output .= '<label for="'.$name.'year'.$j.'" class="labelform" >'.$msgFactor.'</label>';
                $output .= '<br />';
                $output .= '<a href="javascript:removeentry(\''.$name.'\', '.$j.')">'.JText::_('JRESEARCH_REMOVE').'</a>';
                $output .= '</li>';
                $j++;
            }
            $output .= '</ul>';
            $output .= '<a href="javascript:addentry(\''.$name.'\')">'.JText::_('JRESEARCH_ADD').'</a>';
            $output .= '<input type="hidden" name="'.$name.'_count" id="'.$name.'_count" value="'.$j.'" />';


            return $output;

        }
}

?>
