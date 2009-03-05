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
 * The class JResearchArticle is subclass of JResearchPublication and holds
 * the information of an article published in a journal or conference.
 *
 */
class JResearchArticle extends JResearchPublication{
	/**
	 * Integer database id
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * Journal where the article was published.
	 * 
	 * @var string
	 */
	public $journal;
	
	/**
	 * Journal's issn
	 *
	 * @var string
	 */
	public $issn;
	
	/**
	 * Volume of the journal where the article appears.
	 *
	 * @var string
	 */
	public $volume;
	
	/**
	 * The number of the magazine or journal where the article appears.
	 * 
	 * @var string
	 */
	public $number;
	
	/**
	 * The magazine or journal pages where the article is located.
	 * 
	 * @var string
	 */
	public $pages;
	
	
	/**
	 * Month of publication
	 * 
	 * @var int
	 */
	public $month;
	
	/**
	 * Used exclusively for cross-referencing with book, inbook or article records. 
	 * 
	 * @var string
	 */
	public $crossref;
		
	
	/**
	 * Class constructor.  It maps the entity to Joomla tables
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_article");
		$this->pubtype = 'article';
	}
}


?>