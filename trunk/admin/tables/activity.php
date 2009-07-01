<?php
/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * This class defines the base for all sources of activity in a center 
 * like publications, projects and theses. All of them have association with 
 * groups of members.
 *
 */
class JResearchActivity extends JTable{
	/**
	 * Integer database id
	 *
	 * @var int
	 */
	public $id;
	
	/**
	 * String for alias
	 *
	 * @var string
	 */
	public $alias;
	
	/**
	* @var string
	*/
	public $title;
	
	/**
	* @var boolean
	*/
	public $published;
	
	/**
	 * Date of creation
	 *
	 * @var datetime
	 */
	public $created;		
	
	/**
	 * URL associated to the activity
	 *
	 * @var string
	 */
	public $url;
	
	/**
	 * List of relative paths (in relation to site base path)
	 * associated to the activity, separated by semicolons.
	 *
	 * @var string
	 */
	public $files;
	
	/**
	 * Research area's publication database id
	 *
	 * @var int
	 */
	public $id_research_area;	
	
	
	/**
	 * User id of the person who blocked the item. 0 if the item is not blocked.
	 *
	 * @var int
	 */
	public $checked_out;	

	/**
	 * @var unknown_type
	 */
	public $checked_out_time;
	
	/**
	 * Holds the id of the user who created the publication.
	 *
	 * @var string
	 */
	public $created_by;
	
	/**
	 * Number of hits for the activity
	 *
	 * @var int
	 */
	public $hits;
	
	/**
	 * Array of internal authors ids
	 *
	 * @var array
	 */
	protected $_internalAuthors;

	/**
	 * Array of internal members (JResearchMember)
	 *
	 * @var array
	 */
	protected $_internalAuthorsObjects = null;
	
	/**
	 * Array of external authors names
	 * @var array
	 */
	protected $_externalAuthors;
	
	/**
	 * Name used by subtypes.
	 *
	 * @var string
	 */
	protected $_type;
	
	
	/**
	 * Class constructor
	 */
	public function __construct( $table, $key, &$db ){
	 	parent::__construct($table, $key, $db);
	 	$this->_internalAuthors = array();
		$this->_externalAuthors = array();
		$this->_internalAuthorsObjects = null;
	 	
	}
	 
	/**
	 * Loads the information about internal and external authors.
	 *
	 */
	protected function _loadAuthors($oid){
		$db = &$this->getDBO();
		
		$internalTable = $db->nameQuote('#__jresearch_'.$this->_type.'_internal_author');
		$idActivity = $db->nameQuote('id_'.$this->_type);
		$qoid = $db->Quote($oid);
		$externalTable = $db->nameQuote('#__jresearch_'.$this->_type.'_external_author');
		
		// Get internal authors
        $internalAuthorsQuery = "SELECT * FROM $internalTable WHERE $idActivity = $qoid ORDER by ".$db->nameQuote('order');
		$db->setQuery($internalAuthorsQuery);
        if(($result = $db->loadAssocList())){
        	$this->_internalAuthors = $result;
        }else{
        	$this->_internalAuthors = array();	
        }

        // Get external authors
        $externalAuthorsQuery = "SELECT * FROM $externalTable WHERE $idActivity = $qoid ORDER by ".$db->nameQuote('order');
	    $db->setQuery($externalAuthorsQuery);
        if(($result = $db->loadAssocList())){
        	$this->_externalAuthors = $result;
        }else{
        	$this->_externalAuthors = array();	
        }        		
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
	* 
	* @return true If the author could be correctly added (order is >= 0 and there is not any other author associated
	* to the order number), false otherwise.
	*/	
	function setAuthor($member, $order, $internal=false){
		$newEntry = array();
		
		if($order < 0)
			return false;
			
		// Another author is using the same order number						
		if($this->getAuthor($order) != null)
			return false;

		$newEntry['id'] = $this->id;					
		$newEntry['order'] = $order;
		
		if($internal){
			$newEntry['id_staff_member'] = $member;
			$this->_internalAuthors[] = $newEntry;
		}else{
			$newEntry['author_name'] = $member;  
			$this->_externalAuthors[] = $newEntry;
		}
		
		return true;
	}
	
	/**
	 * Returns the complete list of authors (internal and externals) suitably ordered. 
	 * Internal authors are displayed in format [lastname, firstname].
	 * External authors are taken as they appear in the database.
	 *
	 * @return array Array of mixed elements. Internal authors are instances of JResearchMember.
	 * External ones are strings.
	 */
	public function getAuthors(){
		$nAuthors = $this->countAuthors();
		$result = array();
		$i = 0;
		
		while($i < $nAuthors){
			$auth = $this->getAuthor($i);
			if($auth !== null){
				$result[] = $auth;
			}
			$i++;
		}
		
		return $result;
	}
	
	/**
	 * Returns the author with the index specified. In publications records, the order
	 * in which authors are displayed is important.
	 * 
	 * @param int $index Must be equal or greater than 0 and less than the number of authors.
	 * @return mixed string with the name of the author when external.
	 * JResearchMember instance when the author is internal.
	 * null when the index does not make sense (e.g the publication has 3 authors and $index=4 or $index<0)
	 */
	public function getAuthor($index){
		$n = $this->countAuthors();
		if($index < 0 || $index >= $n){
			return null;		
		}else{
			$internalAuthors = $this->getInternalAuthors();
			if(isset($internalAuthors[$index]))
				return $internalAuthors[$index];
			else{
				$externalAuthors = $this->getExternalAuthors();
				if(isset($externalAuthors[$index]))
					return $externalAuthors[$index];

				return null;
			}
		}
	}
	
	/**
	 * Returns a sorted array with the information of the publication's internal authors.
	 * Internal authors are part of the center's staff so they are represented by
	 * objects of class JResearchMember
	 * @return array Associative array of JResearchMember objects as values and order parameter
	 * as key.
	 */
	public function getInternalAuthors(){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');		
		$db = &$this->getDBO();
		if($this->_internalAuthorsObjects == null){
			foreach($this->_internalAuthors as $member){
				$memberObject = new JResearchMember($db);
				$memberObject->load($member['id_staff_member']);
				$this->_internalAuthorsObjects[$member['order']] = $memberObject;
			}
		}		
		return $this->_internalAuthorsObjects;
	}
	
	/**
	 * Returns a sorted array with the information of the publication's external authors.
	 * External authors are not part of the center's staff so they are represented as strings
	 * @return array Associative array, where the order is the key and the author's name, the value.
	 */
	public function getExternalAuthors(){
		$result = array();
		foreach($this->_externalAuthors as $author){
			$result[$author['order']] = $author['author_name'];
		}
		return $result;
	}
	
	/**
	 * Resets the default properties.
	 *
	 */
	public function reset(){
		parent::reset();
		$this->_externalAuthors = array();
		$this->_internalAuthors = array();
		$this->_internalAuthorsObjects = null;
	}
	
	/**
	 * Returns the number of authors of the publication.
	 *
	 * @return int
	 */
	public function countAuthors(){
		return count($this->_internalAuthors) + count($this->_externalAuthors);
	}
	
	/**
	 * Verifies the integrity of the authors names and ids.
	 * 
	 * @return boolean False if a person appears as author more than once or 
	 * invalid author names are provided.
	 */
	public function checkAuthors(){
		// Verify there are not repeated authors
		// First, internal ones
		$n = count($this->_internalAuthors);
		for($i=0; $i<$n; $i++){
			for($j=$i+1; $j<$n; $j++){
				if($this->_internalAuthors[$i]['id_staff_member'] == $this->_internalAuthors[$j]['id_staff_member']){
					$this->setError(JText::sprintf('The member with id %d appears more than once as author', $this->_internalAuthors[$i]['id_staff_member']));
					return false;
				}
			}			
		}
		// External ones
		$n = count($this->_externalAuthors);
		for($i=0; $i<$n; $i++){
			// Verify content
			$name_pattern = '/\w[-_\w\s.]+/';
			if(!preg_match($name_pattern, $this->_externalAuthors[$i]['author_name'])){
				$this->setError(JText::_('Authors names can only contain alphabetic characters plus ._- characters with neither leading nor trailing whitespaces'));
				return false;
			}
			
			for($j=$i+1; $j<$n; $j++){
				if($this->_externalAuthors[$i]['author_name'] == $this->_externalAuthors[$j]['author_name']){
					$this->setError(JText::sprintf('%s appears more than once as author', $this->_externalAuthors[$i]['author_name']));
					return false;
				}
			}			
		}
		
		return true;
	} 
	
	/**
	 * Returns the complete URL of the attachment with index $i
	 *
	 * @param int $i
	 * @param string $controller 
	 */
	public function getAttachment($i, $controller){
		if(!empty($this->files)){
			$filesArr = explode(';', trim($this->files));
			if(!empty($filesArr[$i])){
				$params = JComponentHelper::getParams('com_jresearch'); 
				return  JURI::base().'administrator/components/com_jresearch/'.str_replace(DS, '/', $params->get('files_root_path', 'files'))."/$controller/".$filesArr[$i];
			}else
				return null;
		}else
			return null;
		
	}
	
	/**
	 * Returns the number of activity's attached files.
	 *
	 * @return int
	 */
	public function countAttachments(){
		if(empty($this->files))
			return 0;
		else{
			return count(explode(';', trim($this->files)));
		}	
	}
	
	/**
	* Removes related information related to an activity (not the activity per se as it is done
	* in the child classes) from database. 
	* 
	* @param $oid Publication id
	* @return true if success.
	*/	
	function delete($oid){
		$db = JFactory::getDBO();
		
		$internalTable = $db->nameQuote('#__jresearch_'.$this->_type.'_internal_author');
		$externalTable = $db->nameQuote('#__jresearch_'.$this->_type.'_external_author');
		$db->setQuery('DELETE FROM '.$internalTable.' WHERE '.$db->nameQuote('id_'.$this->_type).' = '.$db->Quote($oid));		
		$db->query();
		$db->setQuery('DELETE FROM '.$externalTable.' WHERE '.$db->nameQuote('id_'.$this->_type).' = '.$db->Quote($oid));		
		$db->query();
		
		return parent::delete($oid);
		
	}
}

?>
