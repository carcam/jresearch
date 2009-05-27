<?php
class translationTeamleaderFilter extends translationFilter
{
	public function __construct($contentElement)
	{
		$this->filterNullValue = -1;
		$this->filterType = "teamleader";
		$this->filterField = $contentElement->getFilter("teamleader");
		
		parent::translationFilter($contentElement);
	}
	
	/**
	* Creates teamleader filter 
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
			
		$leaderOptions=array();
		$leaderOptions[] = JHTML::_('select.option', '-1', JText::_('All'));

		$sql = 	"SELECT DISTINCT leader.id, leader.firstname+' '+leader.lastname AS name FROM #__jresearch_member as leader, #__".$this->tableName.' as c'
				."WHERE c.".$this->filterField."=leader.id ORDER BY leader.lastname";
		
		$db->setQuery($sql);
		$leaders = $db->loadObjectList();
		
		$leaderCount=0;
		foreach($leaders as $leader)
		{
			$leaderOptions[] = JHTML::_('select.option', $leader->id,$leader->name);
			$leaderCount++;
		}
		
		$leaderList=array();
		$leaderList["title"]= JText::_('Team-Leader filter');
		$leaderList["html"] = JHTML::_('select.genericlist', $leaderOptions, 'teamleader_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );
		
		return $leaderList;
	}
}
?>