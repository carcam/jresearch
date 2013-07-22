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
	require_once(JRESEARCH_COMPONENT_SITE.DS.'helpers'.DS.'controller.php');
}

jresearchimport('helpers.publications', 'jresearch.admin');

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
		
		$this->registerDefaultTask('display');
		
		// Tasks for edition of publications when the user is authenticated
		$this->registerTask('new', 'add');
		$this->registerTask('add', 'edit');
		$this->registerTask('edit', 'edit');
		// When the user sees the profile of a single publication
		$this->registerTask('show', 'show');
		$this->registerTask('cite', 'cite');
		$this->registerTask('citeFromDialog', 'citeFromDialog');
		$this->registerTask('citeFromForm', 'citeFromForm');
		$this->registerTask('generateBibliography', 'generateBibliography');
		$this->registerTask('removeCitedRecord', 'removeCitedRecord');
		$this->registerTask('searchByPrefix', 'searchByPrefix');
		$this->registerTask('ajaxGenerateBibliography', 'ajaxGenerateBibliography');
		$this->registerTask('ajaxRemoveAll', 'ajaxRemoveAll');
		$this->registerTask('saveComment', 'saveComment');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('changeType', 'changeType');
		$this->registerTask('exportSingle', 'export');
		$this->registerTask('exportAll', 'exportAll');
		$this->registerTask('executeImport', 'executeImport');
				
		// Add models paths
		$this->addModelPath(JRESEARCH_COMPONENT_SITE.DS.'models'.DS.'publications');
		$this->addViewPath(JRESEARCH_COMPONENT_SITE.DS.'views'.DS.'publicationslist');
		$this->addViewPath(JRESEARCH_COMPONENT_SITE.DS.'views'.DS.'publication');

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
		$mainframe = JFactory::getApplication();
		
		$layout = JRequest::getVar('layout');
		switch($layout){
			case 'new':
				$this->add();
				JRequest::setVar('task', 'new');
				$this->set('task', 'new');
				return;
			case 'edit':
				$id = JRequest::getInt('id', 0);
				$task = !empty($id) ? 'edit' : 'add';
				JRequest::setVar('task', $task );						
				$this->set('task', $task );
				$this->edit();
				return;	
		}		
		
		//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		$format = JRequest::getVar('format', 'html');	
		
		// Sort parameters
		if($format == 'html'){
			// For publications page, take configuration parameters
			$limit = $params->get('publications_entries_per_page', 25);			
			$filter_order_Dir = $params->get('publications_order', 'DESC');
			$filter_order = $params->get('publications_default_sorting', 'year');
			$limitstart = JRequest::getVar('limitstart', null);		
			if($limitstart === null)
				JRequest::setVar('limitstart', 0);		
		}else{
			// For feeds, sort desc by year.
			$filter_order_Dir = 'DESC';
			$filter_order = 'year';	
			$limit = $params->get('publications_items_in_feed', 10);
			$limitstart = 0;
		}
		
		JRequest::setVar('limit', $limit);		
		JRequest::setVar('filter_order', $filter_order);
		JRequest::setVar('filter_order_Dir', $filter_order_Dir);
					
		// Set the view and the model
		$model = $this->getModel('Publications', 'JResearchModel');
		$view = $this->getView('Publications', $format, 'JResearchView');
		$view->setModel($model, true);
		$view->setLayout(JRequest::getVar('layout', 'default'));		
		$view->display();
	}

	/**
	* Invoked when an authenticated user decides to create a publication. Prints
	* a form where the user can select the type of publication to select.
	* @access public
	*/
	function add()
	{
		$canDo = JResearchAccessHelper::getActions();
		if($canDo->get('core.publications.create')){
			$view = $this->getView('Publication', 'html', 'JResearchView');		
			$view->setLayout('new');
			$view->display();
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));		
		}
	}
	
	/**
	* Invoked when an authenticated user decides to create/edit a publication
	* that belongs to him/her.
	* @access public
	*/
	function edit()
	{	
		$view = $this->getView('Publication', 'html', 'JResearchView');
		$pubModel = $this->getModel('Publication', 'JResearchModel');	
		$task = $this->getTask();
		$user = JFactory::getUser();
		$canDoPublications = JResearchAccessHelper::getActions();
		
		if($task == 'edit'){
			$publication = $pubModel->getItem();
			
			if(!empty($publication)){
				if($canDoPublications->get('core.publications.edit') || 
				($canDoPublications->get('core.publications.edit.own') && $publication->created_by == $user->get('id'))
				){				
					$user = JFactory::getUser();
					// Verify if it is checked out
					if($publication->isCheckedOut($user->get('id'))){
						$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
						return;
					}else{
						$publication->checkout($user->get('id'));	
					}
				}else{
					JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));					
					return;
				}
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_ITEM_NOT_FOUND'));
				return;
			}
		}else{
			if(!$canDoPublications->get('core.publications.create')){
				JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
				return;
			}
		}
		
		$view->setLayout('edit');
		$view->setModel($pubModel, true);
		$view->display();
	}

	/**
	* Invoked when the visitant has decided to see the detailed description of
	* a publication.
	* @access public
	*/
	function show(){
		$model = $this->getModel('Publication', 'JResearchModel');
		$view = $this->getView('Publication', 'html', 'JResearchView');
		$view->setModel($model, true);
		$view->display();				
	}

	/**
	 * Invoked when a user cites one or more records from publications database through
	 * an editor.
	 */
	function cite(){
		$output = '';
		// Search the citekey
		$citekeys = JRequest::getVar('citekeys', '');		
		$citekeys = split(',', $citekeys);
		$publicationsArray = array();
		$session = JSession::getInstance(null, null);
		$citedRecords = $session->get('citedRecords', array(), 'com_jresearch');		
		
		foreach($citekeys as $key){
			$pub = JResearchPublicationsHelper::getItemByCitekey(trim($key));
			if($pub != null){
				$publicationsArray[] = $pub;
				if(array_search($key, $citedRecords) === false)
					$citedRecords[] = $key;
			}
		}
		
		$session->set('citedRecords', $citedRecords, 'com_jresearch');
		// Get the object that executes the command		
		$command = JRequest::getVar('command');
		$citeExec = JResearchCite::getInstance();
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
		$document = JFactory::getDocument();
		$document->setMimeEncoding("text/plain");
		echo $output;

	}
	
	/**
	 * Invoked when the user wants to cite a record using the dialog provided via editors-xtd
	 * plugin.
	 */
	function citeFromDialog(){
		// Add explicitly the view path (useful when requesting from the backend)
		$this->addViewPath(JRESEARCH_COMPONENT_SITE.DS.'views');
		$view = $this->getView('Publications', 'html', 'JResearchView');
		$view->setLayout('cite');
		$view->display();
	}
	
	/**
	 * Invoked when the user wants to cite a record using the dialog provided via editors-xtd
	 * plugin.
	 */
	function citeFromForm(){
		// Add explicitly the view path (useful when requesting from the backend)
		$this->addViewPath(JRESEARCH_COMPONENT_SITE.DS.'views');
		$view = $this->getView('Publications', 'html', 'JResearchView');
		$view->setLayout('cite2');
		$view->display();
	}
	
	/**
	 * Invoked when the user wants to generate the bibliography section of a text.
	 *
	 */
	function generateBibliography(){
		// Add explicitly the view path (useful when requesting from the backend)
		$this->addViewPath(JRESEARCH_COMPONENT_SITE.DS.'views');
		$view  = $this->getView('Publications', 'html', 'JResearchView');
		$view->setLayout('generatebibliography');
		$view->display();
	}
	
	/**
	 * Invoked when a user has decided to remove a cited record from the session
	 *
	 */
	function removeCitedRecord(){
		$citekey = JRequest::getVar('citekey', null);
		$document = JFactory::getDocument();
		$document->setMimeEncoding("text/xml");		
		
		$writer = new XMLWriter;
		$writer->openMemory();
		$writer->startDocument('1.0');
		
		if($citekey != null){
			$session = JSession::getInstance(null, null);
			$citedRecords = $session->get('citedRecords', array(), 'com_jresearch');
			$index = array_search($citekey, $citedRecords);
			//Output the result
			if($index !== false){
				unset($citedRecords[$index]);				
				
				$writer->startElement("publications");
				foreach($citedRecords as $key){
					$pub = JResearchPublicationsHelper::getItemByCitekey($key);
					if($pub != null){					
						$writer->startElement('publication');
						$writer->writeElement('key', $key);
						$writer->writeElement('title', $pub->title);
						$writer->endElement();
					}
				}
				$writer->endElement();
				$session->set('citedRecords', $citedRecords, 'com_jresearch');											
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
		$returnedItems = JResearchPublicationsHelper::getItemsByPrefix($key, $criteria, $limitstart, $limit+1);
		
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
		$document = JFactory::getDocument();
		$document->setMimeEncoding("text/plain");
		
		$session = JSession::getInstance(null, null);
		$citekeysArray = $session->get('citedRecords', array(), 'com_jresearch');

		// Get the complete publications
		foreach($citekeysArray as $key){
			$pub = JResearchPublicationsHelper::getItemByCitekey(trim($key));
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
		$document = JFactory::getDocument();
		$document->setMimeEncoding("text/plain");
		
		$session = JSession::getInstance(null, null);
		$session->set('citedRecords', array(), 'com_jresearch');
		echo 'success';
	}

	/**
	* Invoked when the user has decided to save a publication.
	*/	
	function save(){
        $app = JFactory::getApplication();		
        $user = JFactory::getUser();
		if(!JRequest::checkToken()){
           $this->setRedirect('index.php?option=com_jresearch');
           return;
        }
		
		$model = $this->getModel('Publication', 'JResearchModel');
        $task = JRequest::getVar('task');		
        $form = JRequest::getVar('jform', array(), '', 'array');        
		$canDoPubs = JResearchAccessHelper::getActions();
		$canProceed = false;	
		$params = JComponentHelper::getParams('com_jresearch');
                
		// Permissions check
		if(empty($form['id'])){
			$canProceed = $canDoPubs->get('core.publications.create');
			if(!isset($form['published']))
				$form['published'] = $params->get('publications_default_published_status', 1);
			if(!isset($form['internal']))
				$form['internal'] = $params->get('publications_default_internal_status', 1);			
		}else{
			$publication = JResearchPublicationsHelper::getPublication($form['id']);
			
			$canProceed = $canDoPubs->get('core.publications.edit') ||
     			($canDoPubs->get('core.publications.edit.own') && $publication->created_by == $user->get('id'));
		}
        
		if(!$canProceed){
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
			return;
		}

        $app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'publication'));		
        if ($model->save()){
            $publication = $model->getItem();        	
        	$app->triggerEvent('OnAfterSaveJResearchEntity', array($publication, 'publication'));
            if($task == 'save'){
            	$this->setRedirect('index.php?option=com_jresearch&view=publication&layout=new&Itemid='.JRequest::getInt('Itemid', 0), JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
                $app->setUserState('com_jresearch.edit.publication.data', array());
            }elseif($task == 'apply'){
            	$this->setRedirect('index.php?option=com_jresearch&view=publication&layout=edit&id='.$publication->id.'&Itemid='.JRequest::getInt('Itemid', 0), JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
            }
        }else{
            $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
            $type = 'error';
            $app->enqueueMessage($msg, $type);                
            $view = $this->getView('Publication','html', 'JResearchView');
            $view->setLayout('edit');
            $view->setModel($model, true);
            $view->display();
        }
	}
	
	/**
	 * Invoked when pressing cancel button in the form for editing publications.
	 *
	 */
	function cancel(){
		if(!JRequest::checkToken()){
           $this->setRedirect('index.php?option=com_jresearch');
           return;
        }
		
        $model = $this->getModel('Publication', 'JResearchModel');
        $data =& $model->getData();
        $app = JFactory::getApplication();
        
        if(!empty($data['id'])){
	        if(!$model->checkin($data['id'])){
    	        JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
        	}
        }
        
        $app->setUserState('com_jresearch.edit.publication.data', array());
        $Itemid = JRequest::getVar('Itemid', 0);
        $this->setRedirect('index.php?option=com_jresearch&view=publications&layout=new&Itemid='.$Itemid);		
	}
			
	/**
	 * Invoked when the user has decided to change the type of a publication.
	 * It is only applied to existing items.
	 */
	function changeType(){	
	    $app = JFactory::getApplication();		
        $user = JFactory::getUser();
		if(!JRequest::checkToken()){
           $this->setRedirect('index.php?option=com_jresearch');
           return;
        }
		
		$model = $this->getModel('Publication', 'JResearchModel');
        $task = JRequest::getVar('task');		
        $form = JRequest::getVar('jform', array(), '', 'array');        
		$canDoPubs = JResearchAccessHelper::getActions();
		$canProceed = false;	
		$params = JComponentHelper::getParams('com_jresearch');
                
		// Permissions check
		$keepOld = JRequest::getVar('keepold', null);
		if(empty($form['id']) || $keepOld == 'on'){
			$canProceed = $canDoPubs->get('core.publications.create');
			if(!isset($form['published']))
				$form['published'] = $params->get('publications_default_published_status', 1);
			if(!isset($form['internal']))
				$form['internal'] = $params->get('publications_default_internal_status', 1);			
		}else{
			$publication = JResearchPublicationsHelper::getPublication($form['id']);
			
			$canProceed = $canDoPubs->get('core.publications.edit') ||
     			($canDoPubs->get('core.publications.edit.own') && $publication->created_by == $user->get('id'));
		}
        
		if(!$canProceed){
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
			return;
		}

        $app->triggerEvent('OnBeforeSaveJResearchEntity', array($form, 'JResearchPublication'));		
        if ($model->changeType()){
            $publication = $model->getItem();        	
        	$app->triggerEvent('OnAfterSaveJResearchEntity', array($publication, 'JResearchPublication'));
            $this->setRedirect('index.php?option=com_jresearch&view=publication&layout=edit&id='.$publication->id.'&Itemid='.JRequest::getInt('Itemid', 0), JText::_('JRESEARCH_ITEM_SUCCESSFULLY_SAVED'));
        }else{
            $msg = JText::_('JRESEARCH_SAVE_FAILED').': '.implode("<br />", $model->getErrors());
            $type = 'error';
            $app->enqueueMessage($msg, $type);                
            $view = $this->getView('Publication','html', 'JResearchView');
            $view->setLayout('edit');
            $view->setModel($model, true);
            $view->display();
        }		
	}	
	
	/**
	 * Invoked when a user has decided to export a publication from frontend
	 * @return
	 */
	function export(){
		$mainframe = JFactory::getApplication();
        $params = $mainframe->getPageParameters('com_jresearch');
        $exportEnabled = $params->get('enable_export_frontend', 1);
		$strictBibtex = $params->get('enable_strict_bibtex', 1);		    
        $document = JFactory::getDocument();
        $format = JRequest::getVar('format', 'bibtex');

        if($exportEnabled == 1){
            jresearchimport('helpers.exporters.factory', 'jresearch.admin');
            $id = JRequest::getInt('id');
            $model = $this->getModel('Publication', 'JResearchModel');
            $publication = $model->getItem();
  			if($publication == null){
            	$output = JText::_('JRESEARCH_ITEM_NOT_FOUND');
            }elseif($publication->published && $publication->internal){
    			$exportOptions = array();
            	$exportOptions['strict_bibtex'] = ($strictBibtex ==  'yes');                	
            	$exporter = JResearchPublicationExporterFactory::getInstance($format);
                $output = $exporter->parse($publication, $exportOptions);
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
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getPageParameters('com_jresearch');
        $exportEnabled = $params->get('enable_export_frontend', 1);
        $strictBibtex = $params->get('enable_strict_bibtex', 1);
        $document = JFactory::getDocument();
        $format = JRequest::getVar('format', 'bibtex');

        if($exportEnabled == 1){
            jresearchimport('helpers.exporters.factory', 'jresearch.admin');
			$model = $this->getModel('Publications', 'JResearchModel');
            $publicationsArray = $model->getItems();
            $exportOptions['strict_bibtex'] = ($strictBibtex ==  'yes');
            $exporter = JResearchPublicationExporterFactory::getInstance($format);
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
	 * Invoked when a user has imported a set of publications
	 * from frontend
	 * 
	 */
	function executeImport(){
		$canDoPubs = JResearchAccessHelper::getActions();
		if($canDoPubs->get('core.publications.create')){	
			$mainframe = JFactory::getApplication();
	        $params = $mainframe->getPageParameters('com_jresearch');
	        $bibtex = $params->get('enable_bibtex_frontend_import', 0);
	        $researchAreas = JRequest::getVar('researchAreas', array(), '', 'array');
	        if(empty($researchAreas) || in_array('1', $researchAreas)){
				$researchAreasText = '1';
			}else{
				$researchAreasText = implode(',', $researchAreas);
			}	        
	        if($bibtex == 1){
	        	$fileArray = JRequest::getVar('inputfile', null, 'FILES');
	            $format = JRequest::getVar('formats');
	            $texto = JRequest::getVar('bibtex');
	            $uploadedFile = $fileArray['tmp_name'];
	
	            require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'importers'.DS.'factory.php');
	            $importer = JResearchPublicationImporterFactory::getInstance("bibtex");
	
	            if($fileArray == null || $uploadedFile == null){
	            	$n = 0;
	                if($texto != null){
	                	$parsedPublications = $importer->parseText($texto);
	                    foreach($parsedPublications as $p){
	                    	$p->id_research_area = $researchAreasText;
	                    	$p->internal = $params->get('publications_default_internal_status', 1) == 1;
	                    	$p->published = $params->get('publications_default_published_status', 1) == 1;                    	
	                        if(!$p->store()){
	                        	JError::raiseWarning(1, JText::_('PUBLICATION_COULD_NOT_BE_SAVED').': '.$p->getError());
	                        }else{
	                        	$n++;
	                        }
	                    }
	                }
	            }else{
	            	$parsedPublications = $importer->parseFile($uploadedFile);
	                $n = 0;
	                foreach($parsedPublications as $p){
	                	$p->id_research_area = $researchAreasText;
	                    $p->internal = $params->get('publications_default_internal_status', 1) == 1;
	                    $p->published = $params->get('publications_default_published_status', 1) == 1;                    	
	                    if(!$p->store()){
	                    	JError::raiseWarning(1, JText::_('PUBLICATION_COULD_NOT_BE_SAVED').': '.$p->getError());
	                    }else{
	                    	$n++;
	                    }
	                }
	
	             }
	             $mainframe->enqueueMessage(JText::_('JRESEARCH_IMPORTED_ITEMS').': '.$n);
	        }else{
	            JError::raiseWarning(1, JText::_('JRESEARCH_IMPORT_FROM_FRONTEND_NOT_ENABLED'));
	        }
	
	        $this->add();
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}
	
	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
		$canDoPubs = JResearchAccessHelper::getActions();
		if($canDoPubs->get('core.publications.delete')){		
	        $model = $this->getModel('Publication', 'JResearchModel');
	        $n = $model->delete();
	        $Itemid = JRequest::getInt('Itemid', 0);
	        $this->setRedirect('index.php?option=com_jresearch&controller=publications&Itemid='.$Itemid, JText::sprintf('JRESEARCH_ITEM_SUCCESSFULLY_DELETED', $n));
	        $errors = $model->getErrors();
	        if(!empty($errors)){
	        	JError::raiseWarning(1, explode('<br />', $errors));
	        }        
		}else{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));			
		}
	}
}
?>
