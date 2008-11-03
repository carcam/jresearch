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
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'publication.php');

/**
 * This class holds the information related to a technical manual or 
 * report.
 *
 */
class JResearchManual extends JResearchPublication{
	
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * Organization that published the manual.
	 *
	 * @var string
	 */
	public $organization;
	
	/**
	 * Organization's address
	 * 
	 * @var string
	 */
	public $address;
	
	/**
	 * Manual's edition number
	 * 
	 * @var string
	 */
	public $edition;
	
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
		parent::setDerivedTable("#__jresearch_manual");
		$this->pubtype = 'manual';
	}
}

 ?>