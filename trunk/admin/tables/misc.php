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
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'publication.php');

/**
 * This class holds information about publication that do not belong to any of
 * other subtypes in JResearchPublication hierachy.
 *
 */
class JResearchMisc extends JResearchPublication{
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * Information about how the article was published.
	 *
	 * @var string
	 */
	public $howpublished;
	
	/**
	 * Month of publication
	 *
	 * @var int
	 */
	public $month;

	
	/**
	 * Class constructor. Maps the class to Joomla tables.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_misc");
		$this->pubtype = 'misc';
	}
	
}

?>