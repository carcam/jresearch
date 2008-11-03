<?php
/**
* @version		$Id$
* @package		J!Research
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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of members list in frontend.
 *
 */

class JResearchViewStaff extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        switch($layout){
        	case 'staffflow':
	        	$this->_displayStaffFlow();
	        	break;
	        	
        	default:
       			$this->_displayDefaultList();
       			break;
        }
	
        parent::display($tpl);
    }
    
    /**
    * Display the list of published staff members.
    */
    private function _displayDefaultList(){
      	global $mainframe;
    	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	$members =  $model->getData(null, true, true);   
    	
    	$this->assignRef('params', $params);
    	$this->assignRef('items', $members);
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('page', $model->getPagination());	

    }
    
	/**
	* Display coverflow of published staff members
	* @author Florian Prinz
	*/
    private function _displayStaffFlow()
    {
    	global $mainframe;
    	
    	$params =& JComponentHelper::getParams('com_jresearch');
    	$former = (int) $params->get('former_members');
    	$ordering = (int) $params->get('ordering');
    	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	
    	$model->setFormer($former);
    	
    	//Set ordering
    	$mainframe->setUserState('stafffilter_order',(($ordering == 1) ? 'ordering' : 'lastname'));
    	
    	$members =& $model->getData(null, true, false);

    	$images = $this->getImages($members);
    	
    	$this->assignRef('params', $params);
    	$this->assignRef('images', $images);
    	$this->assignRef('areaModel', $areaModel);
    }
    
    /**
	* Gets images from given members
	* @author Florian Prinz
	*/
    private function getImages(&$members)
    {
    	global $mainframe;
    	
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