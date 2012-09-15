<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* This file declares the factory for citation styles objects.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


/**
* Factory for citation styles objects
*
*/
class JResearchCitationStyleFactory{
	
	/**
	* Takes a citation style and a publication type and returns the appropiate citation style
	* instance.
	* @param $citationStyle Name of the citation style, e.g: APA, MLA, Chicago, CSE. It cannot contain neither whitespaces nor
	* punctuation marks.
	* @param $publicationType Name of the publication type, e.g: Book, PhdThesis, Booklet. The rules for citation styles names
	* are also applied.
	* @return 	JResearchCitationStyle, null if the class could not be found.
	*/
	public static function getInstance($citationStyle, $publicationType=''){
            static $instances;

            if(!$instances){
                $instances = array();
            }
            // We just construct the name of the class based on the standard defined: {CitationStyleName}{Reference type}CitationStyle
            $classname = 'JResearch'.$citationStyle.$publicationType.'CitationStyle';
            $citationStyleFolder = strtolower($citationStyle);
            if(empty($publicationType))
                $filename = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.$citationStyleFolder.DS.strtolower($citationStyle).'.php';
            else{
                $extendedtypes = JResearchPublicationsHelper::getPublicationsSubtypes('extended');
                if(!in_array($publicationType, $extendedtypes))
                    $filename = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.$citationStyleFolder.DS.strtolower($citationStyle.'_'.$publicationType).'.php';
                else
                    $filename = JPATH_PLUGINS.DS.'jresearch-pubtypes'.DS.$publicationType.'_styles'.DS.$citationStyleFolder.DS.strtolower($citationStyle).'_'.$publicationType.'.php'; 

            }
            
            if(!isset($instances[$classname])){
                    if(!class_exists($classname)){
                            if(!file_exists($filename))
                                    return null;

                            require_once($filename);

                            if(class_exists($classname))
                                    $instances[$classname] = new $classname();
                            else
                                    return null;
                    }else{
                            $instances[$classname] = new $classname();
                    }
            }
            return $instances[$classname];
	}


}
?>
