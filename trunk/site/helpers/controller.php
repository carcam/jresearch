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
     * @return bool
     */
    public function addPathwayItem($name, $link='')
    {
        global $mainframe;
        
        $itemid = JRequest::getVar('Itemid', null);
        
        if(is_null($itemid))
        {
            $pathway = &$mainframe->getPathway();
            return $pathway->addItem($name, $link);
        }
        
        return false;
    }
}
?>