<?php

/**
 * @version		$Id: image.php 9764 2007-12-30 07:48:11Z ircmaxell $
 * @package		Joomla
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
 * Automatic Bibliography Generation Image button
 */
class plgButtonJResearch_Automatic_Bibliography_Generation extends JPlugin
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
	function plgButtonJResearch_Bibliography_Generation(& $subject, $config)
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
		global $mainframe;

		$doc 		=& JFactory::getDocument();
		$template 	= $mainframe->getTemplate();
		$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$image = $url.DS.'components'.DS.'com_jresearch'.DS.'assets'.DS.'j_button2_bibliography.png';		
		$link = 'index.php?option=com_jresearch&amp;controller=publications&amp;task=generateBibliography&amp;tmpl=component&amp;e_name='.$name;

		JHTML::_('behavior.modal');
		JHTML::_('behavior.mootools');		

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('Generate Bibliography'));
		$button->set('name', 'bibliography');
		$button->set('options', "{handler: 'iframe', size: {x: 570, y: 250}}");

		$css = ".button2-left .bibliography { ".
			   "background: url($image) 100% 0 no-repeat;".
			   "}";
		
		$doc->addStyleDeclaration($css);
		return $button;
	}
}
?>