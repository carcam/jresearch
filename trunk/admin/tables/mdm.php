<?php
/**
 * This class represents a staff member.
 *
 */

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');

class JResearchMdm extends JTable
{
	public $id;
	public $id_member;
	public $month;
	public $description;
	public $published;
	public $checked_out;
	public $checked_out_time;
	
	/**
	 * Class constructor. Maps the class to a Joomla table.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db)
	{
		parent::__construct( '#__jresearch_mdm', 'id', $db );
	}
	
	function check()
	{
		$date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
		
		if(!empty($this->month))
		{
			if(!preg_match($date_pattern, $this->month))
			{
				$this->setError(JText::_('Please provide a proposed date for the month in format YYYY-MM-DD'));
				return false;
			}
		}
		else
		{
			$this->setError(JText::_('Date must be set'));
			return false;
		}
			
		if(!$this->checkMember())
		{
			$this->setError(JText::_('Please ensure that the member is a valid staff member'));
			return false;
		}
			
		return true;
	}
	
	function checkMember()
	{
		$db =& JFactory::getDBO();
		$member = new JResearchMember($db);
		
		return ($member->load($this->id_member) != false);
	}
}
?>