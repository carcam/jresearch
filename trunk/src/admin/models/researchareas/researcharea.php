<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	ResearchAreas
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


jresearchimport('joomla.application.component.modelform');


/**
* Model class for holding a single research area record.
*
*/
class JResearchAdminModelResearchArea extends JModelForm{



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
                    $area = null;
                    JError::raiseWarning(1, '1: '.var_export($data, true));
                    if (empty($data))
                    {
                            $selected = & JRequest::getVar('cid', 0, '', 'array');
                            $db = JFactory::getDBO();
                            $query = $db->getQuery(true);
                            // Select all fields from the hello table.
                            $query->select('*');
                            $query->from('#__jresearch_research_area');
                            $query->where('id = ' . (int)$selected[0]);
                            $db->setQuery((string)$query);
                            $data = & $db->loadAssoc();
                            JError::raiseWarning(1, '2: '.var_export($data, true));
                            $area = JTable::getInstance('Researcharea', 'JResearch');
                            $area->bind($data);
                            JError::raiseWarning(1, '3: '.var_export($area, true));

                    }

                    if (empty($data))
                    {
                            // Check the session for previously entered form data.
                            $data = $app->getUserState('com_jresearch.edit.researcharea.data', array());
                            unset($data['id']);
                            $area->bind($data);
                    }
                    // Store the state as an array of values
                    $app->setUserState('com_jresearch.edit.researcharea.data', $data);
                    // and return it as an object
                    if(!empty($area))
                        $this->data = $area;
                    else
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
            $form = $this->loadForm('com_jresearch.researcharea', 'researcharea', array('control' => 'jform', 'load_data' => $loadData));
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
                $data = &$this->getData();
                // Database processing
                $row = &$this->getTable('Researcharea', 'JResearch');
                // Bind the form fields to the hello table
                if (!$row->save($data))
                {
                    $this->setError($row->getError());
                    return false;
                }
                return true;
        }

        /**
         *
         */
        function checkin(){
            $data = &$this->getData();

            if(!empty($data)){
                // Database processing
                $row = &$this->getTable('Researcharea', 'JResearch');
                $row->bind($data);
                if (!$row->checkin())
                {
                    $this->setError($row->getError());
                    return false;
                }
            }

            return true;
        }

	/**
	 * Returns the staff members that work in a specific research 
	 * area.
	 * 
	 * @param int $id_area Research area id
	 *
	 */
	public function getStaffMembers($id_area){
		$members = array();
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_member').'WHERE '.$db->nameQuote('published').' = 1'
				 .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($id_area).' ORDER BY '.$db->nameQuote('ordering').' ASC';

		$db->setQuery($query);
		$result = $db->loadAssocList();

		foreach($result as $r){
			$newMember = JTable::getInstance('Member', 'JResearch');
			$newMember->bind($r);
			$members[] = $newMember;
		}
		
		return $members;
		
	}
	
	
	/**
	 * Returns an array with the n latest publications associated to the
	 * research area.
	 *
	 * @param int $areaId
	 * @param int $n
	 * @return array Array of JResearchPublicationObjects
	 */
	function getLatestPublications($areaId, $n = 0){
		$db =& JFactory::getDBO();
		$latestPub = array();
		
		$idd = $db->nameQuote('id');
		$query = "SELECT $idd FROM ".$db->nameQuote('#__jresearch_publication').' WHERE '.$db->nameQuote('published').' = 1 AND '.$db->nameQuote('internal').' = 1'
				 .' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY year DESC, created DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
		
		$db->setQuery($query);
		$result = $db->loadResultArray();
		foreach($result as $id){
			$publication =& JResearchPublication::getById($id);
			$latestPub[] = $publication;
		}
		
		return $latestPub;
				 
	}
	
	
	/**
	 * Returns the number of publications where the member has participated.
	 * 
	 * @param int $memberId
	 */
	function countPublications($areaId){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_publication').' WHERE '.$db->nameQuote('published').' =  1 AND '.$db->nameQuote('internal').' = 1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId);		
		$db->setQuery($query);		
		return (int)$db->loadResult();
	}

	/**
	 * Returns an array with the n latest projects in which the member has collaborated.
	 * @param int $areaId
	 * @param int $n
	 */
	function getLatestProjects($areaId, $n = 0){
		$db =& JFactory::getDBO();
		$latestProj = array();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_project').' WHERE '.$db->nameQuote('published').' = 1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY start_date DESC, created DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $r){
			$project = new JResearchProject($db);
			$project->bind($r);
			$latestProj[] = $project;
		}
		
		return $latestProj;
		
		
	}
	
		
	/**
	 * Returns the number of projects the member has participated.
	 * @param int $areaId
	 */
	function countProjects($areaId){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_project').' WHERE '.$db->nameQuote('published').' =  1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId);		
		$db->setQuery($query);		
		return (int)$db->loadResult();
		
	}
	
	/**
	 * Returns an array with the n latest theses in which the member has collaborated.
	 * @param int $memberId
	 * @param int $n
	 */
	function getLatestTheses($areaId, $n = 0){
		$db =& JFactory::getDBO();
		$latestThes = array();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_thesis').' WHERE '.$db->nameQuote('published').' = 1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY start_date DESC, created DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $r){
			$thesis = new JResearchThesis($db);
			$thesis->bind($r);
			$latestThes[] = $thesis;
		}
		
		return $latestThes;				
	}
	

		
	/**
	 * Returns the number of degree theses the member has participated.
	 * @param int $areaId
	 */
	function countTheses($areaId){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT count(*) FROM '.$db->nameQuote('#__jresearch_thesis').' WHERE '.$db->nameQuote('published').' =  1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId);		
		$db->setQuery($query);		
		return (int)$db->loadResult();
		
	}

	
	public function getFacilities($areaId, $n=0)
	{
		$db =& JFactory::getDBO();
		$facilities = array();
		
		$query = 'SELECT * FROM '.$db->nameQuote('#__jresearch_facilities').' WHERE '.$db->nameQuote('published').' = 1'
				.' AND '.$db->nameQuote('id_research_area').' = '.$db->Quote($areaId).' ORDER BY name DESC';

		if($n > 0){
			$query .= ' LIMIT 0, '.$n;
		}
				 				 
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $r){
			$item = new JResearchFacility($db);
			$item->bind($r);
			$facilities[] = $item;
		}
		
		return $facilities;	
	}

        	/**
	 * Ordering item
	*/
	function orderItem($item, $movement)
	{
            $row = JTable::getInstance('Researcharea', 'JResearch');
            $row->load($item);

            if (!$row->move($movement))
            {
                $this->setError($row->getError());
                return false;
            }

            return true;
	}

	/**
	 * Set ordering
	*/
	function setOrder($items)
	{
		$total = count($items);
                $row = JTable::getInstance('Researcharea', 'JResearch');

		$order = JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for( $i=0; $i < $total; $i++ )
		{
			$row->load( $items[$i] );

			$groupings[] = $row->former_member;
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store())
				{
					$this->setError($row->getError());
					return false;
				}
			} // if
		} // for

		// execute updateOrder
		$groupings = array_unique($groupings);
		foreach ($groupings as $group)
		{
			$row->reorder(' AND published >= 0');
		}

		return true;
	}
}
?>
