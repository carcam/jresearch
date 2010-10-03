<?php
/**
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
        
        
        $params =& $this->getParams();
    	$former = (int) $params->get('former_members');
    	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
        $positionModel = &$this->getModel('member_position');
		
    	
    	//$model->setFormer($former);
    	JRequest::setVar('filter_former', $former);
    	
        switch($layout){
        	case 'staffflow':
	        	$this->_displayStaffFlow($model);
	        	break;
				
		case 'positions':
	        	$this->_displayPositions($model);
	        	break;
	        	
        	default:
       			$this->_displayDefaultList($model);
       			break;
        }
        
        $this->assignRef('params', $params);
        $this->assignRef('areaModel', $areaModel);
	
        $eArguments = array('staff', $layout);
		
        $mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);

        parent::display($tpl);

        $mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
    
    /**
    * Display the list of published staff members.
    */
    private function _displayDefaultList(&$model){
      	global $mainframe;
      	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'publications.php');
      	
      	$doc = JFactory::getDocument();
      	$params = $mainframe->getPageParameters('com_jresearch');
        
        switch($params->get('staff_filter')){
            case 'only_current':
                JRequest::setVar('filter_former', -1);
                break;
            case 'only_former':
                JRequest::setVar('filter_former', 1);
                break;
            default:
                JRequest::setVar('filter_former', 0);
                break;
        }

      	
      	$members =  $model->getData(null, true, true);   
    	$doc->setTitle(JText::_('JRESEARCH_MEMBERS'));
    	
    	$format = $params->get('staff_format') == 'last_first'?1:0;
    	$this->assignRef('items', $members);
    	$this->assignRef('page', $model->getPagination());	
    	$this->assignRef('format', $format);

    }
    
	/**
	* Display coverflow of published staff members
	* @author Florian Prinz
	*/
    private function _displayStaffFlow(&$model)
    {
    	global $mainframe;
    	jimport('joomla.filesystem.file');
    	
    	//Includes
        require(JPATH_COMPONENT_SITE.DS.'includes'.DS.'reflect2.php');
        require(JPATH_COMPONENT_SITE.DS.'includes'.DS.'reflect3.php');
    	
    	$doc = JFactory::getDocument();
    	
        $members =& $model->getData(null, true, false);
    	$images = $this->_getImages($members);
    	$params = $mainframe->getPageParameters('com_jresearch');
    	$format = $params->get('staff_format') == 'last_first'?1:0;
    	
    	$base = JURI::base();
        $component = $base.'components/com_jresearch/';

        //Paths
        $js_path = $component.'js';
        $css_path = $component.'css';
        $assets = $component.'assets';

        //Add params to the head script declaration
        if ($params->get('imageslider') == '1')
        {
                $scrpt = 'imf.hide_slider = false;'.PHP_EOL;
        }
        else
        {
                $scrpt = 'imf.hide_slider = true;'.PHP_EOL;
        }
        if ($params->get('captions') == '1')
        {
                $scrpt .= 'imf.hide_caption = false;'.PHP_EOL;
        }
        else
        {
                $scrpt .= 'imf.hide_caption = true;'.PHP_EOL;
        }
        if ($params->get('glidetoimage') != '')
        {
                $glidetoimage = $this->get('glidetoimage');
                //	Have they given us a percentage?
                if (JString::substr($glidetoimage, -1) == '%')
                {
                        //	Yes, remove the % sign
                        $glidetoimage = (int) JString::substr($glidetoimage, 0, -1);
                        $glidetoimage = (int) (count($images)) * ($glidetoimage/100);
                        $glidetoimage = (int) ($glidetoimage+.50);
                }
                else
                {
                        $glidetoimage = (int) $glidetoimage;
                }
                if ($glidetoimage > count($images))
                {
                        $glidetoimage = count($images);
                }
                if ($glidetoimage < 1)
                {
                        $glidetoimage = 1;
                }
                $glidetoimage--;
                $scrpt .= 'imf.caption_id = '.$glidetoimage.';'.PHP_EOL;
                $scrpt .= 'imf.current = '.-$glidetoimage.' * imf.xstep;'.PHP_EOL;
        }
        if ($params->get('imagestacksize') != '')
        {
                $imagestacksize = (int) $params->get('imagestacksize');
                if ($imagestacksize > 0 && $imagestacksize < 10)
                {
                        $scrpt .= 'imf.conf_focus = '.$imagestacksize.';'.PHP_EOL;
                }
        }
        if ($params->get('imagethumbnailclass') != '')
        {
                $scrpt .= "imf.conf_thumbnail = '".$params->get('imagethumbnailclass')."';".PHP_EOL;
        }
        if ($params->get('reflectheight') != '')
        {
                $height = JString::str_ireplace('%', '', $params->get('reflectheight'));
                $height = ($height / 100);
                $scrpt .= 'imf.conf_reflection_p = '.$height.';'.PHP_EOL;
        }

        //Get document and add various scripts/stylesheets
        $document =& JFactory::getDocument();

        $document->addScript($js_path.'/imageflow.js');
        $document->addScriptDeclaration($scrpt);

        $document->addStyleSheet($css_path.'/imageflow.css');
		
    	$doc->setTitle(JText::_('JRESEARCH_MEMBERS'));    	
    	$this->assignRef('images', $images);
        $this->assignRef('format', $format);
        $this->assignRef('css_path', $css_path);
        $this->assignRef('js_path', $js_path);
        $this->assignRef('assets_path', $assets);
        $this->assignRef('params', $params);
    }
	
	/**
    * Display the list of published staff members by positions
	* @author Pablo Moncada
    */
    private function _displayPositions(&$model){
      	global $mainframe;
      	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'publications.php');
      	
      	$doc = JFactory::getDocument();
      	$params = $mainframe->getPageParameters('com_jresearch');

      	$members =  $model->getData(null, true, true);
        $positionModelList = $this->getModel('member_positionList');
        $positions = $positionModelList->getData(null, true, true);
    	$doc->setTitle(JText::_('JRESEARCH_MEMBERS'));
    	
    	$format = $params->get('staff_format') == 'last_first'?1:0;
    	$this->assignRef('items', $members);
        $this->assignRef('positions', $positions);
    	$this->assignRef('page', $model->getPagination());	
    	$this->assignRef('format', $format);

    }
    
    /**
	* Gets images from given members
	* @author Florian Prinz
	*/
    private function _getImages(&$members)
    {
    	$images = array();
    	$i=0;
    	
    	$itemId = JRequest::getVar('Itemid');
    	
    	//Get images
    	foreach($members as $member)
    	{
    		if($member->url_photo != "")
    		{
    			$images[$i]['img'] = str_ireplace(JURI::root(), '', JResearch::getUrlByRelative($member->url_photo));
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
			$images[0]['url'] = 'index.php'.(($itemId != "") ? '?Itemid='.$itemId : '');
		}
		
		return $images;
    }
}

?>