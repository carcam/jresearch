<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of bibliographical references or publications.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
* JResearch Component Publications Controller
*
*/
class JResearchPublicationsController extends JController
{

	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		
		// Tasks for edition of publications when the user is authenticated
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		$this->registerTask('admin', 'administer');
		// When the user sees the profile of a single publication
		$this->registerTask('show', 'show');
		$this->registerTask('cite', 'cite');
		$this->registerTask('citeFromDialog', 'citeFromDialog');
		$this->registerTask('generateBibliography', 'generateBibliography');
		$this->registerTask('removeCitedRecord', 'removeCitedRecord');
		$this->registerTask('searchByPrefix', 'searchByPrefix');
		$this->registerTask('ajaxGenerateBibliography', 'ajaxGenerateBibliography');
		$this->registerTask('ajaxRemoveAll', 'ajaxRemoveAll');
		$this->registerTask('saveComment', 'saveComment');		
		// Add models paths
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'publicationslist');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'publication');		
	}

	/**
	 * Default method, it shows the list of publications in a "ready to publish" style
	 * organized according to the configuration defined by administrators. They can be 
	 * filtered by the user if the search form is activated. 
	 *
	 * @access public
	 */

	function display(){
		global $mainframe;
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		$format = JRequest::getVar('format', 'html');	
		
		// Sort parameters
		if($format == 'html'){
			// For publications page, take configuration parameters
			$limit = $params->get('publications_entries_per_page');			
			$filter_order_Dir = $params->get('publications_order', 'DESC');
			$filter_order = $params->get('publications_default_sorting', 'year');
			$limitstart = JRequest::getVar('limitstart', null);		
			if($limitstart === null)
				JRequest::setVar('limitstart', 0);		
		}else{
			// For feeds, sort desc by year.
			$filter_order_Dir = 'DESC';
			$filter_order = 'year';	
			$limit = $params->get('publications_items_in_feed');
			$limitstart = 0;
		}
		
		JRequest::setVar('limit', $limit);		
		JRequest::setVar('filter_order', $filter_order);
		JRequest::setVar('filter_order_Dir', $filter_order_Dir);
		
		
		// Set the view and the model
		$model =& $this->getModel('PublicationsList', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('PublicationsList', $format, 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();
	}

	/**
	* Invoked when an authenticated user decides to create/edit a publication
	* that belongs to him/her.
	* @access public
	*/
	function edit(){
		
		JRequest::setVar('view', 'publications');
		JRequest::serVar('layout', 'edit');
		parent::display();
	}

	/**
	* Invoked when the visitant has decided to see the detailed description of
	* a publication.
	* @access public
	*/
	function show(){
		$model =& $this->getModel('Publication', 'JResearchModel');
		$areaModel =& $this->getModel('ResearchArea', 'JResearchModel');
		$view =& $this->getView('Publication', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->setModel($areaModel);
		$view->display();				
	}

	/**
	* Invoked when an authenticated user sees the list of his/her publications
	* in an administrator form.
	*
	*/
	function administer(){
		JRequest::setVar('view', 'publications');
		JRequest::serVar('layout', 'admin');
		parent::display();
	}

	/**
	 * Invoked when a user cites one or more records from publications database through
	 * an editor.
	 */
	function cite(){
		$output = '';
		// Search the citekey
		$model = & $this->getModel('publication', 'JResearchModel');
		$citekeys = JRequest::getVar('citekeys');		
		$citekeys = split(',', $citekeys);
		$publicationsArray = array();
		$session = JSession::getInstance(null, null);
		$citedRecords =&  $session->get('citedRecords', array(), 'jresearch');		
		
		foreach($citekeys as $key){
			$pub = $model->getItemByCitekey(trim($key));
			if($pub != null){
				$publicationsArray[] = $pub;
				if(array_search($key, $citedRecords) === false)
					$citedRecords[] = $key;
			}
		}
		
		// Get the object that executes the command		
		$command = JRequest::getVar('command');
		$citeExec =& JResearchCite::getInstance();
		if(count($publicationsArray) > 0){			
			if($command != 'bibliography')
				$output = $citeExec->$command($publicationsArray);
			
		}else{
			$citedPublications = array();
			foreach($citedRecords as $key)
				$citedPublications[] = $model->getItemByCitekey($key);
			if($command == 'bibliography')
				$output = $citeExec->bibliography($citedPublications);
				
		}
		//Output the result
		$document = &JFactory::getDocument();
		$document->setMimeEncoding("text/plain");
		echo $output;

	}
	
	/**
	 * Invoked when the user wants to cite a record using the dialog provided via editors-xtd
	 * plugin.
	 */
	function citeFromDialog(){
		// Add explicitly the view path (useful when requesting from the backend)
		$this->addViewPath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'views');
		$view = &$this->getView('PublicationsList', 'html', 'JResearchView');
		$view->setLayout('cite');
		$view->display();
	}
	
	/**
	 * Invoked when the user wants to generate the bibliography section of a text.
	 *
	 */
	function generateBibliography(){
		// Add explicitly the view path (useful when requesting from the backend)
		$this->addViewPath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'views');
		$view = &$this->getView('PublicationsList', 'html', 'JResearchView');
		$model =& $this->getModel('Publication', 'JResearchModel');
		$view->setLayout('generatebibliography');
		$view->setModel($model);
		$view->display();
	}
	
	/**
	 * Invoked when a user has decided to remove a cited record from the session
	 *
	 */
	function removeCitedRecord(){
		$citekey = JRequest::getVar('citekey', null);
		$document = &JFactory::getDocument();
		$document->setMimeEncoding("text/plain");
		if($citekey != null){
			$session =& JSession::getInstance(null, null);
			$citedRecords =& $session->get('citedRecords', array(), 'jresearch');
			$index = array_search($citekey, $citedRecords);
			//Output the result
			if($index !== false){
				unset($citedRecords[$index]);
				echo 'success';				
			}else{
				echo 'not found';
			}
		}	
	}
	
	/**
	 * Invoked when a user is searching publications through editor plugin.
	 *
	 */
	function searchByPrefix(){
		$writer = new XMLWriter;
		$writer->openMemory();
		$writer->startDocument('1.0');
		$writer->startElement("publications");
		$key = JRequest::getVar('key', null);
		
		$limitstart = JRequest::getInt('limitstart', 0);
		$limit = 10;
		$criteria = JRequest::getVar('criteria', 'all');
		$model =& $this->getModel('PublicationsList', 'JResearchModel');
		$upper = $limitstart + $limit;
		
		$writer->writeElement('lowerlimit', $limitstart);

		$returnedItems = $model->getItemsByPrefix($key, $criteria, $limitstart, $limit+1);
		
		if(count($returnedItems) < ($limit+1)){
			$upper = 0;
		}else{
			$upper = $limitstart + $limit;	
		}
		
		$writer->writeElement('upperlimit', $upper);
			
		$k = 0;	
		foreach($returnedItems as $item){
			if($k >= $limit)
				break;
			$writer->startElement('publication');	
			$writer->writeElement('title', $item->title);
			$authorsArray = $item->getAuthors();
			$separator = ' '.JText::_('and').' ';
			$authors = implode($separator, $authorsArray);
			$writer->writeElement('authors', $authors);
			$writer->writeElement('type', JText::_('JRESEARCH_'.$item->pubtype));
			$writer->writeElement('citekey', $item->citekey);
			$writer->writeElement('year', $item->year);
			$writer->endElement();
			$k++;		
		}
		
		$writer->endElement();
		$writer->endDocument();
		$document = &JFactory::getDocument();
		$document->setMimeEncoding("text/xml");
		echo $writer->outputMemory();
	}

	/**
	 * Invoked when a user has decided to generate the bibliography section
	 * for a document from the popup dialog via editors-xtd Generate Bibliography
	 * button.
	 *
	 */
	function ajaxGenerateBibliography(){
		$document = &JFactory::getDocument();
		$document->setMimeEncoding("text/plain");
		
		$session = &JSession::getInstance(null, null);
		$citekeysArray = $session->get('citedRecords', array(), 'jresearch');

		// Get the complete publications
		foreach($citekeysArray as $key){
			$pub = JResearchPublication::getByCitekey(trim($key));
			if($pub != null){
				$publicationsArray[] = $pub;
			}
		}
		
		if(count($publicationsArray) > 0){
			$citeExec =& JResearchCite::getInstance();		
			$output = $citeExec->bibliography($publicationsArray);
			echo $output;
		}	
	}
	
	/**
	 * Invoked when the user wants to remove all the cited records stored
	 * in session.
	 *
	 */
	function ajaxRemoveAll(){
		$document = &JFactory::getDocument();
		$document->setMimeEncoding("text/plain");
		
		$session = &JSession::getInstance(null, null);
		$session->set('citedRecords', array(), 'jresearch');
		echo 'success';
	}

	/**
	 * Invoked when a user has posted a comment.
	 *
	 */
	function saveComment(){
		global $mainframe;
		jximport('jxtended.captcha.captcha');
		jimport('joomla.utilities.date');
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'comment.php');
	 	$post = JRequest::get('post');

	 	// Get the captcha tests
	 	$captchas = $mainframe->getUserState('jxcaptcha.captcha');
	 	$captchaInstance = JXCaptcha::getInstance('image', array('filePath'=>JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'views'.DS.'publication'.DS.'captcha'));
	 
	 	foreach ($captchas as $captcha){
	 		if (isset($post[$captcha['id']]))
	 		{
	 			if (!$captchaInstance->validate($captcha['id'], $post[$captcha['id']], false)){
	 				JError::raiseWarning(1, JText::_('The user has not passed the verification test.'));
	 				$failed = true;
	 				break;
	 			}
	 		}
	 	}
	 	
	 	// If the user passed the test, save the comment.
	 	if(!$failed){
	 		$db =& JFactory::getDBO();
	 		$comment = new JResearchPublicationComment($db);
	 		$comment->bind($post);
	 		$now = new JDate();
	 		$comment->datetime = $now->toMySQL();
	 		if(!$comment->store()){
	 			$this->setRedirect('index.php?option=com_jresearch&view=publication&task=show&id='.$post['id_publication'].'&showcomm=1&Itemid='.$post['Itemid']);
	 		}else{
				$this->setRedirect('index.php?option=com_jresearch&view=publication&task=show&id='.$post['id_publication'].'&showcomm='.$post['showcomm'].'&Itemid='.$post['Itemid']);	 			
	 		}
	 	}else{
	 		$this->setRedirect('index.php?option=com_jresearch&view=publication&task=show&id='.$post['id_publication'].'&showcomm='.$post['showcomm'].'&Itemid='.$post['Itemid']);	 			
	 	}

	}

}
?>
