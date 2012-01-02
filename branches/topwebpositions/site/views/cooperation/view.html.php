<?php
jimport( 'joomla.application.component.view' );

/**
 * Single Cooperation View
 *
* @package		JResearch
* @subpackage	Cooperations
 */
class JResearchViewCooperation extends JResearchView
{
	/**
	 * Cooperation view display method
	 * @return void
	 **/
	function display($tpl = null)
	{	
            global $mainframe;
            $params = $this->getParams();

            $id = JRequest::getInt('id');
            $layout =& $this->getLayout();
            $doc = JFactory::getDocument();

            if(empty($id)){
                JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
                return;
            }

            // Get data from the model
            $model = &$this->getModel();
            $item = $model->getItem($id);

            if(empty($item) || !$item->published){
                JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
                return;
            }

            $this->addPathwayItem($item->alias, 'index.php?option=com_jresearch&view=cooperation&id='.$item->id);

            $editor =& JFactory::getEditor();
            switch($layout)
            {
                case "edit":
                    $this->_editCooperation($item);
                    break;
                default:
                    break;
            }

            $doc->setTitle(JText::_('JRESEARCH_COOPERATION').' - '.$item->name);
            $description = explode('<hr id="system-readmore" />', $item->description);

            $arguments = array('cooperation', $item);
            $mainframe->triggerEvent('onPrepareJResearchContent', $arguments);

            $this->assignRef('coop', $item, JResearchFilter::OBJECT_XHTML_SAFE);
            $this->assignRef('description', $description);
            $this->assignRef('editor', $editor);
            $this->assignRef('params', $params);

            parent::display($tpl);
            $mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
	}
	
	private function _editCooperation(&$coop)
	{
            JHTML::addIncludePath(JRESEARCH_COMPONENT_SITE.DS.'helpers'.DS.'html');
            $doc = JFactory::getDocument();
			$doc->addScriptDeclaration('
				function msubmitform(pressbutton){
					if (pressbutton) {
						document.adminForm.task.value=pressbutton;
					}
					if (typeof document.adminForm.onsubmit == "function") {
						if(!document.adminForm.onsubmit())
						{
							return;
						}
						else
						{
							document.adminForm.submit();
						}
				}
				else
				{
					document.adminForm.submit();
				}
			}');            
		
            //Published options
            $publishedOptions = array();
            $publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));
            $publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));
            $publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $coop->published);

            $orderOptions = array();
            $orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_cooperations ORDER by ordering ASC');
            $orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , $coop->ordering);
	    	$categoryList = JHTML::_('list.category', 'catid', 'com_jresearch_cooperations', $coop->catid);            

            $this->addPathwayItem(JText::_('Edit'));

	    	$this->assignRef('categoryList', $categoryList);            
            $this->assignRef('publishedList', $publishedRadio);
            $this->assignRef('orderList', $orderList);
	}
}
?>