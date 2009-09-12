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
 * This class holds information about online source which covers: websites, videos, audio 
 * podcasts, blogs and images.
 *
 */
class JResearchOnline_source extends JResearchPublication{
	/**
	 * Database integer ID
	 *
	 * @var int
	 */
	public $id_publication;
	
	
	/**
	 * The date the resource was accessed.
	 *
	 * @var datetime
	 */
	public $access_date;

	/**
	 * The type of resource: image, website, blog, video or audio
	 *
	 * @var string
	 */
	public $source_type;
	
	/**
	 * Extra HTML.
	 *
	 * @var unknown_type
	 */
	public $extra;
	
	/**
	 * Class constructor. Maps the class to Joomla tables.
	 *
	 * @param JDatabase $db
	 */
	function __construct(&$db){
		parent::__construct($db);
		parent::setDerivedTable("#__jresearch_online_source");
		$this->pubtype = 'online_source';
	}
	
}

?>