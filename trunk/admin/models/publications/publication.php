<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage		JResearch
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'modelSingleRecord.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'comment.php');

/**
* Model class for holding a single publication record.
*
* @subpackage		JResearch
*/
class JResearchModelPublication extends JResearchModelSingleRecord{

	/**
	* Returns the record with the id sent as parameter.
	* @param int $itemId Numeric id 
	* @return 	object
	*/
	public function getItem($itemId){
		return JResearchPublication::getById($itemId);
	}

	/**
	 * Returns the record with the citekey sent as parameter. This method considers
	 * published items only.
	 *
	 * @param string $citekey String citekey
	 * @return JResearchPublication or null if there is no published item with the citekey provided.
	 */
	public function getItemByCitekey($citekey){		
		if($this->_record == null){
			$this->_record = JResearchPublication::getByCitekey($citekey);
		}else{
			if($this->_record->citekey == $citekey){
				return $this->_record;	
			}else{
				$this->_record = JResearchPublication::getByCitekey($citekey);
			}
		}
		
		return $this->_record;
	}
	
	/**
	 * Returns an array of JResearchComment objects.
	 *
	 * @param int $id_publication The publication the comments belong to.
	 * @param int $limit How many comments will be returned at most.
	 * @param int $start Start index
	 */
	public function getComments($id_publication, $limit=5, $start=0){
		$db =& JFactory::getDBO();
		$comments = array();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_publication_comment').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($id_publication)
				.' ORDER BY datetime DESC LIMIT '.$start.', '.$limit;
				
		$db->setQuery($query);
		$result = $db->loadAssocList();		
		foreach($result as $r){
			$newComm = new JResearchPublicationComment(&$db);
			$newComm->bind($r);
			$comments[] = $newComm;
		}
		return $comments;
	}
	
	/**
	 * Returns the total number of comments posted for a publication.
	 *
	 * @param int $id_publication
	 * @return int 
	 */
	public function countComments($id_publication){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_publication_comment').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($id_publication);
		$db->setQuery($query);
		return (int)$db->loadResult();
	}
	
}
?>
