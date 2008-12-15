<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the controller for all operations related to the management
* of bibliographical references or publications in the backend interface.
*/

jimport('joomla.application.component.controller');

/**
* Publications Backend Controller
* @package		JResearch
* @subpackage	Publications
*/
class JResearchAdminPublicationsController extends JController
{

	/**
	 * Initialize the controller by registering the tasks to methods.
 	 * @return void
 	 */
	function __construct(){
		parent::__construct();
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch.publications');
		
		// Tasks for edition of publications when the user is authenticated
		$this->registerTask('add', 'add');
		$this->registerTask('edit', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('import', 'import');
		$this->registerTask('export', 'export');
		$this->registerTask('exportAll', 'export');
		$this->registerTask('exportSingle', 'exportSingle');
		$this->registerTask('executeImport', 'executeImport');
		$this->registerTask('executeExport', 'executeExport');
		$this->registerTask('save', 'save');
		$this->registerTask('apply', 'save');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('toggle_internal', 'toggle_internal');
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications');
	}

	/**
	 * Default method, it shows the list of publications for administration. 
	 *
	 * @access public
	 */

	function display(){
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$view = &$this->getView('PublicationsList', 'html', 'JResearchAdminView');
		$pubModel = &$this->getModel('PublicationsList', 'JResearchModel');	
		$model = &$this->getModel('ResearchAreasList', 'JResearchModel');
		$raModel = &$this->getModel('ResearchArea', 'JResearchModel');
		
		$view->setModel($pubModel, true);
		$view->setModel($model);
		$view->setModel($raModel);
		$view->display();
	}

	/**
	* Invoked when an administrator decides to create a publication. Prints
	* a form where the user can select the type of publication to select.
	* @access public
	*/
	function add(){
		$view = &$this->getView('Publication', 'html', 'JResearchAdminView');	
		$view->setLayout('new');
		$view->display();
	}

	/**
	* Invoked when the user wants to edit an existing publication or has selected
	* the type for a new one.
	*/	
	function edit(){
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$cid = JRequest::getVar('cid');
		
		$view = &$this->getView('Publication', 'html', 'JResearchAdminView');
		$pubModel = &$this->getModel('Publication', 'JResearchModel');	
		$model = &$this->getModel('ResearchAreasList', 'JResearchModel');

		if($cid){
			$publication = $pubModel->getItem($cid[0]);
			$user =& JFactory::getUser();
			// Verify if it is checked out
			if($publication->isCheckedOut($user->get('id'))){
				$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_BLOCKED_ITEM_MESSAGE'));
			}else{
				$publication->checkout($user->get('id'));
				$view->setLayout('default');
				$view->setModel($model);
				$view->display();	
			}				
		}else{			
			$view->setLayout('default');
			$view->setModel($model);
			$view->display();
		}
		

	}

	/**
	* Invoked when an administrator has decided to publish a one or more items
	* @access	public
	*/ 
	function publish(){
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		
		$publication = new JResearchPublication($db);
		$publication->publish($cid, 1);
		$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_ITEMS_PUBLISHED_SUCCESSFULLY'));
		
		
	}

	/**
	* Invoked when an administrator has decided to unpublish one or more items
	* @access	public
	*/ 
	function unpublish(){
		// Array of ids
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		
		$publication = new JResearchPublication($db);
		$publication->publish($cid, 0);
		$this->setRedirect('index.php?option=com_jresearch&controller=publications', 'JRESEARCH_ITEMS_UNPUBLISHED_SUCCESSFULLY');
	}

	/**
	* Invoked when an administrator has decided to remove one or more items
	* @access	public
	*/ 
	function remove(){
		$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$n = 0;		
		$publication = new JResearchPublication($db);
		foreach($cid as $id){
			if(!$publication->delete($id))
				JError::raiseWarning(1, JText::sprintf('JRESEARCH_PUBLICATION_NOT_DELETED', $id));
			else
				$n++;	
		}
		$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_SUCCESSFULLY_DELETED', $n));
	}
	
	/**
	* Invoked when an administrator has decided to import publications from a file.
	* @access	public
	*/
	function import(){
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'researchareas');
		$view = &$this->getView('PublicationsList', 'html', 'JResearchAdminView');
		$model = &$this->getModel('ResearchAreasList', 'JResearchModel');
		$view->setLayout('import');
		$view->setModel($model);
		$view->display();
		
	}
	
	/**
	* Invoked when an administrator has decided to export a set of publications.
	* @access	public
	*/
	function export(){
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications');
		$view = &$this->getView('PublicationsList', 'html', 'JResearchAdminView');
		$model = &$this->getModel('PublicationsList', 'JResearchModel');
		$view->setModel($model, true);
		$view->setLayout('export');
		$view->display();
	}

	/**
	* Invoked when the user exports a single publication from backend list.
	*/	
	function exportSingle(){		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications');
		require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'exporters'.DS.'factory.php');
		$document =& JFactory::getDocument(); 

		$id = JRequest::getInt('id');
		$format = JRequest::getVar('format');
		$model = &$this->getModel('Publication', 'JResearchModel');
		$publication = $model->getItem($id);
		
		$exporter =& JResearchPublicationExporterFactory::getInstance($format);
		$output = $exporter->parse($publication);
		$document->setMimeEncoding($exporter->getMimeEncoding());

		if($format == 'bibtex')
			$ext = 'bib';
		else
			$ext = $format;	
			
		$tmpfname = "jresearch_output.$ext";
		header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
		echo $output;

		
	}

	/**
	 * Takes the sent file and imports its contents into the database of
	 * available publications.
	 *
	 */
	function executeImport(){
		//global $mainframe;
		$fileArray = JRequest::getVar('inputfile', null, 'FILES');
		$format = JRequest::getVar('formats');
		$idResearchArea = JRequest::getVar('researchAreas');
		$uploadedFile = $fileArray['tmp_name'];
		//$savedRecords = 0;

		require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'importers'.DS.'factory.php');

		if($fileArray == null || $uploadedFile == null){
			JError::raiseWarning(1, JText::_('JRESEARCH_NO_INPUT_FILE'));
			$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=import');
		}else{
			$importer = &JResearchPublicationImporterFactory::getInstance($format);
			$parsedPublications = $importer->parseFile($uploadedFile);			
			foreach($parsedPublications as $p){
				$p->id_research_area = $idResearchArea;
				if(!$p->store()){					
					JError::raiseWarning(1, JText::_('PUBLICATION_COULD_NOT_BE_SAVED').': '.$p->getError());
				}else{
					$n++;
				}
			}
			$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::sprintf('JRESEARCH_IMPORTED_ITEMS', count($parsedPublications), $n));
		}
		
	}
	
	/**
	 * Triggered when the user clicks the submit button in the export publications
	 * form.
	 *
	 */
	function executeExport(){
		$session = &JFactory::getSession();
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'publications');
		require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'exporters'.DS.'factory.php');
		$markedRecords = $session->get('markedRecords', null, 'jresearch');
		if($markedRecords !== null){
			if($markedRecords === 'all'){
				$model = &$this->getModel('PublicationsList', 'JResearchModel');
				$publicationsArray = $model->getData();
			}else{
				$model = &$this->getModel('Publication', 'JResearchModel');
				$publicationsArray = array();
				foreach($markedRecords as $id){
					$publicationsArray[] = $model->getItem($id);
				}
			}
			
			$format = JRequest::getVar('outformat');
			$exporter =& JResearchPublicationExporterFactory::getInstance($format);
			$output = $exporter->parse($publicationsArray);
			$document =& JFactory::getDocument();
			$document->setMimeEncoding($exporter->getMimeEncoding());
			$session->clear('markedRecords', 'jresearch');

			if($format == 'bibtex')
				$ext = 'bib';			
			else
				$ext = $format;	
			$tmpfname = "jresearch_output.$ext";
			header ("Content-Disposition: attachment; filename=\"$tmpfname\"");
			echo $output;
			
		}
				
		
	}

	/**
	* Invoked when the user has decided to save a publication.
	*/	
	function save(){
		//global $mainframe;
		$db =& JFactory::getDBO();

		// Bind request variables to publication attributes	
		$post = JRequest::get('post');
		$type = JRequest::getVar('pubtype');
		$publication =& JResearchPublication::getSubclassInstance($type);
		$publication->bind($post);
		
		// Validate publication
		if(!$publication->check()){
			JError::raiseWarning(1, $publication->getError());		
			if($publication->id)			
				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype);
			else
				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&pubtype='.$publication->pubtype);	
		}else{
			//Time to set the authors
			$maxAuthors = JRequest::getInt('maxauthors');
			$k = 0;
	
			for($j=0; $j<=$maxAuthors; $j++){
				$value = JRequest::getVar("authors".$j);
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
		
			// Now, save the record
			if($publication->store(true)){			
				
				$task = JRequest::getVar('task');
				if($task == 'apply'){
					$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype, JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED'));
				}elseif($task == 'save'){
					$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_PUBLICATION_SUCCESSFULLY_SAVED'));	
				}
								
			}else{
				JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_SAVED').': '.$db->getErrorMsg());
				$this->setRedirect('index.php?option=com_jresearch&controller=publications&task=edit&cid[]='.$publication->id.'&pubtype='.$publication->pubtype);
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
		
		if($id != null){
			$publication = $model->getItem($id);
			if(!$publication->checkin()){
				JError::raiseWarning(1, JText::_('JRESEARCH_UNLOCK_FAILED'));
			}
		}
		
		$this->setRedirect('index.php?option=com_jresearch&controller=publications');
	}
	
	/**
	 * Invoked when the user has pressed the toggle button for change a publication's 
	 * internal status.
	 *
	 */
	function toggle_internal(){
		//$db =& JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$publication =& JResearchPublication::getById($cid[0]);
		$publication->internal = !$publication->internal;
		if($publication->store())
			$this->setRedirect('index.php?option=com_jresearch&controller=publications', JText::_('JRESEARCH_TOGGLE_INTERNAL_SUCCESSFULLY'));
		else{
			JError::raiseWarning(1, JText::_('JRESEARCH_TOGGLE_INTERNAL_FAILED'));
			$this->setRedirect('index.php?option=com_jresearch&controller=publications');
		}
		
	}

}
?>
