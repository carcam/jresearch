<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for the presentation of a
* single publication in frontend.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for presentation of a publication information.
 *
 */

class JResearchViewPublication extends JView
{
    function display($tpl = null)
    {
        $layout = &$this->getLayout();
        switch($layout){
        	case 'default':
        		$this->_displayPublication();
        		break;
        }
	
        parent::display($tpl);
    }
    
    /**
    * Display the information of a publication.
    */
    private function _displayPublication(){
      	global $mainframe;
    	$id = JRequest::getInt('id');
    	$commentsAllowed = false;
   		$showComments = JRequest::getInt('showcomm', 0);
   		$doc =& JFactory::getDocument();
      	JHTML::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'html');   		
   		JHTML::_('Validator._');   
   		$config = array('filePath'=>JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'views'.DS.'publication'.DS.'captcha');   			
   		
   		$doc->addScript(JURI::base().'components/com_jresearch/views/publication/comments.js');
   		
   		

    	if(empty($id)){
    		JError::raiseWarning(1, JText::_('JRESEARCH_INFORMATION_NOT_RETRIEVED'));
    		return;
    	}
    	//Get the model
    	$model =& $this->getModel();
    	$publication = $model->getItem($id);
    	
		if(!$publication->internal || !$publication->published){
			JError::raiseWarning(1, JText::_('JRESEARCH_PUBLICATION_NOT_FOUND'));
			return;
		}		    	
		
    	$areaModel = &$this->getModel('researcharea');
    	$area = $areaModel->getItem($publication->id_research_area);
    	
    	//Get and use configuration
    	$params = $mainframe->getPageParameters('com_jresearch');
		if($params->get('publications_allow_comentaries') == 'yes'){
			$user =& JFactory::getUser();
		 	$from = $params->get('publications_allow_comentaries_from');	
			if($from == 'everyone' || (!$user->guest && $from == 'users')){
				$commentsAllowed = true;
			}
			
			jximport('jxtended.captcha.captcha');
 		 	$captcha = &JXCaptcha::getInstance('image', $config);
 		 	if(!$captcha->initialize())
 		 		JError::raiseWarning(1, 'JRESEARCH_CAPTCHA_NOT_INITIALIZED');
 	
    		if (!is_array($captchaInformation = $captcha->create())) {
	 			JError::raiseWarning(1, 'JRESEARCH_CAPTCHA_NOT_INITIALIZED');
	    	}
	    	
	    	// Get the comments
	    	$limit = JRequest::getVar('limit', 5);
	    	$limitStart = JRequest::getVar('limitstart', 0);
	    	$comments = $model->getComments($publication->id, $limit, $limitStart);			
	    	$total = $model->countComments($publication->id);
	    	$this->assignRef('comments', $comments);
	    	$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitStart);	    	
			$this->assignRef('total', $total);
		}
    	    	
    	
    	// Cross referencing
		$missingFields = $publication->getReferencedFields();
		if(!empty($missingFields)){
			$count = 0;
			$crossrefData = "<tr>";
			foreach($missingFields as $key=>$value){
				if($count % 2 == 0 && $count > 0){
					$crossrefData .= "<tr>";
				}		
				$crossrefData .= "<td class=\"publicationlabel\">".JResearchText::_($key).": </td><td>".trim($value)."</td>";
				$count++;	
				if($count % 2 == 0 && $count > 0){
					$crossrefData .= "</tr>";
				}
		
			} 
			if($count % 2 != 0)
				$crossrefData .= "<td></td><td></td></tr>";
			
			$this->assignRef('reference', $crossrefData);	
		}
		
    	// Bind variables for layout
    	$this->assignRef('params', $params);
    	$this->assignRef('publication', $publication);
    	$this->assignRef('area', $area);
    	$this->assignRef('commentsAllowed', $commentsAllowed);
    	$this->assignRef('showComments', $showComments);
    	$this->assignRef('captcha', $captchaInformation);


    }
}

?>
