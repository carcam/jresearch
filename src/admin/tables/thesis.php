<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Theses
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
require_once(JRESEARCH_COMPONENT_ADMIN.'/'.'tables'.'/'.'activity.php');

/**
 * This class represent a degree thesis in JResearch environment.
 *
 */
class JResearchThesis extends JResearchActivity{
	
	/**
	 * The type of thesis: bachellor, master, phd
	 *
	 * @var string
	 */
	public $degree;
	
	/**
	 * Development status. It can be: not_started, in_progress, finished
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
	* Proposed deadline
	*
	* @var datetime
	*/	
	public $end_date;
	
	
	/**
	 * Thesis brief description.
	 *
	 * @var string
	 */
	public $description;
	

	/**
	 * Class constructor. Maps the class to a Joomla table.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct( '#__jresearch_thesis', 'id', $db );
		$this->_type = 'thesis';
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
	 * Validates the information of the thesis.
	 *
	 */
	public function check(){
		$date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
		
		// Verify the integrity of members
		if(!parent::checkAuthors())
			return false;

		// Validate dates	
		if(!empty($this->start_date)){
			if(!preg_match($date_pattern, $this->start_date)){
				$this->setError(JText::_('Please provide a proposed start date for the thesis in format YYYY-MM-DD'));
				return false;
			}
		}

		if(!empty($this->end_date)){
			if(!preg_match($date_pattern, $this->start_date)){
				$this->setError(JText::_('Please provide a proposed deadline for the thesis in format YYYY-MM-DD'));
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
			
			
		if(empty($this->title)){
			$this->setError(JText::_('Provide a title for the project'));
			return false;
		}	
		
		return true;		
	}
	
	/**
	 * Return an array of the directors of thesis.
	 *
	 * @return array Array of mixed elements: JResearchMember instances are considered as
	 * internal members while string instances are external members.
	 */
	public function getDirectors(){
		$directors = array();
		// Internal authors
		foreach($this->_internalAuthors as $auth){
			if($auth['is_director']){
				$directors[] = $this->getAuthor($auth['order']);
			}
		}
		
		// External ones
		foreach($this->_externalAuthors as $auth){
			if($auth['is_director']){
				$directors[] = $this->getAuthor($auth['order']);
			}
		}
		
		return $directors;
	}
	
	/**
	 * Return an array of the thesis' students.
	 *
	 * @return array Array of mixed elements: JResearchMember instances are considered as
	 * internal members while string instances are external members.
	 */
	public function getStudents(){
		$students = array();
		// Internal authors
		foreach($this->_internalAuthors as $auth){
			if(!$auth['is_director']){
				$students[] = $this->getAuthor($auth['order']);
			}
		}
		
		// External ones
		foreach($this->_externalAuthors as $auth){
			if(!$auth['is_director']){
				$students[] = $this->getAuthor($auth['order']);
			}
		}
		
		return $students;
		
	}
	
	/**
	* Sets an author. 
	* 
	* @param mixed $value. It has two interpretations depending on the $internal parameter. If $internal
	* is true, $value must be a member database integer id, otherwise it will be considered as a name.
	* @param int $order. The order of the author in the thesis. Order is important in theses
	* as it shows the relevance of author's participation. Small numbers indicate more relevance. It must be
	* a non negative number.
	* @param boolean $internal If true, the author is part of staff and $member is the id, otherwise
	* the author is not part of the center. 
	* @param boolean $isDirector If true, the author is director of the thesis, otherwise is one of the students.
	* @return true If the author could be correctly added (order is >= 0 and there is not any other author associated
	* to the order number), false otherwise.
	*/	
	
	public function setAuthor($value, $order, $internal=false, $isDirector=false){
		$newEntry = array();
		
		if($order < 0)
			return false;
			
		// Another author is using the same order number						
		if($this->getAuthor($order) != null)
			return false;

		$newEntry['id'] = $this->id;					
		$newEntry['order'] = $order;
		$newEntry['is_director'] = $isDirector;
		
		if($internal){
			$newEntry['id_staff_member'] = $value;
			$this->_internalAuthors[] = $newEntry;
		}else{
			$newEntry['author_name'] = $value;  
			$this->_externalAuthors[] = $newEntry;
		}

		return true;		
	}
	
	/**
	 * Inserts a new row if id is zero or updates an existing row in the 
	 * database table.
	 *
	 * @param boolean $updateNulls If false, null object variables are not updated
	 * @return true if successful
	 */
	function store($updateNulls=false){
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
		$deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_thesis_internal_author').' WHERE '.$db->nameQuote('id_thesis').' = '.$db->Quote($this->$j);
		$deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_thesis_external_author').' WHERE '.$db->nameQuote('id_thesis').' = '.$db->Quote($this->$j);
		
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
		$idPubField = $db->nameQuote('id_thesis');
       	$idStaffField = $db->nameQuote('id_staff_member');
       	$isDirectorField = $db->nameQuote('is_director');
       	
		foreach($this->_internalAuthors as $author){			
			$id_staff_member = $author['id_staff_member'];
			$order = $author['order'];
			$is_director = $author['is_director']?1:0;
			$tableName = $db->nameQuote('#__jresearch_thesis_internal_author');
			$insertInternalQuery = "INSERT INTO $tableName($idPubField,$idStaffField,$orderField,$isDirectorField) VALUES ($this->id, $id_staff_member,$order,$is_director)";
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
			$is_director = $author['is_director']?1:0;
			
			$tableName = $db->nameQuote('#__jresearch_thesis_external_author');
			$insertExternalQuery = "INSERT INTO $tableName($idPubField, $authorField, $orderField, $isDirectorField) VALUES($this->id, $authorName, $order, $is_director)";			
			$db->setQuery($insertExternalQuery);
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getQuery());
				return false;
			}
		}     		
     
      	return true;		
	}

}


?>