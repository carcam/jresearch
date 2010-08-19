<?php
/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport( 'joomla.application.component.modelitem' );


/**
* Base class for models that hold a single record.
*
*/
abstract class JResearchModelItem extends JModelItem{
	// If the user sends the same id twice, we just return it.
	protected $_row;

	/**
	* Returns the record with the id sent as parameter.
	* @return 	object
	*/
	abstract public function getItem();
}
?>
