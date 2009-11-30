<?php
jimport( 'joomla.application.component.view' );

/**
 * Single Institute View
 *
* @package		JResearch
* @subpackage	Institutes
 */
class JResearchViewInstitute extends JResearchView
{
	/**
	 * Cooperation view display method
	 * @return void
	 **/
	function display($tpl = null)
	{	
		global $mainframe;
		$arguments = array('institute');
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
		
		if(empty($item)){
			JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			return;			
		}
		
		$this->addPathwayItem($item->alias, 'index.php?option=com_jresearch&view=institute&id='.$item->id);
		$arguments[] = $id;
		
		$editor =& JFactory::getEditor();
		switch($layout)
		{
			case "edit":
				$this->_editInstitute($item);
				break;
			default:
				break;
		}

		$doc->setTitle(JText::_('JRESEARCH_INSTITUTE').' - '.$item->name);
		$comment = explode('<hr id="system-readmore" />', $item->comment);
		
		$this->assignRef('institute', $item, JResearchFilter::OBJECT_XHTML_SAFE);
		$this->assignRef('commentn', $comment);
		$this->assignRef('editor', $editor);
		$this->assignRef('params', $params);

		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
	}
	
	private function _editCooperation(&$coop)
	{
		JHTML::addIncludePath(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'html');
		
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $coop->published);

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_cooperations ORDER by ordering ASC');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , $coop->ordering);
    	
    	$this->addPathwayItem(JText::_('Edit'));
    	
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('orderList', $orderList);
	}
}
?>