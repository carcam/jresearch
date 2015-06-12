<?php
/**
 * @version		$Id$
* @package		JResearch
* @subpackage	Plugins
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Editor Image button
 */
class plgButtonJResearch_Automatic_Citation extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function onDisplay($name)
	{
		$doc = JFactory::getDocument();
		$url = JURI::root();
		$image = $url.'components/com_jresearch/assets/quote.png';			

		$link = 'index.php?option=com_jresearch&amp;controller=publications&amp;task=citeFromDialog&amp;tmpl=component&amp;e_name='.$name;

		JHtml::_('behavior.modal');

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('Cite'));
		$button->set('class', 'btn');
		$button->set('name', 'cite');		
		$button->set('options', "{handler: 'iframe', size: {x: 700, y: 400}}");

		$css = ".icon-cite { ".
			   "background: url($image) 100% 0 no-repeat;".
			   "}";
		
		$doc->addStyleDeclaration($css);

		
		return $button;
	}
}
