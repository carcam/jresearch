<?php
/**
 * @package JResearch
 * @subpackage Helpers
 * @author Florian Prinz
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class JResearchFrontendController extends JController
{
    public function __construct ($config = array())
    {
        parent::__construct($config);
    }
    
	/**
     * Adds a pathway item to the current pathway if no ItemId exists
     *
     * @param string $name
     * @param string $link
     * @param bool $bItemid Parameter for adding Pathway item if ItemId is set or not
     * @return bool
     */
    public function addPathwayItem($name, $link='', $bItemid = false)
    {
        $mainframe = JFactory::getApplication();
        
        $itemid = JRequest::getVar('Itemid', null);
        
        if((is_null($itemid) || $bItemid) && !$mainframe->isAdmin())
        {
            $pathway = &$mainframe->getPathway();
            return $pathway->addItem($name, $link);
        }
        
        return false;
    }
}
?>