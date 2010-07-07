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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'activity.php');
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
	public $finance_value;
	
	/**
	 * Fundings currency
	 *
	 * @var string
	 */
	public $finance_currency;
	
	/**
	 * Holds financiers of the project
	 * @var array
	 */
	protected $_financiers = array();
	
	/**
	 * Holds cooperations of the project
	 * @var array
	 */
	protected $_cooperations = array();
	
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
		
		if((!empty($this->end_date) && $this->end_date != '0000-00-00') && (!empty($this->start_date) && $this->start_date != '0000-00-00')){
			$startDateObj = new JDate($this->start_date);
			$endDateObj = new JDate($this->end_date);
			
			if($endDateObj->toUnix() < $startDateObj->toUnix()){
				$this->setError(JText::_('Start date is greater than end date'));
				return false;
			}
		}
		
		if(!empty($this->finance_value))
		{
			$this->finance_value = round($this->finance_value, 2);
			
			if($this->finance_value <= 0.0)
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
		$this->_loadFinanciers($oid);
		$this->_loadCooperations($oid);
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

		$db = $this->getDBO();
 		$j = $this->_tbl_key;		
			
		// Delete the information about internal and external references
		$deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_internal_author').' WHERE '.$db->nameQuote('id_project').' = '.$db->Quote($this->$j);
		$deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_external_author').' WHERE '.$db->nameQuote('id_project').' = '.$db->Quote($this->$j);
		
		//Delete information of financiers
		$deleteFinQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_project_financier').' WHERE '.$db->nameQuote('id_project').' = '.$db->Quote($this->$j);
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
		
		$db->setQuery($deleteFinQuery);
		if(!$db->query())
		{
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		
		// Insert members' information
		$orderField = $db->nameQuote('order');
		$idPubField = $db->nameQuote('id_project');
       	$idStaffField = $db->nameQuote('id_staff_member');
       	$isPrincipalField = $db->nameQuote('is_principal');
       	
       	//Internal authors
		foreach($this->_internalAuthors as $author){
			$id_staff_member = $author['id_staff_member'];
			$order = $author['order'];
			$principal = $db->Quote($author['is_principal'], false);
			$tableName = $db->nameQuote('#__jresearch_project_internal_author');
			$insertInternalQuery = "INSERT INTO $tableName($idPubField,$idStaffField,$orderField,$isPrincipalField) VALUES ($this->id, $id_staff_member,$order,$principal)";
			$db->setQuery($insertInternalQuery);			
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getQuery());
				return false;
			}
		}
		
		//External authors
		$authorField = $db->nameQuote('author_name');
		foreach($this->_externalAuthors as $author){
			$order = $db->Quote($author['order'], false);
			$authorName = $db->Quote($db->getEscaped($author['author_name'], true), false);
			$principal = $db->Quote($author['is_principal'], false);
			
			$tableName = $db->nameQuote('#__jresearch_project_external_author');
			$insertExternalQuery = "INSERT INTO $tableName($idPubField, $authorField, $orderField, $isPrincipalField) VALUES($this->id, $authorName, $order, $principal)";			
			$db->setQuery($insertExternalQuery);
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getQuery());
				return false;
			}
		}  

		//Financiers
		$idFinField = $db->nameQuote('id_financier');
		$tableName = $db->nameQuote('#__jresearch_project_financier');
		foreach($this->_financiers as $fin)
		{
			$idFin = (int) $fin['id_financier'];
			$insertFinQuery = 'INSERT INTO '.$tableName.' ('.$idPubField.', '.$idFinField.') VALUES('.$this->id.', '.$idFin.')';
			
			$db->setQuery($insertFinQuery);
			
			if(!$db->query())
			{
				$this->setError(get_class( $this ).'::store failed - '.$db->getQuery());
				return false;
			}
		}
		
		//Set cooperations
		$idCoopField = $db->nameQuote('id_cooperation');
		$tableName = $db->nameQuote('#__jresearch_project_cooperation');
		foreach($this->_cooperations as $coop)
		{
			$idCoop = intval($coop['id_cooperation']);
			$insertQuery = 'INSERT INTO '.$tableName.' ('.$idPubField.', '.$idCoopField.' ) VALUES ('.$this->id.', '.$idCoop.')';
			
			$db->setQuery($insertQuery);
			
			if(!$db->query())
			{
				$this->setError(get_class( $this ).'::store failed - '.$db->getQuery());
				return false;
			}
		}
		
      	return true;
			
	}

	/**
	 * Sets a funder for the project
	 *
	 * @param int $financier
	 * @return bool
	 */
	public function setFinancier($financier)
	{
		if($financier > 0)
			array_push($this->_financiers, array('id' => $this->id, 'id_financier' => $financier));
		
		return true;
	}
	
	public function setCooperation($cooperation)
	{
		if($cooperation > 0)
			array_push($this->_cooperations, array('id' => $this->id, 'id_cooperation' => $cooperation));
	}
	
	/**
	 * Gets all funders, an array of financier objects
	 *
	 * @return array
	 */
	public function getFinanciers()
	{
		$db = &$this->getDBO();
		$finObjects = array(); 
		
		foreach($this->_financiers as $financier)
		{
			$finObject = new JResearchFinancier($db);
			$finObject->load($financier['id_financier']);
			array_push($finObjects,$finObject);
		}
		
		return $finObjects;
	}
	
	public function getCooperations()
	{
		$db =& $this->getDBO();
		$cObjects = array();
		
		foreach($this->_cooperations as $cooperation)
		{
			$cObject = JTable::getInstance('Cooperation', 'JResearch');
			$cObject->load($cooperation['id_cooperation']);
			array_push($cObjects, $cObject);
		}
		
		return $cObjects;
	}
	
	/**
	 * Counts the financiers for this project
	 *
	 * @return int
	 */
	public function countFinanciers()
	{
		return count($this->_financiers);
	}
	
	public function countCooperations()
	{
		return count($this->_cooperations);
	}

	protected function _load($oid, $table)
	{
		$db = &$this->getDBO();
		
		$table = $db->nameQuote($table);
		$idProject = $db->nameQuote('id_project');
		
		$qoid = $db->Quote($oid);
		
		// Get internal authors
        $query = "SELECT * FROM $table WHERE $idProject = $qoid";
		$db->setQuery($query);
        
		return $db->loadAssocList();
	}
	
	protected function _loadFinanciers($oid)
	{
		$result = $this->_load($oid, '#__jresearch_project_financier');
        $this->_financiers = (!empty($result)) ? $result : array();
	}
	
	protected function _loadCooperations($oid)
	{
		$result = $this->_load($oid, '#__jresearch_project_cooperation');
		$this->_cooperations = (!empty($result)) ? $result : array();
	}
	
	/**
	* Sets an author. 
	* 
	* @param mixed $member. It has two interpretations depending on the $internal parameter. If $internal
	* is true, $member must be a member database integer id, otherwise it will be a name.
	* @param int $order. The order of the author in the publication. Order is important in publications
	* as it shows the relevance of author's participation. Small numbers indicate more relevance. It must be
	* a non negative number.
	* @param boolean $internal If true, the author is part of staff and $member is the id, otherwise
	* the author is not part of the center. 
	* @param principal $isPrincipal If true, the author is considered as one of the leaders of the project.
	* 
	* @return true If the author could be correctly added (order is >= 0 and there is not any other author associated
	* to the order number), false otherwise.
	*/	
	function setAuthor($member, $order, $internal=false, $isPrincipal=false){
		$result = parent::setAuthor($member, $order, $internal);
		
		if($result){		
			//Get the last entry in the array and set the principal flag
			if($internal){
				$n = count($this->_internalAuthors);
				$this->_internalAuthors[$n - 1]['is_principal'] = $isPrincipal;
			}else{
				$n = count($this->_externalAuthors);
				$this->_externalAuthors[$n - 1]['is_principal'] = $isPrincipal;			
			}
		}
		
		return $result;
		
	}
	
	/**
	 * Returns an array of booleans indicating the principal flag of every member of the
	 * project in the order they were defined when the project was created.
	 * 
	 * @return array
	 *
	 */
	function getPrincipalsFlagsArray(){
		$nAuthors = $this->countAuthors();
		$result = array();
		$i = 0;
		
		while($i < $nAuthors){
			$result[] = $this->getPrincipalFlag($i); 
			$i++;
		}
		
		return $result;		
	}
	
	/**
	 * Returns the boolean flag indicating an author is one of the principal investigators
	 * in a project.
	 *
	 * @param int $authorIndex The index of the author which is defined when the user assigns
	 * the members to the project.
	 */
	function getPrincipalFlag($index){
		$n = $this->countAuthors();
		if($index < 0 || $index >= $n){
			return null;		
		}else{
			// Search in internal authors
			foreach($this->_internalAuthors as $inauth){
				if($inauth['order'] == $index)
					return (boolean)$inauth['is_principal'];
			}
			
			// Then search in external ones
			foreach($this->_externalAuthors as $extauth){
				if($extauth['order'] == $index)
					return (boolean)$extauth['is_principal'];
			}
			
			return null;
		}		
	}
	
	/**
	 * Get the members that were defined as principal investigators in the 
	 * project.
	 * 
	 * @return array
	 */
	function getPrincipalInvestigators(){
		$result = array();
		
		foreach($this->_externalAuthors as $extAuth){
			if($extAuth['is_principal'])
				$result[] = $this->getAuthor($extAuth['order']);
		}
		
		foreach($this->_internalAuthors as $intAuth){
			if($intAuth['is_principal'])
				$result[] = $this->getAuthor($intAuth['order']);
		}
		
		return $result;
	}
	
	/**
	 * Get the members that were not defined as principal investigators in the 
	 * project.
	 * 
	 * @return array
	 */
	function getNonPrincipalInvestigators(){
		$result = array();
		
		foreach($this->_externalAuthors as $extAuth){
			if(!$extAuth['is_principal'])
				$result[] = $this->getAuthor($extAuth['order']);
		}
		
		foreach($this->_internalAuthors as $intAuth){
			if(!$intAuth['is_principal'])
				$result[] = $this->getAuthor($intAuth['order']);
		}
		
		return $result;		
	}

        public function bind($from, $ignore = array(), $loadAuthors = false){
            parent::bind($from, $ignore, $loadAuthors);
            $this->_loadFinanciers($this->id);
            $this->_loadCooperations($this->id);
	}

	
}

?>