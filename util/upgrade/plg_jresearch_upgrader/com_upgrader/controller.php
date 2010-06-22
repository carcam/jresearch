<?php

 // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class JUpdateManController extends JController {

        function  __construct() {
            parent::__construct();
            $this->addModelPath(JPATH_JRESEARCH_UPDATER.DS.'models');
            $this->addViewPath(JPATH_JRESEARCH_UPDATER.DS.'views');
            $this->registerDefaultTask('step1');
        }

	function step1() {
		JToolBarHelper::title( JText::_( 'J!Research Update Manager - Step 1' ), 'install.png' );
		// Download and parse update XML file and provide select download option
		require_once(JPATH_JRESEARCH_UPDATER.DS.'step1.php');
	}

	function step2() {
		JToolBarHelper::title( JText::_( 'J!Research Update Manager - Step 2' ), 'install.png' );
		// Download selected file (progress dialog?) and Are You Sure?
		require_once(JPATH_JRESEARCH_UPDATER.DS.'step2.php');
	}

	function step3() {
		JToolBarHelper::title( JText::_( 'J!Research Update Manager - Step 3' ), 'install.png' );
		// Install
		require_once(JPATH_JRESEARCH_UPDATER.DS.'step3.php');
	}
	
	function autoupdate() {
		$model =& $this->getModel();
		$res = $model->autoupdate();
		$view =& $this->getView('results', 'html');
		$view->setLayout(($res ? 'success' : 'failure'));
		$view->setModel($model, true); // set the model and make it default (true)
		$view->display();
	}
}
