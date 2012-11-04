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

jresearchimport('helpers.importers.importer', 'jresearch.admin');
jresearchimport('includes.BibTex', 'jresearch.admin');
jresearchimport('helpers.publications', 'jresearch.admin');
jresearchimport('tables.publication', 'jresearch.admin');
jresearchimport('tables.member', 'jresearch.admin');

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
        $mapToStaff = JRequest::getVar('maptostaff', null);
		if($parser->parse()){
			foreach($parser->data as $data){
				$type = strtolower($data['entryType']);
				if(!empty($type)){
					$newPub = JTable::getInstance('Publication', 'JResearch');
					if($newPub != null){
						$j = -1;
                        $type = strtolower($type);
						$newPub->pubtype = ($type == 'inproceedings') ? 'conference' : $type;
						if(!empty($data['author'])){
                        	foreach($data['author'] as $auth){
                            	$j++;
                                if($mapToStaff == 'on'){
                                	$member = JTable::getInstance('Member', 'JResearch');
                                    //First determine if this author can be mapped to a member in the staff
                                    if($member->bindFromArray(JResearchPublicationsHelper::bibCharsToUtf8FromArray($auth))){
                                    	$newPub->addAuthor($member->id);
                                        continue;
                                    }
                                }
                                
                                if(empty($auth['von']))
                                	$authorName = $auth['first'].' '.$auth['last'];
                                elseif(!empty($auth['jr']))
                                	$authorName = $auth['von'].' '.$auth['last'].', '.$auth['jr'].', '.$auth['first'];
                                else
                                $authorName = $auth['von'].' '.$auth['last'].', '.$auth['first'];
                                $newPub->addAuthor(JResearchPublicationsHelper::bibCharsToUtf8FromString($authorName));                                                            
							}
						}
						// Normalize the data, bibtex entities are not stored in database
						$newPub->citekey = JResearchPublicationsHelper::bibCharsToUtf8FromString($data['cite']);
						foreach($data as $key=>$info){
							if($key != 'author')
								$data[$key] = JResearchPublicationsHelper::bibCharsToUtf8FromString($info);
						}
						$newPub->bind($data);
						$params = &JComponentHelper::getParams( 'com_jresearch' );
						$newPub->created_by = $user->get('id');	
						$newPub->alias = JFilterOutput::stringURLSafe($newPub->title);
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