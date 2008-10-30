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
 * This class represents a research area.
 *
 */
class JResearchArea extends JTable{
	/**
	 * Database integer id
	 *
	 * @var int
	 */
	public $id;
	
	/**
	 * Research area's name
	 *
	 * @var string
	 */
	public $name;
	
	/**
	 * Research area's description
	 *
	 * @var string
	 */
	public $description;
	
	/**
	 * Published state
	 * 
	 * @var boolean
	 */
	public $published;
	
		
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
	 * Class constructor. Maps the entity to the appropiate table.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct('#__jresearch_research_area', 'id', $db);
	}
	
	/**
	 * Returns an array with all research areas.
	 *
	 * @param boolean $onlyPublic If true, only published research areas are retrieved.
	 * @return array of JResearchArea objects
	 * 
	 */
	public static function getAllItems($onlyPublic = false){
		$db = &JFactory::getDBO();
		$areas = array();
		
		if($onlyPublic){
			$where = ' WHERE '.$db->nameQuote('published').' = '.$db->Quote('1');
		}else{
			$where = '';
		}
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_research_area').$where;
		$db->setQuery($query);
		
		$result = $db->loadAssocList();
		
		foreach($result as $r){
			$ra = new JResearchArea($db);
			$ra->bind($r);
			$areas[] = $ra;	
		}
		
		
		return $areas;
	}
	

	/**
	* Validates the information stored in the object.
	*
	* @return boolean True if the object can be stored in the database (every field is valid), false
	* otherwise. 
	*/
	function check(){
		$name_pattern = '/\w[-_\w\s]+/';
		
		// Validate first and lastname
		if(!preg_match($name_pattern, $this->name)){
			$this->setError(JText::_('Please, provide a valid name. Only alphabetic characters plus _- are allowed.'));
			return false;
		}
		
		return true;

	}
	
	/**
	* Publish/Unpublish method.
	*
	* @param $cid Ids of the items to publish/unpublish
	* @param $publish If 1 the items are published, if 0 are unpublished
	* @param $user_id The id of the user performing the operation
	* @return true if successful
	*/
	function publish( $cid=null, $publish=1, $user_id=0 ){
		$db =& JFactory::getDBO();		
		$result = parent::publish($cid, $publish, $user_id);
		
		if($result && $publish == 0){
			if(!is_array($cid)){
				$db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_publication').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($cid));
				$db->query();
				$db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_project').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($cid));
				$db->query();	
				$db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_member').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($cid));
				$db->query();			
				$db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_thesis').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($cid));	
				$db->query();
			}else{
				foreach($cid as $id){
					$db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_publication').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($id));
					$db->query();
					$db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_project').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($id));
					$db->query();	
					$db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_member').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($id));
					$db->query();			
					$db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_thesis').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($id));	
					$db->query();
				}
			}
		}
		
		return $result;
	}
	
	/**
    * Default delete method. It can be overloaded/supplemented by the child class
    *
    * @access public
    * @return true if successful otherwise returns and error message
    */
   function delete($oid=null){
   	$db =& JFactory::getDBO();
   	$booleanResult = parent::delete($oid);
   	
   	if($booleanResult){
   		// Set as uncategorized any item related to this research area
   		$queryPub = 'UPDATE '.$db->nameQuote('#__jresearch_publication').' SET '.$db->nameQuote('id_research_area').' = '.$db->Quote(1)
   					.' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($oid);
   					
   		$queryProj = 'UPDATE '.$db->nameQuote('#__jresearch_project').' SET '.$db->nameQuote('id_research_area').' = '.$db->Quote(1)
   					.' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($oid);
   		
   		$queryStaff = 'UPDATE '.$db->nameQuote('#__jresearch_member').' SET '.$db->nameQuote('id_research_area').' = '.$db->Quote(1)
   					.' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($oid);						
   					
   		$queryThes = 'UPDATE '.$db->nameQuote('#__jresearch_thesis').' SET '.$db->nameQuote('id_research_area').' = '.$db->Quote(1)
   					.' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($oid);			
   					
   		$db->setQuery($queryPub);
   		$db->query();
   		$db->setQuery($queryProj);			
   		$db->query();
   		$db->setQuery($queryStaff);			
   		$db->query();
   		$db->setQuery($queryThes);			
   		$db->query();

   	}
   	
   	return $booleanResult;
	}
	
}	

?>