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
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'publication.php');

/**
 * This class holds the information about a masther degree thesis
 *
 */
class JResearchMastersthesis extends JResearchPublication{
	
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * School where the thesis was published.
	 *
	 * @var string
	 */
	public $school;
	
	/**
	 * 
	 *
	 * @var string
	 */
	public $type;
	
	/**
	 * School's address
	 * 
	 * @var string
	 */
	public $address;
	
	
	/**
	 * Class constructor. Maps the class to Joomla tables.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_mastersthesis");
		$this->pubtype = 'mastersthesis';
	}
}

 ?>