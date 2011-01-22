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
                if (empty($data))
                {
                    // For new items
                    $selected = & JRequest::getVar('cid', 0, '', 'array');
                    $db = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    $query->select('*');
                    $query->from('#__jresearch_research_area');
                    $query->where('id = ' . (int)$selected[0]);
                    $db->setQuery((string)$query);
                    $data = & $db->loadAssoc();
                }

                if (empty($data))
                {
                    // Check the session for previously entered form data.
                    $data = $app->getUserState('com_jresearch.edit.researcharea.data', array());
                    unset($data['id']);
                }

                // Store the state as an array of values
                $app->setUserState('com_jresearch.edit.researcharea.data', $data);
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
                $app = JFactory::getApplication();
                
                $data =& $this->getData();
                
                $row =& $this->getTable('Researcharea', 'JResearch');

                if (!$row->save($data))
                {
                    $this->setError($row->getError());
                    return false;
                }

                $app->setUserState('com_jresearch.edit.researcharea.data', $data);

                return true;
        }

        /**
         * Publishes the set of selected items
         */
        function publish(){
           $selected = & JRequest::getVar('cid', 0, '', 'array');
           $area = JTable::getInstance('Researcharea', 'JResearch');
           return $area->publish($selected, 1);
        }

        /**
         * Unpublishes the set of selected items
         */
        function unpublish(){
           $selected = & JRequest::getVar('cid', 0, '', 'array');
           $area = JTable::getInstance('Researcharea', 'JResearch');
           return $area->publish($selected, 0);

        }

        /**
         * 
         * Returns the number of removed items based on the 
         * selected items
         */
        function delete(){
           $n = 0;
           $selected = & JRequest::getVar('cid', 0, '', 'array');
           $area = JTable::getInstance('Researcharea', 'JResearch');
           $user = JFactory::getUser();
           foreach($selected as $id){
                $area->load($id);
	           	if(!$area->isCheckedOut($user->get('id'))){	
                	if($area->delete($id)){
            	        $n++;
                	}
	           	}
           }
                                 
           return $n;           
        }

        
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
         * Returns the model data store in the user state as a table
         * object
         */
        public function getItem(){
            $row = $this->getTable('Researcharea', 'JResearch');
            $data =& $this->getData();
            $row->bind($data);
            return $row;
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
