<?php
/**
 * @package JResearch
 * @subpackage Helpers
 * @author Florian Prinz
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
}

class JResearchView extends JView
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
            $val->raw = $val;
            JFilterOutput::objectHTMLSafe($val, $config['quote_style'], $config['exclude_keys']);
            return true;
        }
        
        return false;
    }
}
?>