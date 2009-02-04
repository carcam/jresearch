<?php

/**
* @version		$Id$
* @package		JResearch
* @subpackage	Helpers
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'importers'.DS.'importer.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'includes'.DS.'BibTex.php');

class JResearchBibtexImporter extends JResearchPublicationImporter{
	
	/**
	 * Parse the text sent as parameter in Bibtex format and converts it into 
	 * an array of JResearchPublication objects.
	 *
	 * @param string $text
	 * @param array of JResearchPublication objects
	 */
	public function parse($text){
		$resultArray = array();
		$parser = new Structures_BibTex();
		$parser->content = $text;
		$user = JFactory::getUser();
		if($parser->parse()){
			foreach($parser->data as $data){
				$type = strtolower($data['entryType']);
				$newPub =& JResearchPublication::getSubclassInstance($type);
				if($newPub != null){
					$j = 0;
					if(!empty($data['author'])){
						foreach($data['author'] as $auth){
							if(empty($auth['von']))
								$authorName = $auth['first'].' '.$auth['last'];
							elseif(!empty($auth['jr']))
								$authorName = $auth['von'].' '.$auth['last'].', '.$auth['jr'].', '.$auth['first'];
							else
								$authorName = $auth['von'].' '.$auth['last'].', '.$auth['first'];
							$newPub->setAuthor($authorName, $j);
							$j++;
						}
					}
					$newPub->citekey = $data['cite'];
					$newPub->bind($data);
					$newPub->internal = false;
					$newPub->published = true;
					$newPub->created_by = $user->get('id');	
					
					$resultArray[] = $newPub;
				}
			}
		}

		return $resultArray;
	}
}


?>