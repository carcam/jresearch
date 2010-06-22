<?php
/**
 * @version		$Id: adapterinstance.php 12276 2009-06-22 01:54:01Z pasamio $
 * @package		Joomla.Framework
 * @subpackage	Base
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Adapter Instance Class
 *
 * @package		Joomla.Framework
 * @subpackage	Base
 * @since		1.6
 */
class JAdapterInstance extends JObject {
	
	/** Parent 
	 * @var object */
	protected $parent = null;
	
	/** Database
	 * @var object */
	protected $db = null;
	
	
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$parent	Parent object [JAdapter instance]
	 * @return	void
	 * @since	1.6
	 */
	public function __construct(&$parent, &$db)
	{
		$this->parent =& $parent;
		$this->db =& $db ? $db : JFactory::getDBO(); // pull in the global dbo in case
	}
}