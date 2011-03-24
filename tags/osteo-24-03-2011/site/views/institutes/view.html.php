<?php
jimport( 'joomla.application.component.view' );

/**
 * Cooperations View
 *
* @package		JResearch
* @subpackage	Institutes
 */
class JResearchViewInstitutes extends JResearchView
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
			
		$eArguments = array('institutes', $layout);
		
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
		$params =   $this->getParams();
		
		$doc->setTitle(JText::_('JRESEARCH_INSTITUTES'));
		$this->assignRef('params', $params);
		$this->assignRef('items', $items, JResearchFilter::ARRAY_OBJECT_XHTML_SAFE, array('exclude_keys' => array('comment')));
		$this->assignRef('page', $model->getPagination());
	}
}
?>