<?php
/**
 * @package JResearch
 * @subpackage Helpers
 * @author Florian Prinz
 * @license	GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

final class JResearchFilter
{
    const NO_FILTER = 0;
    const URL_ENCODE = 1;
    const HTML_ENTITIES = 2;
    const OBJECT_XHTML_SAFE = 4;
    const ARRAY_OBJECT_XHTML_SAFE = 8;
}

class JResearchView extends JViewLegacy
{

    public function __construct(array $config=array())
    {
        parent::__construct($config);
    }
    
    /**
     * This is a overriden method of JView which offers a way to filter the value which has been added to the view
     * 
     * @see JView::assignRef()
     *
     * @param string $key
     * @param mixed $val
     * @param int $filter Possible values are Filter constants from JResearchFilter
     * @param array $config
     * @return bool
     */
    public function assignRef($key, &$val='', $filter=JResearchFilter::NO_FILTER, array $config=array())
    {
        switch($filter)
        {
            case JResearchFilter::URL_ENCODE:
                $this->_filterUrlEncode($val);
                break;
            case JResearchFilter::HTML_ENTITIES:
                $this->_filterHtmlEntities($val);
                break;
            case JResearchFilter::OBJECT_XHTML_SAFE:
                $this->_filterObjectXHtmlSafe($val, $config);
                break;
                
            case JResearchFilter::ARRAY_OBJECT_XHTML_SAFE:
            	if(is_array($val))
            		$this->_filterArrayObjectXHtmlSafe($val, $config);
            	break;
                
            case JResearchFilter::NO_FILTER:
            default:
                break;
        }
        
        return parent::assignRef($key, $val);
    }
    
    private function _filterUrlEncode(&$val)
    {
        if(is_string($val))
        {
            $val = JFilterOutput::stringURLSafe(trim($val));
            return true;
        }
        
        return false;
    }
    
    private function _filterHtmlEntities(&$val)
    {
        if(is_string($val))
        {
            JFilterOutput::cleanText(trim($val));
            return true;
        }
        
        return false;
    }
    
    /**
     * Filters the whole object to make the value XHTML safe. Raw value will be added by the function
     *
     * @param object $val
     * @param array $config
     * @return bool
     */
    private function _filterObjectXHtmlSafe(&$val, array $config=array())
    {
        if(is_object($val))
        {
            $raw = $val;
            $quote_style = array_key_exists('quote_style', $config)?$config['quote_style']:ENT_QUOTES;
            $exclude_keys = array_key_exists('exclude_keys', $config)?$config['exclude_keys']:'';
            
            JFilterOutput::objectHTMLSafe($val, $quote_style, $exclude_keys);
            $val->raw = $raw;
            return true;
        }
        
        return false;
    }
    
	/**
     * Filters the whole object to make the value XHTML safe. Raw value will be added by the function
     *
     * @param object $val
     * @param array $config
     * @return bool
     */
    private function _filterArrayObjectXHtmlSafe(array &$val, array $config=array())
    {
    	foreach($val as &$object)
    	{
	        $this->_filterObjectXHtmlSafe($object, $config);
    	}
        
        return false;
    }

    /**
     * Adds a pathway item to the current pathway if no ItemId exists
     *
     * @param string $name
     * @param string $link
     * @param bool $bItemid 
     * @return bool
     */
    public function addPathwayItem($name, $link='', $bItemid = false)
    {
        global $mainframe;
        
        $itemid = JRequest::getVar('Itemid', null);
        
        if(is_null($itemid) || $bItemid)
        {
            $pathway = &$mainframe->getPathway();
            return $pathway->addItem($name, $link);
        }
        
        return false;
    }
    
    /*
     * Returns merged parameter object
     */
    public function getParams()
    {
    	global $mainframe;
    	
    	$params =& JComponentHelper::getParams('com_jresearch');
    	$itemid = JRequest::getVar('Itemid', null);
    	
    	if($itemid != null && !$mainframe->isAdmin())
    	{
    		$menu = JSite::getMenu();
    		$mparams = $menu->getParams($itemid);
    		
    		$params->merge($mparams);
    	}
    	
    	return $params;
    }
}
?>