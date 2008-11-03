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

/**
 * This class represents a comment posted by a user in relation
 * to a publication.
 *
 */
class JResearchPublicationComment extends JTable{
	
	/**
	 * Database id
	 *
	 * @var int
	 */
	public $id;
	
	/**
	 * Id of the commented publication
	 *
	 * @var int
	 */
	public $id_publication;
	
	/**
	 * Comment's subject (title)
	 *
	 * @var string
	 */
	public $subject;
	
	/**
	 *
	 * @var string
	 */
	public $content;
	
	/**
	 * Author name
	 *
	 * @var string
	 */
	public $author;
	
	/**
	 * When the comment was posted.
	 *
	 * @var datetime
	 */
	public $datetime;
	
	/**
	 * Class constructor
	 */
	public function __construct(&$db){
		parent::__construct('#__jresearch_publication_comment', 'id', $db);
	}
	
}
?>