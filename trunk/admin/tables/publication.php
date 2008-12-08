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


jimport('joomla.utilities.date');

__loadPublicationsSubclasses();

/**
 * Imports all the subclasses of JResearchPublication. Must be invoked
 * for any script that works with JResearchPublication entities.
 *
 */
function __loadPublicationsSubclasses(){
	jimport('joomla.filesystem.path');
	jimport('joomla.filesystem.folder');
	
	$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'tables';
	$files = JFolder::files($path, '.php');
	foreach($files as $f){
		require_once($path.DS.$f);
	}
	
}



/**
 * This class defines the base for all types of publications managed by JResearch.
 * Extensions for JResearch supported types imply write JResearchPublication derived
 * classes.
 *
 */
class JResearchPublication extends JResearchActivity{

	
	/**
	 * Extra comments written by the author.
	 *
	 * @var string
	 */
	public $comments;
	
	/**
	* @var boolean
	*/
	public $internal;
	
	
	/**
	 * The acceptance rate of the journal where the publication was accepted.
	 *
	 * @var float
	 */
	public $journal_acceptance_rate;	
	
	/**
	 * @var string
	 */
	public $awards;
	
	/**
	 * @var string
	 */
	public $url;
	
	
	/**
	 * Year of publication.
	 *
	 * @var int
	 */
	public $year;

	/**
	 * Label used for programs like Bibtex when trying to cite a publication.
	 * Used by JResearch automatic citation plugin.
	 *
	 * @var string
	 */
	public $citekey;
	
	/**
	 * @var string
	 */
	public $abstract;
	
	/**
	 * Publication type: Book, Phd Thesis, etc.
	 *
	 * @var string
	 */
	public $pubtype;

	/**
	 * Used for tagging of publications. It must have the following format:
	 * keyword1,keyword2,keyword3,
	 *
	 * @var string
	 */
	public $keywords;
	
	/**
	 * Additional information about the article, useful for readers.
	 * 
	 * @var string
	*/
	public $note;
	
	/**
	 * Name of the foreign key field in the subclass table.
	 * 
	 * @var string
	 */
	private $_d_tbl_key;
	
	/**
	 * Array with the name of the public variables added by the derived 
	 * class. Derived subclasses can access this variable via _getSubclassAttributes
	 * method.
	 * @return array
	 */
	private $_derivedVariablesArray = null;	
	
	/**
	* Name of the table used for the subclass.
	*/
	private $_derivedTable = null;

	/**
	* Associative array with supported records types and their printable names.
	*/
	static $_types;

	/**
	 * Class constructor. It maps the entity to a Joomla table.
	 *
	 * @param JDatabase $db
	 */
	public function __construct(&$db, $tbl_key = 'id_publication'){
		parent::__construct( '#__jresearch_publication', 'id', $db );		
		$this->_d_tbl_key = $tbl_key;
		$this->_type = 'publication';
		$this->year = 0;
		
	}
	
	/**
	* Parse the publication into an associative array considering just the public 
	* attributes.
	* @return array
	*/
	public function __toArray(){
		$properties = get_class_vars(get_class($this));
		$resultProperties = array();
		foreach($properties as $k=>$v){
			if($k{0} != '_')
				$resultProperties[$k] = $v;
		}
		
		return $resultProperties;
	}
	
	/**
	 * Returns an array with the names of the attributes that compound
	 * the class JResearchPublication.
	 * @return array
	 */
	protected function _getParentAttributes(){
		$properties = get_class_vars("JResearchPublication");
		$result = array();
		foreach($properties as $k=>$v){
			if($k{0} != '_')
				$result[] = $k;
		}
		return $result;
	}
	
	/**
	 * Returns an array with the names of the public attributes that form part
	 * of the class but do not belong to JResearchPublication.
	 *
	 * @return unknown
	 */
	protected function _getSubclassAttributes(){
		if($_derivedVariablesArray == null){
			$baseProperties = $this->_getParentAttributes();
			$className = get_class($this);
			$subclassProperties = get_class_vars($className);
	
			$_derivedVariablesArray = array();
			foreach($subclassProperties as $p=>$v ){
				if($p{0} != '_'){
					if(!in_array($p, $baseProperties))
						$_derivedVariablesArray[] = $p;
				}
			} 
		}
		
		return $_derivedVariablesArray;
	}

	
	/**
	* Sets the name of the derived table associated to the 
	* instance.
	*
	*/
	protected function setDerivedTable($tableName){
		$this->_derivedTable = $tableName;
	}
	
	/**
	 * Returns the publication with the citekey provided. The citekey is
	 * the label used for tools like Bibtex to identify a record in the database
	 * when citing.
	 * @param string $citekey
	 * @return JResearchPublication
	 */

	public static function &getByCitekey($citekey){
		$result = null;
		$db =& JFactory::getDBO();
		$citekeyName = $db->nameQuote('citekey');
		$citekeyQ = $db->Quote($citekey, false);
		$query = "SELECT ".$db->nameQuote('pubtype')." FROM ".$db->nameQuote('#__jresearch_publication')." WHERE $citekeyName = $citekeyQ";
		$db->setQuery($query);
		$pub = $db->loadResult();
		if($pub){
			$result = JResearchPublication::getSubclassInstance($pub);
			$result->loadByCitekey($citekey);	
			return $result;
		}else{
			return null;
		}

	}
	
	/**
	 * Returns the publication with the database integer id provided. 
	 *
	 * @param int $id
	 * @return JResearchPublication or null.
	 * 
	 */
	public static function &getById($id){
		$result = null;
		$db = &JFactory::getDBO();
		$idQ = $db->Quote($id, false);
		$query = "SELECT ".$db->nameQuote('pubtype')." FROM ".$db->nameQuote('#__jresearch_publication')." WHERE id = $idQ";
		$db->setQuery($query);
		$pub = $db->loadResult();
		if($pub){
			$result = JResearchPublication::getSubclassInstance($pub);
			$result->load($id);	
			return $result;
		}else{
			return null;
		}
	}
	
	/**
	 * Returns the name of the table associated to the subclass the
	 * instance belongs to.
	 *
	 * @return string
	 */
	protected function _getDerivedTable(){
		return $this->_derivedTable;
	}
	
	
	
	/**
	 * Loads a row from the database and binds the fields to the object properties.
	 * @param string $citekey Label or key that identifies the record and is used
	 * during citation.
	 * @return True if successful
	 */
	public function loadByCitekey($citekey){
		if($citekey === null || $citekey === '')
			return false;

		$derivedTable = $this->_getDerivedTable();

		$this->reset();
		$this->citekey = $citekey;
      $db =& $this->getDBO();
        
      $query = "SELECT * "
      . " FROM $this->_tbl , $derivedTable "
      . " WHERE $this->_tbl.$this->_tbl_key = $derivedTable.$this->_d_tbl_key"
      . " AND $this->_tbl.citekey = ".$db->Quote($citekey);
      $db->setQuery( $query );
      if (($result = $db->loadAssoc())) {
        	$rs = $this->bind($result);
        	$this->_loadAuthors($this->id);
         return $rs;
      }else{
         $this->setError( $db->getErrorMsg() );
         return false;
      }
	}


	/**
	* Removes a publication from the database. 
	* 
	* @param $oid Publication id
	* @return true if success.
	*/	
	public function delete($oid){
		$db =& JFactory::getDBO();
		// Get publication type
		$db->setQuery('SELECT '.$db->nameQuote('pubtype').' FROM '.$db->nameQuote('#__jresearch_publication').' WHERE '.$db->nameQuote('id').' = '.$db->Quote($oid));			
		$pubtype = $db->loadResult();
		$result = parent::delete($oid);
		
		if($result){
			$tableName = $db->nameQuote('#__jresearch_'.$pubtype);
			$db->setQuery('DELETE FROM '.$tableName.' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($oid));		
			$db->query();
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
		$k = $this->_tbl_key;
		if(!parent::load($oid))
			return false;
        
        if ($oid === null) {
           return false;
        }        
        
        $this->$k = $oid;
        
        $derivedTable = $this->_getDerivedTable();
        $db =& $this->getDBO();        
		$this->_loadAuthors($oid);


        $query = "SELECT * "
        . " FROM $this->_tbl , $derivedTable"
        . " WHERE $this->_tbl.$this->_tbl_key = $derivedTable.$this->_d_tbl_key"
        . " AND $this->_tbl.$this->_tbl_key = $db->Quote($oid)";        
        $db->setQuery( $query );
        
        if (($result = $db->loadAssoc( ))) {
            return $this->bind($result);
        }else{
            $this->setError( $db->getErrorMsg() );
            return false;
        }
			
	}
	
	
	/**
	* Verify if the publication is ready to be saved in the database. To get the
	* cause of a failed check, method getError must be invoked. 
	* @return boolean
	*/
	public function check(){
		$db =& JFactory::getDBO();		
		
		// Verify authors integrity
		if(!parent::checkAuthors())
			return false;
		
			
		if(empty($this->citekey)){
			$this->setError(JText::_('JRESEARCH_PROVIDE_CITEKEY'));
			return false;
		}	
		
		// Verify if title is not empty
		if(empty($this->title)){
			$this->setError(JText::_('JRESEARCH_REQUIRE_PUBLICATION_TITLE'));
			return false;
		}
		// Verify year
		if(!empty($this->year)){
			if(!preg_match('/^\d{4}$/',$this->year)){
				$this->setError(JText::_('JRESEARCH_PROVIDE_VALID_YEAR')); 
				return false;
			}
					
		}
		
		if(!empty($this->keywords)){
			require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'language.php');
			$extra = extra_word_characters();
			if(!preg_match("/^[-_'\w$extra\s\d]+(,[-_'\w$extra\s\d]+)*,*$/", $this->keywords)){
				$this->setError(JText::_('Error in the keywords field. They must be provided as several words separated by commas'));
				return false;
			}
		}
		
		if(!empty($this->journal_acceptance_rate)){
			if(!is_numeric($this->journal_acceptance_rate)){
				$this->setError(JText::_('Journal acceptance rate must be a number'));
				return false;
			}
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
	public function store($updateNulls = false){				
		$db = &$this->getDBO();
		// Time to insert the information of the publication per se			
 		$j = $this->_tbl_key;		

		$isNew = $this->$j?false:true;
		if($isNew){
			$now = new JDate();
			$this->created = $now->toMySQL();
		}

		$parentProperties = $this->_getParentAttributes();		

		$parentObject = (object)array();
		$parentObject->$j = $this->$j;
		foreach($parentProperties as $prop){
			 if($this->$prop !== null)
				$parentObject->$prop = $this->$prop;
		}
 		// Time to insert the attributes
      	if($this->$j){
          	$ret = $db->updateObject( $this->_tbl, $parentObject, $this->_tbl_key, $updateNulls );
      	}else{
          	$ret = $db->insertObject( $this->_tbl, $parentObject, $this->_tbl_key );
          	$this->$j = $db->insertid();
      	}

		// Delete the information about internal and external references
		$deleteInternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_publication_internal_author').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($this->$j);
		$deleteExternalQuery = 'DELETE FROM '.$db->nameQuote('#__jresearch_publication_external_author').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($this->$j);
		
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

		// We construct an object with the derived properties only
 		$derivedProperties = $this->_getSubclassAttributes();
 		// If it is a JResearchPublication object, just return 
 		if(!empty($derivedProperties)){
	 		$derivedObject = (object)array();
	 		$d = $this->_d_tbl_key;
	 		$derivedObject->$d = $parentObject->$j;
	 		foreach($derivedProperties as $prop){
	 			if($this->$prop !== null){				
			 		$derivedObject->$prop = $this->$prop;
			 	}else{
			 		$derivedObject->$prop = ' ';
			 	}
	 		}
	 		
	 		$derivedObject->$d = $this->$j; 		
	
	 		// Time to insert the derived attributes
	  		if( !$isNew){
	          $ret = $db->updateObject( $this->_derivedTable, $derivedObject, $this->_d_tbl_key, $updateNulls );
	      }else{
	          $ret = $db->insertObject( $this->_derivedTable, $derivedObject, $this->_d_tbl_key );
	      }

	      
	      if( !$ret ){
	          $this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
	          return false;
	      }				
      	}
		
		$orderField = $db->nameQuote('order');
		$idPubField = $db->nameQuote('id_publication');
       
		foreach($this->_internalAuthors as $author){			
			$id_staff_member = $author['id_staff_member'];
			$idStaffField = $db->nameQuote('id_staff_member');
			$order = $author['order'];
			$tableName = $db->nameQuote('#__jresearch_publication_internal_author');
			$insertInternalQuery = "INSERT INTO $tableName($idPubField,$idStaffField,$orderField) VALUES ($this->id, $id_staff_member,$order)";
			$db->setQuery($insertInternalQuery);			
			if(!$db->query()){
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}
		}

		foreach($this->_externalAuthors as $author){
			$order = $db->Quote($author['order'], false);
			$authorName = $db->Quote($db->getEscaped($author['author_name'], true), false);
			
			$authorField = $db->nameQuote('author_name');
			$tableName = $db->nameQuote('#__jresearch_publication_external_author');
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
	 * Returns a new instance of JResearchPublication.
	 * @param string $publicationType Name of the subclass of JResearchPublication that will be
	 * instantiated. The name of the class is obtained by concatenating this value with prefix JResearch. E.g: 
	 * If the param is 'masterthesis' (all in lowercase) then the classname will be JResearchMasterthesis which must
	 * be declared in a file named masterthesis.php (all letters in lowercase)
	 * @return JResearchPublication if the appropiate class is found, null otherwise.
	 */
	public static function &getSubclassInstance($publicationType){
		$prefix = 'JResearch';
		$filename = strtolower($publicationType);
		if($filename == 'inproceedings')
			$filename = 'conference';
		$classname = $prefix.ucfirst($filename);
		$db = &JFactory::getDBO();

		if(!class_exists($classname)){

			$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.$filename.'.php';

			if(!file_exists($path)){
				return null;
			}					
			require_once($path);
			
			if(class_exists($classname))
				return new $classname($db);
			else
				return null;
		}else
			return new $classname($db);
	}

	/**
	* Returns an array with the supported publications datatypes. 
	* 
	* @return array
	*/	
	public static function getPublicationsSubtypes(){
		$db = &JFactory::getDBO();
			
		$query = 'SELECT '.$db->nameQuote('name').' FROM '.$db->nameQuote('#__jresearch_publication_type');	
		$db->setQuery($query);
		
		return $db->loadResultArray();
	}
	
	/**
	 * Returns an array of book's editors.
	 *
	 */
	function getEditors(){
		if(isset($this->editor))
			return explode(' and ',$this->editor);
		else
			return array();	
	}
	
	
	/**
	 * Returns the string representation of the publication.
	 * @return string
	 */
	function __toString(){
		return $this->citekey+': '+$this->title;
	}

	/**
	 * Returns the completed publication referred by field crossref.
	 * 
	 * @return JResearchPublication null if the referenced publication could not be found
	 * or the publication does not have a value for crossref field.
	 */
	function getReferencedRecord(){
		$ref = null;
		$cf = trim($this->crossref);
		if(!empty($cf)){
			$ref = JResearchPublication::getByCitekey($this->crossref);
		}
		
		return $ref;
	}
	
	/**
	 * Returns an associative array with the fields that are defined in any
	 * referenced publication.
	 * 
	 * @return array
	 */
	function getReferencedFields(){
		$ref = $this->getReferencedRecord();
		$exceptions = array('checked_out', 'checked', 'internal');
		$result = array();
		
		if($ref != null){
			if($ref->published){
				$properties = $this->_getParentAttributes();
				$properties = array_merge($properties, $this->_getSubclassAttributes());
				foreach($properties as $prop){
					if(!in_array($prop, $exceptions)){
						if(empty($this->$prop)){
							if(!empty($ref->$prop)){
								$result[$prop] = $ref->$prop;
							}
						}
					}
				}
			}
		}
		
		return $result;
		
	}
}
?>