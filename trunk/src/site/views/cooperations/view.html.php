<?php
jimport( 'joomla.application.component.view' );

/**
 * Cooperations View
 *
* @package		JResearch
* @subpackage	Cooperations
 */
class JResearchViewCooperations extends JResearchView
{
	/**
	 * Cooperations view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		global $mainframe;
		
		$layout = &$this->getLayout();
		
		switch($layout)
		{
		    case 'default':
		    default:
		        $this->_defaultList();
		        break;
		}
			
		$eArguments = array('cooperations', $layout);
		
		$mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
	}
	
	private function _defaultList()
	{
	    global $mainframe;
	    $doc    = &JFactory::getDocument();
	    
	    // Get data from the model
		$model 	= 	&$this->getModel();
		$items 	= 	$model->getData(null, true, true);
		$cats 	= 	$model->getCategories();
		$params =   $this->getParams();
		
		$doc->setTitle(JText::_('JRESEARCH_COOPERATIONS'));
		$this->assignRef('params', $params);
		$this->assignRef('items', $items, JResearchFilter::ARRAY_OBJECT_XHTML_SAFE, array('exclude_keys' => array('description')));
		$this->assignRef('cats', $cats);
		$this->assignRef('page', $model->getPagination());
		$this->assignRef('introtext', $params->get('intro_text'));
	}
}
?>