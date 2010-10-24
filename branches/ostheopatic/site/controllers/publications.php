<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of bibliographical references or publications.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

if(!class_exists('JResearchFrontendController'))
{
	require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'controller.php');
}

/**
* JResearch Component Publications Controller
*
*/
class JResearchPublicationsController extends JResearchFrontendController
{

	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		//Load additionally language files
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.publications');
		
		// Tasks for edition of publications when the user is authenticated
		$this->registerTask('new', 'add');
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
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('filtered', 'filtered');
		$this->registerTask('changeType', 'changeType');
		$this->registerTask('export', 'export');
		$this->registerTask('exportAll', 'exportAll');
		// Add for osteopathic adaptation
		$this->registerTask('startsearch', 'startsearch');
		$this->registerTask('search', 'search');
		$this->registerTask('advancedSearch', 'advancedSearch');
				
		// Add models paths
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'publicationslist');
		$this->addViewPath(JPATH_COMPONENT.DS.'views'.DS.'publication');

		$this->addPathwayItem(JText::_('JRESEARCH_PUBLICATIONS'), 'index.php?option=com_jresearch&view=publicationslist');
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
	* Invoked when an authenticated user decides to create a publication. Prints
	* a form where the user can select the type of publication to select.
	* @access public
	*/
	function add()
	{
		$view = &$this->getView('Publication', 'html', 'JResearchView');
		
		$view->setLayout('new');
		$view->display();
	}
	
	/**
	* Invoked when an authenticated user decides to create/edit a publication
	* that belongs to him/her.
	* @access public
	*/
	function edit()
	{	
		$cid = JRequest::getVar('id');
		
		$view = &$this->getView('Publication', 'html', 'JResearchView');
		$pubModel = &$this->getModel('Publication', 'JResearchModel');	
		$model = &$this->getModel('ResearchAreasList', 'JResearchModel');
		
		if($this->getTask() == 'edit')
		{
			$publication = $pubModel->getItem($cid);
			
			if(!empty($publication))
			{
				$user =& JFactory::getUser();
				
				// Verify if it is checked out
				if($publication->isCheckedOut($user->get('id')))
				{
					$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
				}
				else
				{
					$publication->checkout($user->get('id'));	
				}
			}
			else
			{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
			}
		}
		
		$view->setLayout('edit');
		$view->setModel($model);
		$view->display();
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
		$this->addViewPath(JPATH_COMPONENT_SITE.DS.'views');
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
		$this->addViewPath(JPATH_COMPONENT_SITE.DS.'views');
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
		$document->setMimeEncoding("text/xml");		
		
		$writer = new XMLWriter;
		$writer->openMemory();
		$writer->startDocument('1.0');
		
		if($citekey != null){
			$session =& JSession::getInstance(null, null);
			$citedRecords =& $session->get('citedRecords', array(), 'jresearch');
			$index = array_search($citekey, $citedRecords);
			//Output the result
			if($index !== false){
				unset($citedRecords[$index]);				
				
				$writer->startElement("publications");
				foreach($citedRecords as $key){
					$writer->startElement('publication');
					$writer->writeElement('key', $key);
					$pub = JResearchPublication::getByCitekey($key);
					$writer->writeElement('title', $pub->title);
					$writer->endElement();
				}
				$writer->endElement();											
			}else{
				$writer->writeElement('answer', 'not found');
			}
		}	
		$writer->endDocument();
		$output = $writer->outputMemory();
		echo $output;				
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
	 	$captchaInstance = JXCaptcha::getInstance('image', array('filePath'=>JPATH_COMPONENT_SITE.DS.'views'.DS.'publication'.DS.'captcha'));
	 
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

	/**
	* Invoked when the user has decided to save a publication.
	*/	
	function save(){
		global $mainframe;
	    if(!JRequest::checkToken())
		{
		    $this->setRedirect('index.php?option=com_jresearch');
		    return;
		}
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');		
		
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_jresearch');				
		$user = JFactory::getUser();
		$id = JRequest::getInt('id');
		$post = JRequest::get('post');
		$type = JRequest::getVar('pubtype');
		$publication = JResearchPublication::getSubclassInstance($type);
		$Itemid = JRequest::getVar('Itemid');
		$ItemidText = !empty($Itemid)?'&Itemid='.$Itemid:'';		
		$publication->bind($post);		
		
		$previousFile = JRequest::getVar('old_url_0', null);
	    $countUrl = JRequest::getInt('count_url', 0);		
		$filetoremove = JPATH_COMPONENT_ADMINISTRATOR.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.$previousFile;	    
		
		//Verify if the user wants to remove old files
		$delete = JRequest::getVar('delete_url_0', false);		
	    if($delete === 'on'){	    	
	    	if($previousFile != null){
		    	@unlink($filetoremove);
		    	$publication->files = '';
	    	}
	    }
	    
		// Upload new file	    
		if(!empty($file['name'])){	
	    	$publication->files = JResearch::uploadDocument($file, $params->get('files_root_path', 'files').DS.'publications');			
	    	if($previousFile != null){
		    	//Remove previous file if it has not been removed yet    	
			    if(file_exists($filetoremove))
			    	@unlink($filetoremove);	    	
	    	}

	    }
	    
	 	//Generate an alias if needed
		$alias = trim(JRequest::getVar('alias'));
	  	if(empty($alias)){
 	  	 	$publication->alias = JResearch::alias($publication->title);
		}
		
		$publication->abstract = JRequest::getVar('abstract', '', 'post', 'string', JREQUEST_ALLOWHTML);
		$publication->original_abstract = JRequest::getVar('original_abstract', '', 'post', 'string', JREQUEST_ALLOWHTML);
			
		$check = $publication->check();		
		// Validate publication
		if(!$check){
			for($i=0; $i<count($publication->getErrors()); $i++)
				JError::raiseWarning(1, $publication->getError($i));
							
			if($publication->id)			
				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&id='.$publication->id);
			else
				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&pubtype='.$publication->pubtype);	
		}else{
			//Time to set the authors
			$maxAuthors = JRequest::getInt('nauthorsfield');
			$k = 0;
	
			for($j=0; $j<=$maxAuthors; $j++){
				$value = JRequest::getVar("authorsfield".$j);
				if(!empty($value)){
					if(is_numeric($value)){
						// In that case, we are talking about a staff member
						$publication->setAuthor($value, $k, true); 
					}else{
						// For external authors 
						$email = JRequest::getVar('authorsfieldemail'.$j);
						$publication->setAuthor($value, $k, false, $email);
					}
					
					$k++;
				}			
			}
		
			// Set the id of the author if the item is new
			if(empty($publication->id))
				$publication->created_by = $user->get('id');
			
			// Now, save the record
			$task = JRequest::getVar('task');
			$modelkey = JRequest::getVar('modelkey');
			$modeltext = $modelkey == 'tabular'?'&task=filtered':'';			
			if($publication->store(true)){			
				$idText = !empty($publication->id)?'&id='.$publication->id:'';
				
				if($task == 'apply'){
					$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit'.$idText.'&pubtype='.$publication->pubtype.$ItemidText.($modelkey?'&modelkey='.$modelkey:''), JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED'));
				}elseif($task == 'save'){
					$this->setRedirect('index.php?option=com_jresearch&controller=publications'.$ItemidText.$modeltext, JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED'));	
				}
								
			}else{
				$idText = !empty($publication->id)?'&id='.$publication->id:'';
				$taskText = '&task='.(!empty($publication->id)?'edit':'add');
				
				if($db->getErrorNum() == 1062)				
					JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_SAVED').': '.JText::_('JRESEARCH_DUPLICATED_RECORD'));
				else
					JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_SAVED').': '.$db->getErrorMsg());						
				
				if($task == 'apply'){
					$this->setRedirect('index.php?option=com_jresearch&controller=publications'.$taskText.$idText.'&pubtype='.$publication->pubtype.$ItemidText.($modelkey?'&modelkey='.$modelkey:''));
				}elseif($task == 'save'){
					$this->setRedirect('index.php?option=com_jresearch&controller=publications'.$ItemidText.$modeltext);	
				}

			}	
		}
		
		$user =& JFactory::getUser();
		if(!$publication->isCheckedOut($user->get('id'))){
			if(!$publication->checkin())
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));			
		}
		
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing publications.
	 *
	 */
	function cancel(){
		$id = JRequest::getInt('id');
		$model = &$this->getModel('Publication', 'JResearchModel');
		$Itemid = JRequest::getVar('Itemid');
		$ItemidText = !empty($Itemid)?'&Itemid='.$Itemid:'';
		$modelkey = JRequest::getVar('modelkey');
		if(!empty($modelkey) && $modelkey == 'tabular')
			$viewText = '&task=filtered&layout=filtered';
		
		
		if($id != null){
			$publication = $model->getItem($id);
			if(!$publication->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=publications'.$ItemidText.$viewText);
	}
	

	function filtered(){
		global $mainframe;
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
	
		// Use configuration parameters
		$limit = $params->get('publications_entries_per_page');			
		$filter_order_Dir = $params->get('publications_order', 'DESC');
		$filter_order = $params->get('publications_default_sorting', 'title');
		
		JRequest::setVar('limitstart', JRequest::getInt('limitstart', 0));
		JRequest::setVar('limit', $limit);		
		JRequest::setVar('filter_order', $filter_order);
		JRequest::setVar('filter_order_Dir', $filter_order_Dir);
		
		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'teams');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$view = &$this->getView('PublicationsList', 'html', 'JResearchView');
		$pubModel = $this->getModel('PublicationsList', 'JResearchModel');	
		$areaModel = $this->getModel('ResearchAreasList', 'JResearchModel'); 
		$teamsModel = $this->getModel('Teams', 'JResearchModel');
		
		$view->setModel($pubModel, true);
		$view->setModel($areaModel);
		$view->setModel($teamsModel);
		$view->setLayout('filtered');
		$view->display();
		
	}
	
	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove()
	{
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('id');
		$n = 0;		
		$publication = new JResearchPublication($db);
		
		$modelkey = JRequest::getVar('modelkey');
		if(!empty($modelkey) && $modelkey == 'tabular')
			$viewText = '&task=filtered&layout=filtered';

		$Itemid = JRequest::getVar('Itemid');
		if(!empty($Itemid))
			$itemIdText = '&Itemid='.$Itemid;	
		
		
		if(!$publication->delete($cid))
			JError::raiseWarning(1, JText::sprintf('JRESEARCH_PUBLICATION_NOT_DELETED', $cid));
		else
			$n++;
			
		$this->setRedirect('index.php?option=com_jresearch&controller=publications'.$itemIdText.$viewText, JText::sprintf('JRESEARCH_SUCCESSFULLY_DELETED', 1));
	}
	
	/**
	 * Invoked when the user has decided to change the type of a publication.
	 * It is only applied to existing items.
	 */
	function changeType(){	
		global $mainframe;
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'jresearch.php');
		
		$db = JFactory::getDBO();
		$type = JRequest::getVar('change_type');
		JRequest::setVar('pubtype', $type, 'POST', true);
		$post = JRequest::get('post');
		$publication = JResearchPublication::getSubclassInstance($type);
		$user = JFactory::getUser();
		$id = JRequest::getInt('id');
		$keepOld = JRequest::getVar('keepold', false);
		$itemIdText = '';
		$Itemid = JRequest::getVar('Itemid');
		if(!empty($Itemid))
			$itemIdText = '&Itemid='.$Itemid;	
		
		
		if(empty($id)){
			$this->setRedirect('index.php?option=com_jresearch');
			return;
		}
		
		// Get old publication
		$oldPublication = JResearchPublication::getById($id);
		
		// Get extra parameters
		$delete = JRequest::getVar('delete_url_0');
	    if($delete === 'on'){
	    	if(!empty($publication->files)){
		    	$filetoremove = JPATH_COMPONENT_ADMINISTRATOR.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.$publication->files;
		    	@unlink($filetoremove);
		    	$publication->files = '';
		    	$oldPublication->files = '';
	    	}
	    }else{
	    	$publication->files = $oldPublication->files;
	    }
	   
	    $publication->bind($post, array('id'));	    
	    
	    $countUrl = JRequest::getInt('count_url', 0);
	    $file = JRequest::getVar('file_url_'.$countUrl, null, 'FILES');
	    if(!empty($file['name'])){	    	
	    	$publication->files = JResearch::uploadDocument($file, $params->get('files_root_path', 'files').DS.'publications');
	    }
	    
	    $reset = JRequest::getVar('resethits', false);
	    if($reset == 'on'){
	    	$publication->hits = 0;
	    }

		// Validate publication	    
		$check = $publication->check();		
		if(!$check){
			for($i=0; $i<count($publication->getErrors()); $i++)
				JError::raiseWarning(1, $publication->getError($i));

			$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&id='.$oldPublication->id.'&pubtype='.$oldPublication->pubtype.$itemIdText);
				
		}else{
			//Time to set the authors
			$maxAuthors = JRequest::getInt('nauthorsfield');
			$k = 0;
	
			for($j=0; $j<=$maxAuthors; $j++){
				$value = JRequest::getVar("authorsfield".$j);
				if(!empty($value)){
					if(is_numeric($value)){
						// In that case, we are talking about a staff member
						$publication->setAuthor($value, $k, true); 
					}else{
						// For external authors 
						$publication->setAuthor($value, $k);
					}
					
					$k++;
				}			
			}
		
			// Change created by
			$publication->created_by = $user->get('id');
			
			//Remove previous publication if user has not stated it must be backup
			if($keepOld !== 'on'){
				if(!$oldPublication->delete($id))
					JError::raiseWarning(1, JText::sprintf('JRESEARCH_PUBLICATION_NOT_DELETED', $id));
			}else{
				//Rename unique fields like title and citekey
				$oldSuffix = JText::_('JRESEARCH_OLD');
				$oldPublication->title .= ' ('.$oldSuffix.')';
				$oldPublication->citekey .= $oldSuffix;
				
				// Duplicate files if they have not been removed
				if(!empty($oldPublication->files)){
					$source = JPATH_COMPONENT_ADMINISTRATOR.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.$oldPublication->files;				
					$dest = JPATH_COMPONENT_ADMINISTRATOR.DS.$params->get('files_root_path', 'files').DS.'publications'.DS.'old_'.$oldPublication->files;					
					if(!@copy($source, $dest))
						JError::raiseWarning(1, JText::_('JRESEARCH_FILE_NOT_BACKUP'));
					$oldPublication->files = 'old_'.$oldPublication->files;
				}
								
				if(!$oldPublication->store(true)){
					$idText = '&id='.$oldPublication->id;
					JError::raiseWarning(1, JText::_('JRESEARCH_OLD_PUBLICATION_NOT_SAVED'));
					$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit'.$idText.'&pubtype='.$publication->pubtype.$itemIdText);					
					return;
				} 
			}
			
			// Now, save the record
			if($publication->store(true)){							
				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&id='.$publication->id.'&pubtype='.$publication->pubtype.$itemIdText, JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED'));				
				// Trigger event
				$arguments = array('publication', $publication->id);
				$mainframe->triggerEvent('onAfterSaveJResearchEntity', $arguments);												
			}else{
				$idText = '&id='.$oldPublication->id;
				
				if($db->getErrorNum() == 1062)				
					JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_SAVED').': '.JText::_('JRESEARCH_DUPLICATED_RECORD').' '.$db->getErrorMsg());
				else
					JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_SAVED').': '.$db->getErrorMsg());

				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit'.$idText.'&pubtype='.$publication->pubtype.$itemIdText);
			}	
		}
	}
	
	/**
	 * Invoked when a user has decided to export a publication from frontend
	 * @return
	 */
	function export(){
		$params = JComponentHelper::getParams('com_jresearch');
		$exportEnabled = $params->get('enable_export_frontend');
		$document =& JFactory::getDocument();		
		$format = JRequest::getVar('format');
		
		if($exportEnabled == 'yes'){ 		
			$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications');
			require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'exporters'.DS.'factory.php'); 
			$id = JRequest::getInt('id');			
			$model = $this->getModel('Publication', 'JResearchModel');
			$publication = $model->getItem($id);
			
			if($publication == null){
				$output = JText::_('JRESEARCH_ITEM_NOT_FOUND');		
			}elseif($publication->published){			
				$exporter = JResearchPublicationExporterFactory::getInstance($format);
				$output = $exporter->parse($publication);	
				$document->setMimeEncoding($exporter->getMimeEncoding());			
			}else{
				$output = JText::_('JRESEARCH_ITEM_NOT_FOUND');
			}
			
		}else{
			$output = JText::_('JRESEARCH_ENABLE_EXPORT_FROM_FRONTEND');
			$document->setMimeEncoding('text/plain');
		}
		
		if($format == 'bibtex')
			$ext = 'bib';
		else
			$ext = $format;	
			
		$tmpfname = "jresearch_output.$ext";
		header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
		echo $output;
	}
	
	/**
	 * Invoked when the user has decided to export all public and internal publications
	 * @return 
	 */
	function exportAll(){
		//Do the export only if export from frontend is enabled		
		$exportOptions = array();	
		$params = JComponentHelper::getParams('com_jresearch');
		$exportEnabled = $params->get('enable_export_frontend');
		$document =& JFactory::getDocument();
		$format = JRequest::getVar('format', 'bibtex');				
		
		if($exportEnabled == 'yes'){				
			$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications');		
			require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'exporters'.DS.'factory.php');
			$model = $this->getModel('PublicationsList', 'JResearchModel');
			$publicationsArray = $model->getData(null, true, false);
			$exportOptions['strict_bibtex'] = false;					
			$exporter =& JResearchPublicationExporterFactory::getInstance($format);
			$output = $exporter->parse($publicationsArray, $exportOptions);
			$document->setMimeEncoding($exporter->getMimeEncoding());	
		}else{
			$output = JText::_('JRESEARCH_ENABLE_EXPORT_FROM_FRONTEND');
			$document->setMimeEncoding('text/plain');			
		}
		
		if($format == 'bibtex')
			$ext = 'bib';			
		else
			$ext = $format;	
		
		$tmpfname = "jresearch_output.$ext";
		header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
		echo $output;
	}
	
	/**
	 * Invoked in response to publications search form submit
	 * 
	 */
	function search(){
		$limitstart = JRequest::getInt('limitstart', 0);
 		JRequest::setVar('limitstart', $limitstart);	
		$view = $this->getView('PublicationsSearch', 'html', 'JResearchView');
		$pubModel = $this->getModel('PublicationsSearch', 'JResearchModel');
		$view->setModel($pubModel);
		$view->display();
	}
	
	function startsearch(){
		$url = 'index.php?option=com_jresearch&view=publicationssearch&task=search';
		// Time to construct the URL
		$limitstart = JRequest::getInt('limitstart', 0);
		$url .= '&limitstart='.$limitstart;
		
		$key = JRequest::getVar('key', '');
		if(!empty($key))
			$url .= '&key='.$key;
		
		$key1 = JRequest::getVar('key1', '');
		if(!empty($key1))
			$url .= '&key1='.$key1;

		$key2 = JRequest::getVar('key2', '');
		if(!empty($key2))
			$url .= '&key2='.$key2;

		$key3 = JRequest::getVar('key3', '');
		if(!empty($key3))
			$url .= '&key3='.$key3;
			
		$keyfield0 = JRequest::getVar('keyfield0', 'all');			
		if(!empty($key))	
			$url .= '&keyfield0='.$keyfield0;
			
		$keyfield1 = JRequest::getVar('keyfield1', 'all');			
		if(!empty($key1))	
			$url .= '&keyfield1='.$keyfield1;
			
		$keyfield2 = JRequest::getVar('keyfield2', 'all');			
		if(!empty($key2))	
			$url .= '&keyfield2='.$keyfield2;
			
		$keyfield3 = JRequest::getVar('keyfield3', 'all');			
		if(!empty($key3))	
			$url .= '&keyfield3='.$keyfield3;


		$op1 = JRequest::getVar('op1', 'and');
		if(!empty($key1))
			$url .= '&op1='.$op1;
			
		$op2 = JRequest::getVar('op2', 'and');
		if(!empty($key2))
			$url .= '&op2='.$op2;

		$op3 = JRequest::getVar('op3', 'and');
		if(!empty($key3))
			$url .= '&op3='.$op3;
			
		$with_abstract = JRequest::getVar('with_abstract', 'off');
		$url .= '&with_abstract='.$with_abstract;
					
		$pubtype = JRequest::getVar('pubtype', '0');
		$url .= '&pubtype='.$pubtype;

		$language =	JRequest::getVar('language', '0');
		$url .= '&language='.$language;

		$status = JRequest::getVar('status', '0');
		$url .= '&status='.$status;
			
		$from_year = JRequest::getVar('from_year', '');
		if(!empty($from_year))
			$url .= '&from_year='.$from_year;																			

		$from_month = JRequest::getVar('from_month', '');
		if(!empty($from_month))
			$url .= '&from_month='.$from_month;																			

		$from_day = JRequest::getVar('from_day', '');
		if(!empty($from_day))
			$url .= '&from_day='.$from_day;																			

		$to_year = JRequest::getVar('to_year', '');
		if(!empty($to_year))
			$url .= '&to_year='.$to_year;																			
		
		$to_month = JRequest::getVar('to_month', '');
		if(!empty($to_month))
			$url .= '&to_month='.$to_month;																			
						
		$to_day = JRequest::getVar('to_day', '');
		if(!empty($to_day))
			$url .= '&to_day='.$to_day;
		
		$date_field = JRequest::getVar('date_field', 'publication_date');
		$url .= '&date_field='.$date_field;
			
		$order_by1 = JRequest::getVar('order_by1', '');
		if(!empty($order_by1))
			$url .= '&order_by1='.$order_by1;																			
			
		$order_by2 = JRequest::getVar('order_by2', '');
		if(!empty($order_by2))
			$url .= '&order_by2='.$order_by2;																			

		$recommended = JRequest::getVar('recommended', '');
		if(!empty($recommended))
			$url .= '&recommended='.$recommended;																			
			
		$newSearch = JRequest::getVar('newSearch', 0);
		$url .= '&newSearch='.$newSearch;	
			
		$this->setRedirect($url);
	}
	
	/**
	 * Invoked to render advanced search form for publications
	 */
	function advancedSearch(){
		$newSearch = JRequest::getInt('newSearch', 0);
		if($newSearch == 1)
			$this->_resetUserState();
			
		$view = &$this->getView('PublicationsSearch', 'html', 'JResearchView');
		$view->setLayout('advancedsearch');
		$view->display();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _resetUserState(){
		JRequest::setVar('key', '');
		JRequest::setVar('keyfield0', 'all');
		JRequest::setVar('keyfield1', 'all');
		JRequest::setVar('keyfield2', 'all');
		JRequest::setVar('keyfield3', 'all');						
		JRequest::setVar('key1', '');
		JRequest::setVar('key2', '');		
		JRequest::setVar('key3', '');
		JRequest::setVar('op1', 'and');
		JRequest::setVar('op2', 'and');		
		JRequest::setVar('op3', 'and');
		JRequest::setVar('with_abstract', 'off');
		JRequest::setVar('pubtype', '0');
		JRequest::setVar('language', '0');
		JRequest::setVar('status', '0');
		JRequest::setVar('date_field', 'publication_date');
		JRequest::setVar('from_year', '');																		
		JRequest::setVar('from_month', '');
		JRequest::setVar('from_day', '');
		JRequest::setVar('to_year', '');																		
		JRequest::setVar('to_month', '');
		JRequest::setVar('to_day', '');
		JRequest::setVar('order_by1', '');
		JRequest::setVar('order_by2', '');
		JRequest::setVar('with_abstract', '');
		JRequest::setVar('recommended', '');													
	}
	

}
?>
