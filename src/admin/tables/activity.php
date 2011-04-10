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
	 * Research areas ids
	 *
	 * @var string
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
	 * 
	 * String storing either author names or staff ids separated by commas
	 * @var string
	 */
	public $authors;
	
	/**
	 * Cache for list of authors 
	 *
	 * @var array
	 */
	protected $_authorsArray;
	
	/**
	 * Name used by subtypes.
	 *
	 * @var string
	 */
	protected $_type;
	

	/**
	 * Cache for the list of research areas associated to the
	 * object
	 */
	protected $_areas;
	

	public function __construct($table, $key, $db ){
	 	parent::__construct($table, $key, $db);
	}
	
		 
	/**
	 * Loads the information about internal and external authors.
	 *
	 */
	protected function _loadAuthors(){
        $db = $this->getDBO();

		$query = 'SELECT '.$db->nameQuote('id').' FROM 
		(SELECT '.$db->nameQuote('id_staff_member').' as id, '.$db->nameQuote('order').' FROM #__jresearch_publication_internal_author 
		WHERE '.$db->nameQuote("id_".$this->_type).' = '.$db->Quote($this->id).' UNION 
		(SELECT '.$db->nameQuote('author_name').' as id, '.$db->nameQuote('order').' FROM #__jresearch_publication_external_author
		WHERE '.$db->nameQuote("id_".$this->_type).' = '.$db->Quote($this->id).')) R1 order by R1.'.$db->nameQuote('order');
		
		$db->setQuery($query);
		$result = $db->loadResultArray();
		
		$this->authors = implode(',', $result);
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
		if(empty($this->_authorsArray)){
			$this->_authorsArray = array();
			
			if(!empty($this->authors))
				$tmpAuthorsArray = explode(',', $this->authors);
			else
				$tmpAuthorsArray = array();	
			
			foreach($tmpAuthorsArray as $author){
				if(is_numeric($author)){
					$member = JTable::getInstance('Member', 'JResearch');
					$member->load((int)$author);
					$this->_authorsArray[] = $member;
				}else{
					$this->_authorsArray[] = $author;
				}
			}
		}
		
		return $this->_authorsArray;
	}
	
	/**
	 * 
	 * Adds an author to the activity
	 * @param mixed If $author is a integer or numeric string it is consider as the id
	 * of a J!Research member, otherwise it is considered as an external author.
	 * If it is a JResearchMember, only its id is taken.
	 */
	public function addAuthor($author){		
		$textToAppend = '';
		$this->_authorsArray = null;
		
		if($author instanceof JResearchMember){
			$textToAppend = (string)$author->id;
		}elseif(is_numeric($author)){
			$textToAppend = (string)$author;
		}elseif(is_string($author)){
			$textToAppend = $author;
		}else{
			return false;
		}

		if(!empty($this->authors))
			$this->authors .= ','.$textToAppend;
		else
			$this->authors = $textToAppend;	
		
		return true;	
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
		$this->getAuthors();
		return $this->_authorsArray[$index];
	}
	
	/**
	 * Returns a sorted array with the information of the publication's internal authors.
	 * Internal authors are part of the center's staff so they are represented by
	 * objects of class JResearchMember
	 * @return array Associative array of JResearchMember objects as values and order parameter
	 * as key.
	 */
	public function getInternalAuthors(){
		$this->getAuthors();
		
		$internalsArray = array();
		$index = 0;
		
		foreach($this->_authorsArray as $author){
			if($author instanceof JResearchMember){
				$internalsArray[$index] = $author; 
			}			
			$index++;
		}
		
		return $internalsArray;		
	}
	
	/**
	 * Returns a sorted array with the information of the publication's external authors.
	 * External authors are not part of the center's staff so they are represented as strings
	 * @return array Associative array, where the order is the key and the author's name, the value.
	 */
	public function getExternalAuthors(){
		$this->getAuthors();
		
		$externalsArray = array();
		$index = 0;
		
		foreach($this->_authorsArray as $author){
			if(is_string($author)){
				$externalsArray[$index] = $author; 
			}			
			$index++;
		}
		
		return $externalsArray;
	}
	
	/**
	 * Resets the default properties.
	 *
	 */
	public function reset(){
		parent::reset();
		$this->_authorsArray = null;
		$this->_areas = null;
	}
	
	/**
	 * Returns the number of authors of the publication.
	 *
	 * @return int
	 */
	public function countAuthors(){
		if(!empty($this->_authorsArray)){
			return count($this->_authorsArray);
		}else{
			return count(explode(',', $this->authors));
		}	
	}
	
	/**
	 * Verifies the integrity of the authors names and ids.
	 * 
	 * @return boolean False if a person appears as author more than once or 
	 * invalid author names are provided.
	 */
	public function checkAuthors(){
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
				return  JURI::root().'administrator/components/com_jresearch/'.str_replace(DS, '/', $params->get('files_root_path', 'files'))."/$controller/".$filesArr[$i];
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
		$k = $this->_tbl_key;
		$oid = (is_null($oid)) ? $this->$k : $oid;			
		$result = parent::delete($oid);
		
		if(!$result)
			return $result;
		
		$internalTable = $db->nameQuote('#__jresearch_'.$this->_type.'_internal_author');
		$externalTable = $db->nameQuote('#__jresearch_'.$this->_type.'_external_author');
		$areasTable = $db->nameQuote('#__jresearch_'.$this->_type.'_researcharea');

		$db->setQuery('DELETE FROM '.$internalTable.' WHERE '.$db->nameQuote('id_'.$this->_type).' = '.$db->Quote($oid));		
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		
		$db->setQuery('DELETE FROM '.$externalTable.' WHERE '.$db->nameQuote('id_'.$this->_type).' = '.$db->Quote($oid));		
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		
		$db->setQuery('DELETE FROM '.$areasTable.' WHERE '.$db->nameQuote('id_'.$this->_type).' = '.$db->Quote($oid));
		if(!$db->query()){
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}		
		
		return true;
		
	}
	
	/**
	 * 
	 * Returns an array with the research area objects associated to 
	 * the activity.
	 * @param string $whatInfo "all" to bring the entire list of objects,
	 * "names" to bring only the names.
	 * @return Array of JResearchResearcharea objects or stdobjects containing ids and names
	 */
	function getResearchAreas($whatInfo = 'all'){
		$db = JFactory::getDBO();
		
		if($whatInfo == 'all'){
			if(!isset($this->_areas)){
				$this->_areas = array();
				$db->setQuery('SELECT * FROM #__jresearch_research_area WHERE id IN ('.$this->id_research_area.')');				
				$areas = $db->loadAssocList();		
				foreach($areas as $row){
					$area = JTable::getInstance('Researcharea', 'JResearch');
					$area->bind($row);
					$this->_areas[] = $area;
				}
			}			
			
			return $this->_areas;			
		}elseif($whatInfo == 'names'){
			$db->setQuery('SELECT id, name, published FROM #__jresearch_research_area WHERE id IN ('.$this->id_research_area.')');				
			return $db->loadObjectList();
		}else{
			return null;
		}		
	}
}

?>
