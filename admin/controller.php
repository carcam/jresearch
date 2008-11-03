<?php
/**
* @version		$Id$
* @package		J!Research
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the base controller for the backend interface of JResearch.
* It holds the configuration related tasks.
*/

jimport('joomla.application.component.controller');

/**
* JResearch Base Backend Controller
*
* @package		J!Research
*/
class JResearchAdminController extends JController
{
	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		$this->registerDefaultTask('display');
		$this->registerTask('show', 'show');
		$this->registerTask('save', 'save');
		$this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'conf');
	}

	/**
	 * Default method, it shows the control panel for JResearch component.
	 * 
	 * @access public
	 */

	function display(){
		$view = &$this->getView('conf', 'html', 'JResearchAdminView');
		$view->display();
	}

	/**
	* Invoked when an administrator has decided to save configuration changes.
	* 
	* @access public
	*/
	function save(){
		parent::display();
	}
	
	/**
	* Invoked when an administrator has decided to see the configuration for the component
	* @access	public
	*/ 
	function show(){
		
	}
}
?>
