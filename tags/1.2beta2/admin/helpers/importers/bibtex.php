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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'importers'.DS.'importer.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'includes'.DS.'BibTex.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'publications.php');

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
				if(!empty($type)){
					$newPub = JTable::getInstance('Publication', 'JResearch');
					if($newPub != null){
						$j = 0;
						$newPub->pubtype = $type;						
						if(!empty($data['author'])){
							foreach($data['author'] as $auth){
								if(empty($auth['von']))
									$authorName = $auth['first'].' '.$auth['last'];
								elseif(!empty($auth['jr']))
									$authorName = $auth['von'].' '.$auth['last'].', '.$auth['jr'].', '.$auth['first'];
								else
									$authorName = $auth['von'].' '.$auth['last'].', '.$auth['first'];	
								$newPub->setAuthor(JResearchPublicationsHelper::bibCharsToUtf8FromString($authorName), $j);
								$j++;
							}
						}
						// Normalize the data, bibtex entities are not stored in database
						$newPub->citekey = JResearchPublicationsHelper::bibCharsToUtf8FromString($data['cite']);
						foreach($data as $key=>$info){
							if($key != 'author')
								$data[$key] = JResearchPublicationsHelper::bibCharsToUtf8FromString($info);
						}
						$params = &JComponentHelper::getParams( 'com_jresearch' );
						$mi = $params->get('make_internal');
						
						$newPub->bind($data);
						//Auto make internal when uploading from bibtex file
						if($mi == "yes")
							$newPub->internal = true;
						else
							$newPub->internal = false;

						$newPub->published = true;
						$newPub->created_by = $user->get('id');	
						$newPub->alias = JResearch::alias($newPub->title);
						$newPub->title = JResearchPublicationsHelper::formatBibtexTitleForImport($newPub->title);
						
						$resultArray[] = $newPub;
					}
				}
			}
		}

		return $resultArray;
	}
}


?>