<?php
/**
* @version		$Id$
* @package		J!Research
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
    	JResearchToolbar::controlPanelToolbar();
        parent::display($tpl);
    }
}

?>
