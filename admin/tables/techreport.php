<?php
/**
* @version		$Id$
* @package		J!Research
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
 * Holds information about a technical report.
 *
 */
class JResearchTechReport extends JResearchPublication{
	
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * Institution that sponsors the technical report
	 *
	 * @var string
	 */
	public $institution;
	
	/**
	 * 
	 *
	 * @var string
	 */
	public $type;
	
	/**
	 * The number of the publication when it is part of a series.
	 * 
	 * @var string
	 */
	public $number;
	
	/**
	 * Institution's address
	 *
	 * @var string
	 */
	public $address;
	
	/**
	 * Month of publication
	 * 
	 * @var int
	 */
	public $month;

	
	/**
	 * Class constructor.  It maps the entity to Joomla tables
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_techreport");
		$this->pubtype = 'techreport';
	}
}

?>