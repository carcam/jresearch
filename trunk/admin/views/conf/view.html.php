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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for configuration form in JResearch Component backend
 */

class JResearchAdminViewConf extends JView
{
    function display($tpl = null)
    {
    	JHTML::_('behavior.modal');
    	
    	if($this->getLayout() == 'help'){
    		JResearchToolbar::helpToolbar();
    		$doc = JFactory::getDocument();
    		$prefix = str_replace($doc->getLanguage(), '_', '-');
    		$this->assignRef('langprefix', $prefix);
    	}else	
    		JResearchToolbar::controlPanelToolbar();

    	parent::display($tpl);
    }
}

?>
