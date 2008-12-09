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
 * The class JResearchPatent is subclass of JResearchPublication and holds
 * the information of a patent.
 */
class JResearchPatent extends JResearchPublication
{	
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	public $patent_number;
	public $filing_date;
	public $issue_date;
	public $claims;
	public $drawings_dir;
	public $country;
	public $office;

	/**
	 * Class constructor.  It maps the entity to Joomla tables
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable('#__jresearch_patent');
		$this->pubtype = 'patent';
	}
	
	
}

?>