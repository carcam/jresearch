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
class JResearchRecava_article extends JResearchPublication{
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
	 * @var boolean 
	 */
  	public $recava_ack;
  	
  	/**
  	 * @var boolean
  	 */
  	public $other_recava_groups;

  	/**
  	 * @var string
  	 */
  	public $recava_groups;
  	
  	/**
  	 * @var string
  	 */
  	public $used_recava_platforms;
  	
  	/**
  	 * @var string
  	 */
  	public $recava_platforms;

  	/**
  	 * @var string
  	 */
  	public $priority_line;
  	
  	
  	/**
  	 * @var string 
  	 */
  	public $secondary_lines;
	
	public $id_journal;
	
	/**
	 * Class constructor.  It maps the entity to Joomla tables
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_recava_article");
		$this->pubtype = 'recava_article';
	}
	
	function getImpactFactor(){
		if(empty($this->id_journal))
			return $this->impact_factor;
		else{
			require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'journal.php');
			$journal = JTable::getInstance('Journal', 'JResearch');
			$journal->load($this->id_journal);
			return $journal->impact_factor;
		}	
	}
	
	function getJournal(){
		if(empty($this->id_journal))
			return $this->journal;
		else{
			require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'journal.php');
			$journal = JTable::getInstance('Journal', 'JResearch');
			$journal->load($this->id_journal);
			return $journal->title;
		}	
	}
	
}

?>
