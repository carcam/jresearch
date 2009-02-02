<?php
class translationPublicationFilter extends translationFilter
{
	public function __construct($contentElement)
	{
		$this->filterNullValue = -1;
		$this->filterType = "publication";
		$this->filterField = $contentElement->getFilter("publication");
		
		parent::translationFilter($contentElement);
	}
	
	/**
	* Creates publication filter 
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
		$areaOptions[] = JHTML::_('select.option', '-1', JText::_('All Publications'));

		$sql = 	"SELECT DISTINCT pub.id, pub.title FROM #__jresearch_publication as pub, #__".$this->tableName.' as c'
				."WHERE c.".$this->filterField."=pub.id ORDER BY pub.id";
		
		$db->setQuery($sql);
		$pubs = $db->loadObjectList();
		
		$pubCount=0;
		foreach($pubs as $pub)
		{
			$pubOptions[] = JHTML::_('select.option', $pub->id,$pub->name);
			$pubCount++;
		}
		
		$pubList=array();
		$pubList["title"]= JText::_('Publication filter');
		$pubList["html"] = JHTML::_('select.genericlist', $pubOptions, 'pub_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );
		
		return $pubList;
	}
}
?>