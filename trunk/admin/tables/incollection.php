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
 * This class holds information about a section of a book that has its own
 * title.
 *
 */
class JResearchIncollection extends JResearchPublication{
	
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * Book's title
	 * 
	 * @var string
	 */
	public $booktitle;
	
	/**
	 * Book's publisher
	 * 
	 * @var string
	 */
	public $publisher;
	
	/**
	 * @var string
	 */
	public $editor;
	
	/**
	 * The organization that sponsors the publication of the book.
	 * 
	 * @var string
	 */
	public $organization;
	
	/**
	 * 
	 * The number of the book when it is part of a series.
	 * 
	 * @var string
	 */
	public $address;
		
	/**
	 * Book's pages interval
	 *
	 * @var string
	 */
	public $pages;
	
	/**
	 * @var string
	 */
	public $key;
	
	/**
	 * Month of publication.
	 *
	 * @var int
	 */
	public $month;
	
	
	/**
	 * Used exclusively for cross-referencing with book records. 
	 * 
	 * @var string
	 */
	public $crossref;
	
	
	/**
	 * Class constructor. Maps the class to Joomla tables.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_incollection");
		$this->pubtype = 'incollection';
	}
}
?>