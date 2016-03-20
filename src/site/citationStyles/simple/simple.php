<?php
/**
 * @version		$Id$
*  @package		JResearch
*  @subpackage	Citation
 * @copyright		Copyright (C) 2008 Luis Galarraga.
 * @license			GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jresearchimport('citationStyles.factory', 'jresearch.site');
jresearchimport('citationStyles.citation_style', 'jresearch.site');
jresearchimport('helpers.publications', 'jresearch.admin');


/**
* Base class for implementation of a simple citation style: authors, title, conference/publisher, year.
*
*/
class JResearchSimpleCitationStyle implements JResearchCitationStyle{

    public static $name = 'Simple';
    
    /**
     *  Use the APA citation style for the cases we do not really care.
     */
    private $backupObj; 
    
    function __construct() {
       $this->backupObj = JResearchCitationStyleFactory::getInstance('APA');
    }

    /**
    * Takes a publication and returns the string that would be printed when citing the work in a non­
    * parenthetical way. It is used when the author is subject or object in the sentence that includes
    * the cite.
    * 
    * @param mixed $publication JResearchPublication object or array of them
    * @return 	string
    */
    function getCitationText($publication){
        return $this->backupObj->getCitationText($publication);
    }

    /**
    * Takes a publication and returns the HTML string output that would be printed when citing the work 
    * in a non­parenthetical way.
    * 
    * @param mixed $publication JResearchPublication object or array of them
    * @return 	string
    */
    function getCitationHTMLText($publication){
        return $this->backupObj->getCitationHTMLText($publication);
    }

    /**
    * Returns the non-parenthetical citation text for a single publication.
    * @param $publication JResearchPublication instance
    * @param $html If true, the text will include HMTL tags for formatting issues like 
    * font styles.
    *
    * @return string
    */
    private function getCitation($publication, $html = false){
        return $this->backupObj->getCitation($publication, $html);
    }

    /**
    * Takes a publication and returns the complete reference text. This is the text used in the Publications 
    * page and in the Works Cited section at the end of a document.
    * 
    * @param mixed $publication JResearchPublication object or array of them
    * @return 	string
    */
    function getReferenceText(JResearchPublication $publication){
        return $this->getReference($publication);
    }


    /**
    * Takes a publication and returns the complete reference text in HTML format.
    * 
    * @param JResearchPublication $publication
    * @return 	string
    */
    function getReferenceHTMLText(JResearchPublication $publication, $authorLinks = false){
        return $this->getReference($publication, true, $authorLinks);
    }

    /**
    * Takes a publication and returns the string that would be printed when citing   
    * the work in a parenthetical way. It means, the author is neither subject nor object in 
    * the sentence containing the cite.
    * 
    * @param mixed $publication JResearchPublication object or array of them
    * @return 	string
    */  
    function getParentheticalCitationText($publication){
        return $this->backupObj->getParentheticalCitationText($publication);
    }

    /** 
    * Takes a publication and returns the string that would be printed when citing   
    * the work in a parenthetical way
    * 
    * @param mixed $publication JResearchPublication object or array of them
    * @return 	string
    */
    function getParentheticalCitationHTMLText($publication){
        return $this->backupObj->getParentheticalCitationHTMLText($publication);
    }

    /**
    * Takes a publication and returns the complete reference text. This is the text used in the Publications 
    * page and in the Works Cited section at the end of a document.
    * 
    * @param JResearchPublication $publication
    * @param $html Add html tags for formats like italics or bold
    * @param $authorLinks If true, internal authors names are included as links to their profiles.
    * @return 	string
    */
    protected function getReference(JResearchPublication $publication, $html=false, $authorLinks = false){
        $output = '';
        $authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));		
        $title = trim($publication->title);
        $title = $html? "<b>$title</b>" : $title;
        $journal_url = !empty($publication->journal_url) && $html ?
                $publication->journal_url : null;
        $year = trim($publication->year);
        $letter = isset($publication->__yearLetter)?$publication->__yearLetter:'';

        if($year != null && $year != '0000')
            $year = "$year$letter";
        else
            $year = '';	

        if(!empty($authorsText)){
            $output .= $authorsText.'. ';
        }
        
        $output.= $title.'. ';
        
       $moreDetails = false;
       if (!empty($publication->booktitle)) {
           $booktitle = trim($publication->booktitle);
       } elseif(!empty($publication->publisher)) {
           $booktitle = trim($publication->publisher);
       } elseif (!empty($publication->journal)) {
           $booktitle = trim($publication->journal);           
       } elseif (!empty($publication->institution)) {
           $booktitle = trim($publication->institution);
       }
       
        if (!empty($booktitle)) {
            if (!empty($journal_url)) {
                $booktitle = "<a href=\"$journal_url\" target=\"_blank\">$booktitle</a>";
            }
            $output .= ' '.$booktitle;
            $moreDetails = true;
        }
        
        if ($publication->pubtype == 'mastersthesis') {
            $output .= '. '.JText::_('JRESEARCH_MASTERSTHESIS');
        } else if($publication->pubtype == 'phdthesis') {
            $output .= '. '.JText::_('JRESEARCH_PHDTHESIS');            
        }
               
       
        if (!empty($year)) {
           if ($moreDetails) {
               $output .= ', '.$year.'.';
           } else {
               $output .= ' '.$year.'.';               
           }
        }
       
        return $output;	
    }

    /**
     * Returns the author used when adding the publication in the reference section at the end of
     * the document.
     *
     * @param JResearchPublication $publication
     * @param $authorLinks If true, internal authors names are included as links to their profiles.	 
     **/
    protected function getAuthorsReferenceTextFromSinglePublication(JResearchPublication $publication, $authorsLinks = false){
        $authors = $publication->getAuthors();
        $formattedAuthors = array();

        $k = 0;
        $n = count($authors);		
        foreach($authors as $auth){
            $text = $this->formatAuthorForReferenceOutput(($auth instanceof JResearchMember)?$auth->__toString():$auth);			
            if($k == $n - 1)
                $text = rtrim($text, '.');
            if($authorsLinks){
                if($auth instanceof JResearchMember){
                    if($auth->published){
                        if($auth->link_to_member) {
                            if($auth->link_to_website) {
                                $website = $auth->url_personal_page;
                                $text = "<a href=\"$website\">$text</a>";                            
                            } else {
                                $text = "<a href=\"index.php?option=com_jresearch&view=member&id=$auth->id&task=show\">$text</a>";
                            }
                        }                         
                    }
                }	
            }
            $k++;		
            $formattedAuthors[] = $text;
        }


        if($n == 0) {
            return '';
        } elseif($n == 1) {
            $text = $formattedAuthors[0];
        } else {
            $text = implode(', ', $formattedAuthors);
        }

        return $text;
    }

    /**
     * Returns an author name with the format used for APA in the reference list
     *
     * @param string $authorName In any of the formats supported by Bibtex.
     */
    protected function formatAuthorForReferenceOutput($authorName) {
        $params = JComponentHelper::getParams('com_jresearch');
        $format_last_first = $params->get('staff_format') == 'lastname_firstname';
        
        $authorComponents = JResearchPublicationsHelper::bibCharsToUtf8FromArray(
                JResearchPublicationsHelper::getAuthorComponents($authorName));
        // We have two components: firstname and lastname
        if(count($authorComponents) == 1){
            $text = JString::ucfirst($authorComponents['lastname']);
        }elseif(count($authorComponents) == 2){
            if ($format_last_first) {
                $text = JString::ucfirst($authorComponents['lastname']).', '.$authorComponents['firstname'];
            } else {
                $text = JString::ucfirst($authorComponents['firstname']).' '.$authorComponents['lastname'];                
            }
        }elseif(count($authorComponents) == 3){
            if ($format_last_first) {
                $text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).', '.$authorComponents['firstname'];
            } else {
                $text = $authorComponents['firstname'].' '.$authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']);                
            }
        }else{
            if ($format_last_first) {
                $text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).' '.$authorComponents['firstname'].' '.utf8_ucfirst($authorComponents['jr']);
            } else {
                $text = $authorComponents['firstname'].' '.utf8_ucfirst($authorComponents['jr']).' '.$authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']);
            }
        }

        return $text;
    }

    /**
     * Takes an array of JResearchPublication objects and returns the HTML that
     * would be printed considering those publications were cited.
     *
     * @param array $publicationsArray
     * @param boolean $authorsLinks
     */
    function getBibliographyHTMLText($publicationsArray, $authorsLinks = false){
        $entries = array();

        $sortedArray = $this->sort($publicationsArray);			

        foreach($sortedArray as $pub){
            $entries[] = $this->getReferenceHTMLText($pub, $authorsLinks);
        }

        return '<h1>'.JText::_('JRESEARCH_REFERENCES').'</h1><ul><li>'.implode('</li><li>', $entries)."</li></ul>";
    }

    function sort(array $publicationsArray){
        return $this->backupObj->sort($publicationsArray);
    }
}
?>