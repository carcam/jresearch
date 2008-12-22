<?php
jimport( 'joomla.application.component.view' );

/**
 * Single Cooperation View
 *
* @package		JResearch
* @subpackage	Cooperations
 */
class JResearchViewCooperation extends JView
{
	/**
	 * Cooperation view display method
	 * @return void
	 **/
	function display($tpl = null)
	{	
		$id = JRequest::getInt('id');
		$layout =& $this->getLayout();
		
		// Get data from the model
		$model = &$this->getModel();
		$item = $model->getItem($id);
		
		$editor =& JFactory::getEditor();
		
		switch($layout)
		{
			case "edit":
				$this->_editCooperation($item);
				break;
			default:
				break;
		}

		$this->assignRef('coop', $item);
		$this->assignRef('editor', $editor);

		parent::display($tpl);
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