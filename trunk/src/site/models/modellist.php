<?php
/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport( 'joomla.application.component.modellist' );


/**
* Base class for models that hold lists of records.
*
*/
class JResearchModelList extends JModelList{

        protected $_context;

        protected $_items;
	
	/**
	* Class constructor.
	*/
	public function __construct(){
        $option = JRequest::getVar('controller');
        $Itemid = JRequest::getInt('Itemid', 0);
        $this->_context = 'com_jresearch.'.$option.($Itemid > 0? '.'.$Itemid : '');
        parent::__construct();
	}

	/**
    * Method to auto-populate the model state.
    *
    * This method should only be called once per instantiation and is designed
    * to be called on the first call to the getState() method unless the model
    * configuration flag to ignore the request is set.
    *
    * @return      void
    */
    protected function populateState() {
        // Initialize variables.
        $app = JFactory::getApplication('site');
        $params = $app->getParams();
        $controller = JRequest::getCmd('controller');

        // Load the list state.
        $this->setState('list.start', $app->getUserStateFromRequest($this->_context . '.list.start', 'limitstart', 0, 'int'));
        $this->setState('list.limit', $params->get($controller.'_entries_per_page', 25));
    }
}
?>
