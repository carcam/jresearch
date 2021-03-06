<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Configuration
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_COMPONENT_ADMINISTRATOR.'/includes/view.php';

/**
 * HTML View class for configuration form in JResearch Component backend
 */

class JResearchAdminViewConf extends JResearchView
{
    function display($tpl = null)
    {
    	JHTML::_('behavior.modal');
    	
    	if($this->getLayout() == 'help'){
            JResearchToolbar::helpToolbar();
            $langObj =  JFactory::getLanguage();
            $this->assignRef('langtag', $langObj->_lang);
    	} else {	
            JResearchToolbar::controlPanelToolbar();
        }
    	parent::display($tpl);
    }
}
?>