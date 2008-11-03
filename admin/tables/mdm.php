<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	MtM
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
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