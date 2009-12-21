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
 * The information about the act or record of proceedings.
 *
 */
class JResearchProceedings extends JResearchPublication{
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	/**
	 * Publication's editor
	 * 
	 * @var string
	 */
	public $editor;
	
	/**
	 * Publication's volume
	 *
	 * @var string
	 */
	public $volume;
	
	/**
	 * 
	 * The number of the book when it is part of a series.
	 * 
	 * @var string
	 */
	public $number;
	
	/**
	 * Name of the series, the publication is part of.
	 *
	 * @var string
	 */
	public $series;
	
	/**
	 * Usually, publisher's address
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
	 * Book's publisher
	 * 
	 * @var string
	 */
	public $publisher;
	
	/**
	 * Organization that sponsored the proceedings.
	 * 
	 * @var string 
	 */
	public $organization;
	
/**
	 * Class constructor. Maps the class to Joomla tables.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_proceedings");
		$this->pubtype = 'proceedings';
	}
	
	
}

?>