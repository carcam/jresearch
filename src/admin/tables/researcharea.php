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

jresearchimport('tables.table', 'jresearch.admin');

/**
 * This class represents a research area.
 *
 */
class JResearchResearcharea extends JResearchTable{

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
        if(empty($this->name)){
            $this->setError(JText::_('JRESEARCH_PROVIDE_VALID_TITLE'));
            return false;
        }

        return true;
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
            $booleanResult = $booleanResult && $this->_keepIntegrity('publication', $oid);
            $booleanResult = $booleanResult && $this->_keepIntegrity('project', $oid);
            $booleanResult = $booleanResult && $this->_keepIntegrity('member', $oid);
            $booleanResult = $booleanResult && $this->_keepIntegrity('thesis', $oid);

        	// Set as uncategorized any item related to this research area
            $queryPub = 'DELETE FROM '.$db->quoteName('#__jresearch_publication_research_area')
                        .' WHERE '.$db->quoteName('id_research_area').' = '.$db->Quote($oid);

            $queryProj = 'DELETE FROM '.$db->quoteName('#__jresearch_project_research_area')
                         .' WHERE '.$db->quoteName('id_research_area').' = '.$db->Quote($oid);

            $queryStaff = 'DELETE FROM '.$db->quoteName('#__jresearch_member_research_area')
                          .' WHERE '.$db->quoteName('id_research_area').' = '.$db->Quote($oid);

            $queryThes = 'DELETE FROM '.$db->quoteName('#__jresearch_thesis_research_area')
                          .' WHERE '.$db->quoteName('id_research_area').' = '.$db->Quote($oid);

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

    /**
     * Fixes the id_research_area column of all entities referencing the current area
     * @return boolean
     */
    function _keepIntegrity($tableSuffix, $oid){
    	$db = JFactory::getDbo();
    	$booleanResult = true;
    	$query = $db->getQuery(true);
    	$primaryTable = '#__jresearch_'.$tableSuffix;
    	$query->select('t.id, t.id_research_area');
    	$query->from("$primaryTable t");
    	$query->join('', "#__jresearch_".$tableSuffix."_research_area tr");
    	$query->where("t.id = tr.id_$tableSuffix");
    	$db->setQuery($query);
        $result = $db->loadAssocList();
        foreach($result as $row){
            $elements = explode(',', $row['id_research_area']);
            for($i = 0; $i < count($elements); $i++){
                if($elements[$i] == $oid){
                    unset($elements[$i]);
                    break;
                }
            }
            $db->setQuery("UPDATE $primaryTable SET id_research_area = ".$db->Quote(implode(',', $elements)));
            $booleanResult = $booleanResult && $db->query();
        }

        return $booleanResult;
    }


    public function store($updateNulls = false){
        jresearchimport('joomla.utilities.date');
        $dateObj = new JDate();
        $user = JFactory::getUser();
        $author = '';
        if(!isset($this->id)){
            $created = JRequest::getVar('created', $dateObj->toSql());
            $this->created = $created;
            $author = JRequest::getVar('created_by', $user->get('id'));
            $this->created_by = $author;
            $this->ordering = parent::getNextOrder();
        }

        $this->modified = $dateObj->toSql();
        $this->modified_by = $author;
        $result = false;
        try {
            $result = parent::store($updateNulls);
        } catch (RuntimeException $ex) {
            $this->setError(parent::getError().' '.$ex->getMessage());
        }

        // If the item is unpublished, unpublished all its children
        if ($result && $this->published == 0 && !empty($this->id)) {
            $this->_unpublishChildren($this->id);
        }

        return $result;

     }


    function __toString(){
    	return isset($this->title) ? $this->title : "";
    }

    /**
     * Method to compute the default name of the asset.
     * The default name is in the form `table_name.id`
     * where id is the value of the primary key of the table.
     *
     * @return	string
     * @since	1.6
     */
    protected function _getAssetName()
    {
        $k = $this->_tbl_key;
        return 'com_jresearch.researcharea.'.(int) $this->$k;
    }
}

?>
