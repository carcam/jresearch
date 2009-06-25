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
		$arguments = array('cooperation');
		
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
		
		$arguments[] = $id;
		
		$editor =& JFactory::getEditor();
		switch($layout)
		{
			case "edit":
				$this->_editCooperation($item);
				break;
			default:
				JResearchPluginsHelper::onPrepareJResearchContent('cooperation', $item);
				break;
		}

		$doc->setTitle(JText::_('JRESEARCH_COOPERATION').' - '.$item->name);
		$this->assignRef('coop', $item, JResearchFilter::OBJECT_XHTML_SAFE);
		$this->assignRef('editor', $editor);

		$mainframe->triggerEvent('onBeforeDisplayJResearchEntity', $arguments);
		
       	parent::display($tpl);
       	
       	$mainframe->triggerEvent('onAfterRenderJResearchEntity', $arguments);
	}
	
	private function _editCooperation(&$coop)
	{
		JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');
		JHTML::_('Validator._');
		
		//Published options
    	$publishedOptions = array();
    	$publishedOptions[] = JHTML::_('select.option', '1', JText::_('Yes'));    	
    	$publishedOptions[] = JHTML::_('select.option', '0', JText::_('No'));    	
    	$publishedRadio = JHTML::_('select.genericlist', $publishedOptions ,'published', 'class="inputbox"' ,'value', 'text' , $coop->published);

    	$orderOptions = array();
    	$orderOptions = JHTML::_('list.genericordering','SELECT ordering AS value, name AS text FROM #__jresearch_cooperations ORDER by ordering ASC');
    	$orderList = JHTML::_('select.genericlist', $orderOptions ,'ordering', 'class="inputbox"' ,'value', 'text' , $coop->ordering);
    	
    	$this->assignRef('publishedRadio', $publishedRadio);
    	$this->assignRef('orderList', $orderList);

	}
}
?>