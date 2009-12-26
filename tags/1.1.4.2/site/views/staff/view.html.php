<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Staff
* @copyright	Copyright (C) 2008 Luis Galarraga/Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of presentation of
* staff member list in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('PHP_EOL'))
{
    switch (strtoupper(substr(PHP_OS, 0, 3)))
    {
        // Windows
        case 'WIN':
            define('PHP_EOL', "\r\n");
            break;

        // Mac
        case 'DAR':
            define('PHP_EOL', "\r");
            break;

        // Unix
        default:
            define('PHP_EOL', "\n");
    }
}



/**
 * HTML View class for presentation of members list in frontend.
 *
 */

class JResearchViewStaff extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
        $layout = &$this->getLayout();
        
        $params =& JComponentHelper::getParams('com_jresearch');
    	$former = (int) $params->get('former_members');
    	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	
    	//$model->setFormer($former);
    	JRequest::setVar('filter_former', $former);
    	
        switch($layout){
        	case 'staffflow':
	        	$this->_displayStaffFlow($model);
	        	break;
	        	
        	default:
       			$this->_displayDefaultList($model);
       			break;
        }
        
        $this->assignRef('params', $params);
        $this->assignRef('areaModel', $areaModel);
	
        $eArguments = array('staff', $layout);
		
		$mainframe->triggerEvent('onBeforeListJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListJResearchEntities', $eArguments);
    }
    
    /**
    * Display the list of published staff members.
    */
    private function _displayDefaultList(&$model){
      	$doc = JFactory::getDocument();

      	$members =  $model->getData(null, true, true);   
    	$doc->setTitle(JText::_('JRESEARCH_MEMBERS'));
    	
    	$this->assignRef('items', $members);
    	$this->assignRef('page', $model->getPagination());	

    }
    
	/**
	* Display coverflow of published staff members
	* @author Florian Prinz
	*/
    private function _displayStaffFlow(&$model)
    {
    	$doc = JFactory::getDocument();
    	
		$members =& $model->getData(null, true, false);
    	$images = $this->getImages($members);
    	
		$doc->setTitle(JText::_('JRESEARCH_MEMBERS'));    	
    	$this->assignRef('images', $images);
    }
    
    /**
	* Gets images from given members
	* @author Florian Prinz
	*/
    private function getImages(&$members)
    {
    	
    	$images = array();
    	$i=0;
    	
    	$itemId = JRequest::getVar('Itemid');
    	
    	//Get images
    	foreach($members as $member)
    	{
    		if($member->url_photo != "")
    		{
    			$images[$i]['img'] = str_ireplace(JURI::root(), '', $member->url_photo);
    			$images[$i]['imgalt'] = $member->firstname.' '.$member->lastname;
				$images[$i]['imgtitle'] = 'Image of '.$member->firstname.' '.$member->lastname;
				$images[$i]['hreftitle'] = 'Show me details of '.$member->firstname.' '.$member->lastname;
				$images[$i++]['url'] = JURI::base().'index.php?option=com_jresearch&amp;view=member&amp;task=show&amp;id='.$member->id.(($itemId != "") ? '&amp;Itemid='.$itemId : '');
    		}
    	}
    	
    	//If no images are present, fill with "no image"
    	if (count($images) == 0 )
		{
			$images[0]['img'] = str_ireplace(JURI::root(), '', JURI::base().'components/com_jresearch/assets/qmark.jpg');
			$images[0]['imgalt'] = 'No images found!';
			$images[0]['imgtitle'] = 'No images found!';
			$images[0]['hreftitle'] = 'No images found!';
			$images[0]['url'] = JURI::base().'components/com_jresearch/assets/qmark.jpg';
		}
		
		return $images;
    }
}

?>