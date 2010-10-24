<?php
/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );


/**
* Base class for models that hold lists of records.
*
*/
abstract class JResearchModelList extends JModel{
	/**
	 * Array of cached retrieved records. Used only to cache the information
	 * retrieved by getData method
	 *
	 * @var array
	 */
	protected $_items;
	
	/**
	* JPagination object
	*/
	protected $_pagination;
		
	/**
	* Name of the table that stores the items.
	*/
	protected $_tableName;
	
	/**
	* Cached id of the author of the records.
	*/
	protected $_memberId = null;
	
	/**
	* Cached parameter.
	*/
	protected $_onlyPublished = null;
	
	/**
	* Cached parameter.
	*/
	protected $_paginate = null;
	
	/**
	* Class constructor.
	*/
	public function __construct(){
		global $mainframe;
		$option = JRequest::getVar('controller');
		parent::__construct();
		$this->_items = array();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'));
		$limitstart = $mainframe->getUserStateFromRequest($option.'limitstart', 'limitstart', 0);
		
		//Set the state pagination variables
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	}	
	
	/**
	* Returns an array of the items of an entity independently of its published state and
	* considering pagination issues and authoring.
	* backend functionality.
	* @param 	$memberId Id of the user that is author of the entity.
	* @param 	$onlyPublished If true, only published records will be considered in the query
	* @param 	$paginate If true, user state information will be considered.
	* @return 	array
	*/
	abstract public function getData($memberId = null, $onlyPublished = false, $paginate = false);

	/**
	* Like method _buildQuery, but it does not consider LIMIT clause.
	* 
	* @return string SQL query.
	*/		
	abstract protected function _buildRawQuery();	

	/**
	* Returns the SQL used to get the data from publications table.
	* 
	* @pàram $memberId If non null, it represents the id of a staff member and the method returns
	* only those items of the member's authoring.
	* @param $onlyPublished If true, returns only published items.
	* @param $paginate If true, the method considers pagination user parameters
	*
	* @return string
	*/
	abstract protected function _buildQuery($memberId = null, $onlyPublished = false, $paginate = false );


	/**
	* Updates the pagination object according to the parameters sent when trying to retreive
	* the data. Should be invoked after getData.
	*/
	protected function updatePagination(){
		jimport('joomla.html.pagination');
		$db =& JFactory::getDBO();
		
		$db->setQuery($this->_buildRawQuery());
			
		//prepare the pagination values
		$total = $db->loadResult();
		$limitstart = $this->getState('limitstart');
		$limit = $this->getState('limit');
		if($total <= $limitstart)
			$limitstart = 0;

		$this->_pagination = new JPagination($total, $limitstart, $limit);

	}
	
	/**
	* Get a pagination object
	* 
	* @access public
	* @return JPagination
	*/
	public function getPagination(){
		return $this->_pagination;
	}	

}
?>
