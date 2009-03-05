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
 * This class holds the information of an article published
 * in a congress.
 *
 */
class JResearchConference extends JResearchPublication{

	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * Book's isbn
	 *
	 * @var string
	 */
	public $isbn;
	
	/**
	 * Conference's issn
	 *
	 * @var string
	 */
	public $issn;
	
	/**
	 * Conference's editor
	 * 
	 * @var string
	 */
	public $editor;
	
	/**
	 * Volume of the publication where the article appears.
	 *
	 * @var string
	 */
	public $volume;
	
	/**
	 * The name of publications series related to the conference.
	 * 
	 * @var string
	 */
	public $series;
	
	/**
	 * The pages where the article is located in the publication.
	 * 
	 * @var string
	 */
	public $pages;

	/**
	 * @var string
	 */
	public $number;
	
	/**
	 * Publisher's address
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
	* Title of the book where the article was published.
	*/	
	public $booktitle;
	
	/**
	 * Article's publisher
	 * 
	 * @var string
	 */
	public $publisher;
	
	/**
	 * Name of the organization that supports the conference.
	 *
	 * @var string
	 */
	public $organization;
	
	/**
	 * Used exclusively for cross-referencing with proceedings records. 
	 * 
	 * @var string
	 */
	public $crossref;
	
	/**
	 * Class constructor.  It maps the entity to Joomla tables.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_conference");
		$this->pubtype = 'conference';
	}
}

?>