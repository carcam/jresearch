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
 * This class holds information about a section of a book like
 * a chapter or a pages interval.
 *
 */
class JResearchInbook extends JResearchPublication{
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * Book's editor
	 * 
	 * @var string
	 */
	public $editor;
	
	/**
	 * Book's chapter
	 * 
	 * @var string
	 */
	public $chapter;
	
	/**
	 * Book's pages interval
	 *
	 * @var string
	 */
	public $pages;
	
	/**
	 * Book's publisher
	 * 
	 * @var string
	 */
	public $publisher;
	
	/**
	 * Book's volume
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
	 * Name of the series, the book is part of.
	 *
	 * @var string
	 */
	public $type;
	
	/**
	 * Publisher's address
	 *
	 * @var string
	 */
	public $address;
	
	/**
	 * Book's edition number
	 *
	 * @var string
	 */
	public $edition;
	
	/**
	 * Month of publication.
	 *
	 * @var string
	 */
	public $month;
	

	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_inbook");
		$this->pubtype = 'inbook';
	}
	
}

?>