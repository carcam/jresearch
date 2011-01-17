<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport('joomla.application.component.modelform');


/**
* Model class for holding a single publication record.
*
*/
class JResearchAdminModelPublication extends JModelForm{

        /**
         * @var array data
         */
        protected $data = null;

        /**
         * Method to get the data.
         *
         * @access      public
         * @return      array of string
         * @since       1.0
         */
        public function &getData()
        {
            if (empty($this->data))
            {
                    $app = & JFactory::getApplication();
                    $data = & JRequest::getVar('jform');
                    if (empty($data))
                    {
                            $selected = & JRequest::getVar('cid', 0, '', 'array');
                            $db = JFactory::getDBO();
                            $query = $db->getQuery(true);
                            // Select all fields from the hello table.
                            $query->select('*');
                            $query->from('`#__jresearch_publication`');
                            $query->where('id = ' . (int)$selected[0]);
                            $db->setQuery((string)$query);
                            $data = & $db->loadAssoc();
                    }
                    if (empty($data))
                    {
                            // Check the session for previously entered form data.
                            $data = $app->getUserState('com_jresearch.edit.publication.data', array());
                            unset($data['id']);
                    }
                    
                    $app->setUserState('com_jresearch.edit.publication.data', $data);
                    $this->data = $data;
            }
            return $this->data;
        }
        /**
         * Method to get the HelloWorld form.
         *
         * @access      public
         * @return      mixed   JForm object on success, false on failure.
         * @since       1.0
         */
        public function getForm($data = array(), $loadData = true)
        {
            $pubtype = JRequest::getVar('pubtype', 'article');
            $form = $this->loadForm('com_jresearch.'.$pubtype, $pubtype, array('control' => 'jform', 'load_data' => $loadData));
            return $form;
        }
        
        /**
         * Method to save a record
         *
         * @access      public
         * @return      boolean True on success
         */
        function save()
        {
				$app = JFactory::getApplication();
                
                $data =& $this->getData();                
                $row =& $this->getTable('Publication', 'JResearch');

                // Bind the form fields to the hello table
                if (!$row->save($data))
                {
                    $this->setError($row->getError());
                    return false;
                }

                $app->setUserState('com_jresearch.edit.publication.data', $data);

                return true;
        }
        
        /**
         * Publishes the set of selected items
         */
        function publish(){
           $selected =& JRequest::getVar('cid', 0, '', 'array');
           $publication = JTable::getInstance('Publication', 'JResearch');
           return $publication->publish($selected, 1);
        }

        /**
         * Unpublishes the set of selected items
         */
        function unpublish(){
           $selected =& JRequest::getVar('cid', 0, '', 'array');
           $publication = JTable::getInstance('Publication', 'JResearch');
           return $publication->publish($selected, 0);

        }

        /**
         * 
         * Returns the number of removed items based on the 
         * selected items
         */
        function delete(){
           $n = 0;
           $selected =& JRequest::getVar('cid', 0, '', 'array');
           $publication = JTable::getInstance('Publication', 'JResearch');
           foreach($selected as $id){
                $publication->load($id);
	           	if(!$publication->isCheckedOut($user->get('id'))){	
                	if($publication->delete($id)){
            	        $n++;
                	}
	           	}
           }
                                 
           return $n;           
        }
        
	
	/**
	 * Returns an array of JResearchComment objects.
	 *
	 * @param int $id_publication The publication the comments belong to.
	 * @param int $limit How many comments will be returned at most.
	 * @param int $start Start index
	 */
	public function getComments($id_publication, $limit=5, $start=0){
		$db = JFactory::getDBO();
		$comments = array();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_publication_comment').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($id_publication)
				.' ORDER BY datetime DESC LIMIT '.$start.', '.$limit;
				
		$db->setQuery($query);
		$result = $db->loadAssocList();		
		foreach($result as $r){
			$newComm = JTable::getInstance('PublicationComment', 'JResearch');
			$newComm->bind($r);
			$comments[] = $newComm;
		}
		return $comments;
	}
	
	/**
	 * Returns the total number of comments posted for a publication.
	 *
	 * @param int $id_publication
	 * @return int 
	 */
	public function countComments($id_publication){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_publication_comment').' WHERE '.$db->nameQuote('id_publication').' = '.$db->Quote($id_publication);
		$db->setQuery($query);
		return (int)$db->loadResult();
	}
	
}
?>
