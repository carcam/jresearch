<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage		JResearch
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );


/**
* Base class for models that hold a single record.
*
* @subpackage		JResearch
*/
abstract class JResearchModelSingleRecord extends JModel{
	// If the user sends the same id twice, we just return it.
	protected $_record;

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	abstract public function getItem($itemId);

}
?>
