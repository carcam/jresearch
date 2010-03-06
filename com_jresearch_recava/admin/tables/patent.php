<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'publication.php');

/**
 * The class JResearchPatent is subclass of JResearchPublication and holds
 * the information of a patent.
 */
class JResearchPatent extends JResearchPublication
{	
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	public $patent_number;
	public $issue_date;
	public $titular_entity;
	public $extended_countries;
	public $in_explotation;
	public $country;
	protected $_internalInventors;
	protected $_internalInventorsObjects = null;
	protected $_externalInventors;
	

	/**
	 * Class constructor.  It maps the entity to Joomla tables
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable('#__jresearch_patent');
		$this->pubtype = 'patent';
	}
	
		/**
	 * Loads the information about internal and external authors.
	 *
	 */
	protected function _loadInventors($oid){
		$db = $this->getDBO();
		
		$internalTable = $db->nameQuote('#__jresearch_patent_internal_inventor');
		$idActivity = $db->nameQuote('id_patent');
		$qoid = $db->Quote($oid);
		$externalTable = $db->nameQuote('#__jresearch_patent_external_inventor');
		
		// Get internal authors
        $internalAuthorsQuery = "SELECT * FROM $internalTable WHERE $idActivity = $qoid ORDER by ".$db->nameQuote('order');
		$db->setQuery($internalAuthorsQuery);
        if(($result = $db->loadAssocList())){
        	$this->_internalInventors = $result;
        }else{
        	$this->_internalInventors = array();	
        }

        // Get external authors
        $externalAuthorsQuery = "SELECT * FROM $externalTable WHERE $idActivity = $qoid ORDER by ".$db->nameQuote('order');
	    $db->setQuery($externalAuthorsQuery);
        if(($result = $db->loadAssocList())){
        	$this->_externalInventors = $result;
        }else{
        	$this->_externalInventors = array();	
        }
	}
	

	function setInventor($member, $order, $internal=false){
		$newEntry = array();
		
		if($order < 0)
			return false;
			
		// Another author is using the same order number						
		if($this->getInventor($order) != null)
			return false;

		$newEntry['id'] = $this->id;					
		$newEntry['order'] = $order;
		
		if($internal){
			$newEntry['id_staff_member'] = $member;
			$this->_internalInventors[] = $newEntry;
		}else{
			$newEntry['author_name'] = $member;  
			$this->_externalInventors[] = $newEntry;
		}
		
		return true;
	}
	
	
	/**
	 * 
	 * @return unknown_type
	 */
	function getInventors(){
		$nInventors = $this->countInventors();
		$result = array();
		$i = 0;
		
		while($i < $nInventors){
			$inventor = $this->getInventor($i);
			if($inventor !== null){
				$result[] = $inventor;
			}
			$i++;
		}
		
		return $result;
		
	}
	
	public function getInventor($index){
		$n = $this->countInventors();
		if($index < 0 || $index >= $n){
			return null;		
		}else{
			$internalInventors = $this->getInternalInventors();
			if(isset($internalInventors[$index]))
				return $internalInventors[$index];
			else{
				$externalInventors = $this->getExternalInventors();
				if(isset($externalInventors[$index]))
					return $externalInventors[$index];

				return null;
			}
		}
	}
	
	public function getInternalInventors(){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');		
		if($this->_internalInventorsObjects == null){
			foreach($this->_internalInventors as $member){
				$memberObject = JTable::getInstance('Member', 'JResearch');
				$memberObject->load($member['id_staff_member']);
				$this->_internalInventorsObjects[$member['order']] = $memberObject;
			}
		}		
		return $this->_internalInventorsObjects;
	}
	

	public function getExternalInventors(){
		$result = array();
		foreach($this->_externalInventors as $author){
			$result[$author['order']] = $author['author_name'];
		}
		return $result;
	}
	
	/**
	 * Returns the number of authors of the publication.
	 *
	 * @return int
	 */
	public function countInventors(){
		return count($this->_internalInventors) + count($this->_externalInventors);
	}
	
	
	/**
	 * Inserts a new row if id is zero or updates an existing row in the 
	 * database table.
	 *
	 * @param boolean $updateNulls If false, null object variables are not updated
	 * @return true if successful
	 */
	public function store($updateNulls = false){
		if(!parent::store($updateNulls))
			return false;
		
		$db = JFactory::getDBO();	

		// Delete the information about internal and external references
		$deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_patent_internal_inventor').' WHERE '.$db->nameQuote('id_patent').' = '.$db->Quote($this->id);
		$deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_patent_external_inventor').' WHERE '.$db->nameQuote('id_patent').' = '.$db->Quote($this->id);
		
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
		
		$orderField = $db->nameQuote('order');
		$idPubField = $db->nameQuote('id_patent');		
		
		foreach($this->_internalInventors as $author){			
			$id_staff_member = $author['id_staff_member'];
			$idStaffField = $db->nameQuote('id_staff_member');
			$order = $author['order'];
			$tableName = $db->nameQuote('#__jresearch_patent_internal_inventor');
			$insertInternalQuery = "INSERT INTO $tableName($idPubField,$idStaffField,$orderField) VALUES ($this->id, $id_staff_member,$order)";
			$db->setQuery($insertInternalQuery);			
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}
		}

		foreach($this->_externalInventors as $author){
			$order = $db->Quote($author['order'], false);
			$authorName = $db->Quote($db->getEscaped($author['author_name'], true), false);
			
			$authorField = $db->nameQuote('author_name');
			$tableName = $db->nameQuote('#__jresearch_patent_external_inventor');
			$insertExternalQuery = "INSERT INTO $tableName($idPubField, $authorField, $orderField) VALUES($this->id, $authorName, $order)";			
			$db->setQuery($insertExternalQuery);
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}
		}     		
     
      	return true;
	}
	
	/**
	* Removes a publication from the database. 
	* 
	* @param $oid Publication id
	* @return true if success.
	*/	
	public function delete($oid){
		$db = JFactory::getDBO();
		// Get publication type
		$db->setQuery('SELECT '.$db->nameQuote('pubtype').' FROM '.$db->nameQuote('#__jresearch_publication').' WHERE '.$db->nameQuote('id').' = '.$db->Quote($oid));			
		$pubtype = $db->loadResult();
		$result = parent::delete($oid);
		
		if($result){
			$tableName = $db->nameQuote('#__jresearch_'.$pubtype);
			$db->setQuery('DELETE FROM '.$tableName.' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($oid));		
			$db->query();
		}
		
		//Remove inventors
		$deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_patent_internal_inventor').' WHERE '.$db->nameQuote('id_patent').' = '.$db->Quote($oid);
		$deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_patent_external_inventor').' WHERE '.$db->nameQuote('id_patent').' = '.$db->Quote($oid);
		
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
				
		return $result;
	}
	
	/**
	 * Loads a row from the database and binds the fields to the object properties.
	 *
	 * @param int $oid
	 * @return True if successful
	 */
	public function load($oid = null){		
		parent::load($oid);
		$this->_loadInventors($oid);			
	}
	
	
	
}

?>