<?php
/**
 * @package	JResearch
 * @subpackage	Translations
 * @copyright	2014, Carlos Cámara.
 * @license		GNU/GPL
 */
defined('_JEXEC') or die( 'Restricted access' );

class translationResearchareaFilter extends translationFilter
{
	public function __construct($contentElement)
	{
		$this->filterNullValue = -1;
		$this->filterType = "researcharea";
		$this->filterField = $contentElement->getFilter("researcharea");
		
		parent::translationFilter($contentElement);
	}
	
	/**
	* Creates researcharea filter 
	*
	* @param unknown_type $filtertype
	* @param unknown_type $contentElement
	* @return unknown
	*/
	function _createfilterHTML()
	{
		$db =& JFactory::getDBO();
		
		if (!$this->filterField)
			return "";
			
		$areaOptions=array();
		$areaOptions[] = JHTML::_('select.option', '-1', JText::_('All Sections'));
		$areaOptions[] = JHTML::_('select.option', '0', JText::_('Uncategorized'));

		$sql = 	"SELECT DISTINCT area.id, area.title FROM #__jresearch_research_area as area, #__".$this->tableName." as c "
				."WHERE c.".$this->filterField."=area.id ORDER BY area.name";
		
		$db->setQuery($sql);
		$areas = $db->loadObjectList();
		
		$areaCount=0;
		if(count($areas) > 0)
			foreach($areas as $area)
			{
				$areaOptions[] = JHTML::_('select.option', $area->id,$area->name);
				$areaCount++;
			}
		
		$areaList=array();
		$areaList["title"]= JText::_('Researcharea filter');
		$areaList["html"] = JHTML::_('select.genericlist', $areaOptions, 'area_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );
		
		return $areaList;
	}
}
?>