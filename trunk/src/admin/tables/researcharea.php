<?php
/** 
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
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
class JResearchResearcharea extends JTable{
	/**
	 * Database integer id
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
	 * @var datetime
	 */
	public $checked_out_time;

        /**
         * @var datetime
         */

        public $created;

        /**
         * @var datetime
         */

        public $modified;


        /**
         *
         * @var int
         */
        public $created_by;

        /**
         *
         * @var int
         */
        public $modified_by;


        /**
         *
         * @var int
         */
        public $ordering;
	
	
	/**
	 * Class constructor. Maps the entity to the appropiate table.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
            parent::__construct('#__jresearch_research_area', 'id', $db);
	}
	

	/**
	* Validates the information stored in the object.
	*
	* @return boolean True if the object can be stored in the database (every field is valid), false
	* otherwise. 
	*/
	function check(){
            $name_pattern = '/^\w[-_\w\d\s]+$/';

            // Validate first and lastname
            if(!preg_match($name_pattern, $this->name)){
                    $this->setError(JText::_('JRESEARCH_PROVIDE_VALID_TITLE'));
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
            $db = JFactory::getDBO();
            $result = parent::publish($cid, $publish, $user_id);

            if($result && $publish == 0){
                $this->_unpublishChildren($cid);
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
            $db = JFactory::getDBO();
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
        

        public function store($updateNulls = false){
            jresearchimport('joomla.utilities.date');
            $dateObj = new JDate();
            $user = JFactory::getDBO();

            if(isset($this->id)){
                $created = JRequest::getVar('created', $dateObj->toMySQL());
                $this->created = $created;
                $author = JRequest::getVar('created_by', $user->id);
                $this->created_by = $author;
            }
            
            $this->modified = $dateObj->toMySQL();
            $this->modified_by = $author;
            if(empty($this->alias))
                $this->alias = JFilterOutput::stringURLSafe($this->name);

            $result = parent::store($updateNulls);
            
            // If the item is unpublished, unpublished all its children
            if($this->published == 0 && !empty($this->id)){
                $this->_unpublishChildren($this->id);
            }

            return $result;

        }

        /**
         * Invoked when a research area is unpublished. All items belonging ONLY to this researcharea
         * are unpublished.
         * @param mixed $cid Area id or array of them
         */
        private function _unpublishChildren($cid){
            if(!is_array($cid)){
                $this->_unpublishChildrenFromSingle($cid);
            }else{
                foreach($cid as $id){
                    $this->_unpublishChildrenFromSingle($id);
                }
            }
        }

        /**
         * Invoked when a research area is unpublished. All items belonging ONLY to this researcharea
         * are unpublished.
         * @param mixed $cid Area id or array of them
         */
        private function _unpublishChildrenFromSingle($id){
            $unpublishPub = false;
            $unpublishProj = false;
            $unpublishThes = false;
            $unpublishMem = false;
            $db = JFactory::getDBO();


            $queryPub = 'SELECT '.$db->nameQuote('id_publication').' FROM '.$db->nameQuote('#__jresearch_publication_researcharea');
            $queryPub.= ' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($id);

            $queryProj = 'SELECT '.$db->nameQuote('id_project').' FROM '.$db->nameQuote('#__jresearch_project_researcharea');
            $queryProj.= ' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($id);

            $queryThes = 'SELECT '.$db->nameQuote('id_thesis').' FROM '.$db->nameQuote('#__jresearch_thesis_researcharea');
            $queryThes.= ' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($id);

            $queryMem = 'SELECT '.$db->nameQuote('id_member').' FROM '.$db->nameQuote('#__jresearch_member_researcharea');
            $queryMem.= ' WHERE '.$db->nameQuote('id_research_area').' = '.$db->Quote($id);


            $db->setQuery($queryPub);
            $resultPub = $db->loadResultArray();
            if(count($resultPub) == 1)
                $unpublishPub = true;

            $db->setQuery($queryProj);
            $resultProj = $db->loadResultArray();
            if(count($resultProj) == 1)
                $unpublishProj = true;

            $db->setQuery($queryThes);
            $resultThes = $db->loadResultArray();
            if(count($resultThes) == 1)
                $unpublishThes = true;

            $db->setQuery($queryMem);
            $resultMem = $db->loadResultArray();
            if(count($resultMem) == 1)
                $unpublishMem = true;


            if($unpublishPub){
                $db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_publication').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id').' IN ('.implode(',', $resultPub).')');
                $db->query();
            }

            if($unpublishProj){
                $db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_project').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id').' IN ('.implode(',', $resultProj).')');
                $db->query();
            }

            if($unpublishThes){
                $db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_thesis').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id').' IN ('.implode(',', $resultThes).')');
                $db->query();
            }

            if($unpublishMem){
                $db->setQuery('UPDATE '.$db->nameQuote('#__jresearch_member').' SET '.$db->nameQuote('published').' = '.$db->Quote(0).' WHERE '.$db->nameQuote('id').' IN ('.implode(',', $resultMem).')');
                $db->query();
            }

        }
	
}	

?>