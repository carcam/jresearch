<?php
class translationPublicationtypeFilter extends translationFilter
{
	public function __construct($contentElement)
	{
		$this->filterNullValue = -1;
		$this->filterType = "publicationtype";
		$this->filterField = $contentElement->getFilter("publicationtype");
		
		parent::translationFilter($contentElement);
	}
	
	/**
	* Creates publication type filter 
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
			
		$typeOptions=array();
		$typeOptions[] = JHTML::_('select.option', '-1', JText::_('All Publications'));

		$sql = 	"SELECT DISTINCT type.name FROM #__jresearch_publication_type as type, #__".$this->tableName." as c "
				."WHERE c.".$this->filterField."=type.name ORDER BY type.name";
		
		$db->setQuery($sql);
		$types = $db->loadObjectList();
		
		$typeCount=0;
		if(count($types) > 0)
			foreach($types as $type)
			{
				$typeOptions[] = JHTML::_('select.option', $type->name,$type->name);
				$typeCount++;
			}
		
		$typeList=array();
		$typeList["title"]= JText::_('Publication type filter');
		$typeList["html"] = JHTML::_('select.genericlist', $typeOptions, 'pubtype_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );
		
		return $typeList;
	}
}
?>