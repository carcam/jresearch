<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Projects
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.utilities.date');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'financier.php');

/**
 * This class represents a JResearch project in database.
 *
 */
class JResearchProject extends JResearchActivity{
			
	
	/**
	 * Project's status. It can be: not_started, in_progress, finished
	 *
	 * @var string
	 */
	public $status;
	
	/**
	 * Proposed start date
	 *
	 * @var datetime
	 */
	public $start_date;
	
	/**
	* Proposed end date
	*
	* @var datetime
	*/	
	public $end_date;
	
	
	/**
	* Url to the an image that represents the project
	*
	* @var string
	*/
	public $url_project_image;	
	
	
	/**
	 * Url to the project official page
	 *
	 * @var string
	 */
	public $url_project_page;
	
	/**
	 * Project's complete description
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	 * Project's full funding value
	 *
	 * @var float
	 */
	public $funding;
	
	/**
	 * Fundings currency
	 *
	 * @var string
	 */
	public $funding_currency;
	
	protected $_funders;
	
	
	/**
	 * Class constructor. Maps the class to a Joomla table.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct( '#__jresearch_project', 'id', $db );
		$this->_type = 'project';
	}
	
	/**
	* Validates the content of the project's information.
	* @return boolean. True if all fields of the project have a valid content.
	*/	
	
	function check(){
		$date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
		
		// Verify the integrity of members
		if(!parent::checkAuthors())
			return false;
			
		// Validate dates	
		if(!empty($this->start_date)){
			if(!preg_match($date_pattern, $this->start_date)){
				$this->setError(JText::_('Please provide a proposed start date for the project in format YYYY-MM-DD'));
				return false;
			}
		}

		if(!empty($this->end_date)){
			if(!preg_match($date_pattern, $this->start_date)){
				$this->setError(JText::_('Please provide a proposed deadline for the project in format YYYY-MM-DD'));
				return false;
			}
		}
		
		if(!empty($this->end_date) && !empty($this->start_date)){
			$startDateObj = new JDate($this->start_date);
			$endDateObj = new JDate($this->end_date);
			
			if($endDateObj->toUnix() < $startDateObj->toUnix()){
				$this->setError(JText::_('Start date is greater than end date'));
				return false;
			}
		}
		
		if(!empty($this->funding))
		{
			$this->funding = round($this->funding, 2);
			
			if($this->funding <= 0.0)
			{
				$this->setError(JText::_('Funding must be greater than 0'));
			}
		}
			
			
		if(empty($this->title)){
			$this->setError(JText::_('Provide a title for the project'));
			return false;
		}	
		
		return true;

	}
	
	/**
	 * Loads a row from the database and binds the fields to the object properties.
	 *
	 * @param int $oid
	 * @return True if successful
	 */
	public function load($oid = null){
		$result = parent::load($oid);
		$this->_loadAuthors($oid);
		return $result;
	}
	
	/**
	 * Inserts a new row if id is zero or updates an existing row in the 
	 * database table.
	 *
	 * @param boolean $updateNulls If false, null object variables are not updated
	 * @return true if successful
	 */
	public function store($updateNulls = false){
		$isNew = $this->id?false:true;
		if($isNew){
			$now = new JDate();
			$this->created = $now->toMySQL();
		}		
		if(!parent::store($updateNulls))
			return false;

		$db = &$this->getDBO();
 		$j = $this->_tbl_key;		
			
		// Delete the information about internal and external references
		$deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_internal_author').' WHERE '.$db->nameQuote('id_project').' = '.$db->Quote($this->$j);
		$deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_external_author').' WHERE '.$db->nameQuote('id_project').' = '.$db->Quote($this->$j);
		
		$db->setQuery($deleteInternalQuery);
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}	
		
		$db->setQuery($deleteExternalQuery);
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		
		// Insert members' information
		$orderField = $db->nameQuote('order');
		$idPubField = $db->nameQuote('id_project');
       	$idStaffField = $db->nameQuote('id_staff_member');
       	
		foreach($this->_internalAuthors as $author){			
			$id_staff_member = $author['id_staff_member'];
			$order = $author['order'];
			$tableName = $db->nameQuote('#__jresearch_project_internal_author');
			$insertInternalQuery = "INSERT INTO $tableName($idPubField,$idStaffField,$orderField) VALUES ($this->id, $id_staff_member,$order)";
			$db->setQuery($insertInternalQuery);			
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getQuery());
				return false;
			}
		}
		
		$authorField = $db->nameQuote('author_name');
		foreach($this->_externalAuthors as $author){
			$order = $db->Quote($author['order'], false);
			$authorName = $db->Quote($db->getEscaped($author['author_name'], true), false);
			
			$tableName = $db->nameQuote('#__jresearch_project_external_author');
			$insertExternalQuery = "INSERT INTO $tableName($idPubField, $authorField, $orderField) VALUES($this->id, $authorName, $order)";			
			$db->setQuery($insertExternalQuery);
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getQuery());
				return false;
			}
		}     		
     
		/**
		 * @todo Add funders to project
		 * @author Florian Prinz
		 */
		
      	return true;
			
	}

	/**
	 * Sets a funder for the project
	 *
	 * @param int $funder
	 * @return bool
	 */
	public function setFunder($funder)
	{
		$this->_funders[] = array('id' => $this->id, 'id_financier' => $funder);
		
		return true;
	}
	
	/**
	 * Gets all funders, an array of financier objects
	 *
	 * @return array
	 */
	public function getFunders()
	{
		$db = &$this->getDBO();
		$funderObjects = array(); 
		
		foreach($this->_funders as $funder)
		{
			$funderObject = new JResearchFinancier($db);
			$funderObject->load($funder['id_financier']);
			$funderObjects[] = $funderObject;
		}
		
		return $funderObjects;
	}
	
	/**
	 * Counts the funders for this project
	 *
	 * @return int
	 */
	public function countFunders()
	{
		return count($this->_funders);
	}
}

?>