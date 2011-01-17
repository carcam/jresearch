<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Facilities
* @copyright	Copyright (C) 2008 Florian Prinz.
* @license		GNU/GPL
* This file implements the view which is responsible for management of presentation of
* facility list in frontend
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/**
 * HTML View class for presentation of facilities list in frontend.
 *
 */

class JResearchViewFacilities extends JResearchView
{
    function display($tpl = null)
    {
    	global $mainframe;
        $layout  = &$this->getLayout();
        
        switch($layout)
        {
       		case 'default':
       			$this->_displayDefaultList();
       			break;
       		case 'facilityflow':
       			$this->_displayFlowList();
       			break;
        }
	
        $eArguments = array('facilities', $layout);
		
		$mainframe->triggerEvent('onBeforeListFrontendJResearchEntities', $eArguments);
		
		parent::display($tpl);
		
		$mainframe->triggerEvent('onAfterListFrontendJResearchEntities', $eArguments);
    }
    
    /**
    * Display the list of published projects.
    */
    private function _displayDefaultList()
    {
      	global $mainframe;
    	$doc = JFactory::getDocument();
      	
    	//Get the model
    	$model =& $this->getModel();
    	$areaModel = &$this->getModel('researcharea');
    	
    	$params = $mainframe->getParams();    
		$facs = array();
		
    	$facs = $model->getData(null, true, true);   
    	
    	$doc->setTitle(JText::_('JRESEARCH_FACILITIES'));
    	$this->assignRef('params', $params);
    	$this->assignRef('items', $facs, JResearchFilter::ARRAY_OBJECT_XHTML_SAFE, array('exclude_keys' => array('description')));
    	$this->assignRef('areaModel', $areaModel);
    	$this->assignRef('page', $model->getPagination());	

    }
    
    private function _displayFlowList()
    {
    	global $mainframe;
    	
    	//Includes
		require(JRESEARCH_COMPONENT_SITE.DS.'includes'.DS.'reflect2.php');
		require(JRESEARCH_COMPONENT_SITE.DS.'includes'.DS.'reflect3.php');
    	
    	//Get document and add various scripts/stylesheets
		$document =& JFactory::getDocument();
    	$component = JURI::base().'components/com_jresearch/';
    	$params = $mainframe->getPageParameters('com_jresearch');
    	
    	//Paths
		$js_path = $component.'js';
		$css_path = $component.'css';
		$assets = $component.'assets';
    	
    	//Get facilities
    	$model =& $this->getModel();
    	$facs = array();
    	
    	$facs = $model->getData(null, true, true);

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
				$glidetoimage = (int) (count($this->images)) * ($glidetoimage/100);
				$glidetoimage = (int) ($glidetoimage+.50);
			}
			else
			{
				$glidetoimage = (int) $glidetoimage;
			}
			if ($glidetoimage > count($this->images))
			{
				$glidetoimage = count($this->images);
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
		
		$document->addScript($js_path.'/imageflow.js');
		$document->addScriptDeclaration($scrpt);
		
		$document->addStyleSheet($css_path.'/imageflow.css');
		
		$images = $this->_getImages($facs);
		
		$this->assignRef('css_path', $css_path);
		$this->assignRef('js_path', $js_path);
		$this->assignRef('assets_path', $assets);
		$this->assignRef('images', $images);
		$this->assignRef('params', $params);
    }
    
    private function _getImages(array $facilities)
    {
    	$images = array();
    	$i=0;
    	
    	$itemId = JRequest::getVar('Itemid');
    	
    	//Get images
    	foreach($facilities as $facility)
    	{
    		if($facility instanceof JResearchFacility && $facility->image_url)
    		{
    			$images[$i]['img'] = str_ireplace(JURI::root(), '', JResearch::getUrlByRelative($facility->image_url));
    			$images[$i]['imgalt'] = $facility->name;
				$images[$i]['imgtitle'] = 'Image of '.$facility->name;
				$images[$i]['hreftitle'] = 'Show me details of '.$facility->name;
				$images[$i++]['url'] = JURI::base().'index.php?option=com_jresearch&amp;view=facility&amp;task=show&amp;id='.$facility->id.(($itemId != "") ? '&amp;Itemid='.$itemId : '');
    		}
    	}
    	
    	//If no images are present, fill with "no image"
    	if (count($images) == 0 )
		{
			$url = JURI::base().'components/com_jresearch/assets/qmark.jpg';
			
			$images[0]['img'] = str_ireplace(JURI::root(), '', $url);
			$images[0]['imgalt'] = 'No images found!';
			$images[0]['imgtitle'] = 'No images found!';
			$images[0]['hreftitle'] = 'No images found!';
			$images[0]['url'] = $url;
		}
		
		return $images;
    }
}

?>