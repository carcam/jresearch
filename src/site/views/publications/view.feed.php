<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Publications
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file implements the view which is responsible for generating the RSS feed
* for publications.
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );




/**
 * RSS Feed view class for management of publications lists in JResearch Component frontend
 *
 */

class JResearchViewPublicationsList extends JResearchView{
	
	function display($tpl = null){
		$doc =& JFactory::getDocument();
		$doc->setTitle('JRESEARCH_PUBLICATIONS');
		
		$model =& $this->getModel();
		$areaModel =& $this->getModel('ResearchArea'); 
		$publications = $model->getData(null, true, true);
		
		foreach($publications as $pub){
			$item = new JFeedItem();
			
			$item->author = implode('; ' ,$pub->getAuthors());
			$area = $areaModel->getItem($pub->id_research_area);
			$item->category = $area->name;
			$item->comments = $pub->comments;
			$item->date = $pub->year;
			$item->description = $pub->abstract;
			$item->guid = $pub->id;
			$item->link = JRoute::_('index.php?option=com_jresearch&amp;task=show&amp;view=publication&amp;id='.$pub->id);
			$item->pubDate = $pub->created;
			$item->title = $pub->title;
			$doc->addItem($item);
		}

	}
	
}

?>