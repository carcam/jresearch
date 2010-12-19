<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'citation_style.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');


/**
* Base class for implementation of Vancouver citation style
*
*/
class JResearchVancouverCitationStyle implements JResearchCitationStyle{
	
	public static $name = 'Vancouver';
	
	/**
	* Takes a publication and returns the string that would be printed when citing the work in a non­
	* parenthetical way.
	* @return 	string
	*/
	function getCitationText($publication){
		return $this->getCitation($publication, false);
	}


	/**
	* Takes a publication and returns the HTML string output that would be printed when citing the work 
	* in a non­parenthetical way.
	* @return 	string
	*/
	function getCitationHTMLText($publication){
		return $this->getCitation($publication, true);
	}
	
	/**
	* Returns the non-parenthetical citation text for a single publication.
	* @param $publication JResearchPublication instance
	* @param $html If true, the text will include HMTL tags for formatting issues like 
	* font styles.
	*
	* @return string
	*/
	
	protected function getCitation($publication, $html=false){
		$session =& JFactory::getSession();
		$citedRecords = $session->get('citedRecords', array(), 'jresearch');
		$n = count($citedRecords);
		
		if($publication instanceof JResearchPublication){
			$key = array_search($publication->citekey, $citedRecords);
			if($key !==  false)
				$result = '('.($key+1).')';
			else
				$result = '('.($n+1).')';

			return $result;
		}else{
			$citations = array();
			foreach($publication as $pub){
				$key = array_search($pub->citekey, $citedRecords);
				if($key !==  false)
					$result = $key+1;
				else
					$result = $n+1;
				
				$citations[] = $result;	
			}		
			return $this->abbreviate($citations, $html);
		}
	}
	
	/**
	 * Takes an array of citation indexes (just an array of integers) and returns the correct
	 * abbreviation text according to Vancouver citation style rules. For example, the following
	 * array [1 2 3 10 12 13 14 15] would output [1-3, 10, 12-15].
	 * @param $indexes Input array
	 *
	 */
	protected function abbreviate($indexes){
		sort($indexes);
		$firstStart = $indexes[0];	
		$start = $indexes[0];
		$end = $indexes[0];
		$output = '(';
				
		for($j=1; $j<=count($indexes); $j++){
			if(1 + $indexes[$j-1] == $indexes[$j])
				$end = $indexes[$j];
			else{
				if($end - $start > 1)
					$fragment = "$start-$end";
				elseif($end - $start == 1)
					$fragment = "$start,$end";
				else
					$fragment = "$start";
				if($start > $firstStart)
					$output .= ",$fragment"; 
				else
					$output .= $fragment;					
				$start = $end = $indexes[$j];
	
	
			}
		}
		
		$output .= ')';
		return $output;
	}
	
	

	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication){
		return $this->getReference($publication);
	}
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication, $authorLinks=false){
		return $this->getReference($publication, true, $authorLinks);
	}
	
	
			
	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param boolean $html Add html tags for formats like italics or bold
	* @param boolean $authorPreviouslyCited If true
	* 
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks=false){		
		$nAuthors = $publication->countAuthors();
		$text = '';
		
		if($nAuthors <= 0){
			$authorsText = '';
		}else{
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		$text .= rtrim($authorsText, '.');		
		
		$title = $html?"<i>".trim($publication->title)."</i>":trim($publication->title);	
		if(!empty($authorsText))
			$text .= '. '.$title;
		else
			$text .= $title;				
				
		$year = trim($publication->year);	
		if($year != null && $year != '0000')		
			$text .= '; '.$year;

		return $text.'.';	
	}
	
	
	
	/**
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way.
	* @return 	string
	*/  
	function getParentheticalCitationText($publication){
		return $this->getCitation($publication);
	}
	

	/** Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way
	* @return 	string
	*/
	function getParentheticalCitationHTMLText($publication){
		return $this->getCitation($publication, true);
	}
	
		
	
	/**
	 * Returns the editors text that is printed in book related references.
	 *
	 * @param JResearchPublication $publication
	 */
	protected function getEditorsReferenceTextFromSinglePublication($publication){
		$editorsArray = $publication->getEditors();
		
		$formattedEditors = array();
		$n = count($editorsArray);

		if($n == 0)
			return '';
		
		$k = 0;	
		foreach($editorsArray as $ed){
			$isFirst = $k==0?true:false;
			$formattedEditors[] = $this->formatAuthorForReferenceOutput($ed, $isFirst);	
			$k++;
		}
		
		$n = count($formattedEditors);
		if($n <= 3){
			if($n == 1)
				$text = $formattedEditors[0];
			else{
				$text = implode(', ', $formattedEditors);
			}
		}else{
			$text = "$formattedEditors[0] et al";
		}	
		
		return $text;		
		
	}
	
	
	/**
	 * Returns an author name with the format used for MLA in the reference list
	 *
	 * @param string $authorName In any of the formats supported by Bibtex.
	 * @param boolean $isFirst If true, the authorName will be formatted according MLA rules
	 * for the first author of a reference.
	 */
	protected function formatAuthorForReferenceOutput($authorName){
		$authorComponents = JResearchPublicationsHelper::bibCharsToUtf8FromArray(JResearchPublicationsHelper::getAuthorComponents($authorName));

		// We have two components: firstname and lastname
		if(count($authorComponents) == 1){
			$text = JString::ucfirst($authorComponents['lastname']);
		}elseif(count($authorComponents) == 2){
			$text = JString::ucfirst($authorComponents['lastname']).' '.JString::rtrim(JResearchPublicationsHelper::getInitials($authorComponents['firstname']), '.'); 
		}elseif(count($authorComponents) == 3){
			$text = JString::ucfirst($authorComponents['von']).' '.JString::ucfirst($authorComponents['lastname']).' '.JString::rtrim(JResearchPublicationsHelper::getInitials($authorComponents['firstname']), '.');
		}else{
			$text = JString::ucfirst($authorComponents['von']).' '.JString::ucfirst($authorComponents['lastname']).' '.JString::rtrim(JResearchPublicationsHelper::getInitials($authorComponents['firstname']), '.').JString::rtrim(JResearchPublicationsHelper::getInitials($authorComponents['jr']), '.');
		}
		
		return $text;
	}
	
	
	/**
	 * Returns the author used when adding the publication in the reference section at the end of
	 * the document.
	 *
	 * @param JResearchPublication $publication
	 */
	protected function getAuthorsReferenceTextFromSinglePublication(JResearchPublication $publication, $authorLinks=false){
		$authors = $publication->getAuthors();
		$formattedAuthors = array();
		
		$k = 0;
		$n = count($authors);
		foreach($authors as $auth){
			$text = $this->formatAuthorForReferenceOutput(($auth instanceof JResearchMember)?$auth->__toString():$auth);
			if($k == $n - 1)
				$text = rtrim($text, '.');
						
			if($authorLinks){
				if($auth instanceof JResearchMember)				
					$text = "<a href=\"index.php?option=com_jresearch&view=member&task=show&id=$auth->id\">$text</a>";
			}
			
			$formattedAuthors[] = $text;					
			$k++;
		}

		$n = count($authors);
		if($n <= 6){
			$text = implode(', ', $formattedAuthors);
		}else{
			$subtotal = array_slice($formattedAuthors, 0, 6);			
			$text = implode(', ', $subtotal).' et al';
		}	

		return $text;		
	}
	
	
	/**
	 * Returns the address text according to MLA rules for reference text.
	 * @param JResearchPublication $publication
	 */
	protected function _getAddressText($publication){
		$address = '';
		$adr = trim($publication->address);
		if(!empty($adr))
			$address = $adr;
		
		$publ = trim($publication->publisher);	
		if(!empty($publ)){
			if(empty($address))
				$address = $publ;
			else
				$address .= ": $publ";	
		}
		
		return $address;
	}
		
	
	/**
	 * Takes an array of JResearchPublication objects and returns the HTML that
	 * would be printed considering those publications were cited (Works Cited section).
	 *
	 * @param array $publicationsArray
	 * @param string $format
	 * @param boolean $authorsLinks
	 */
	function getBibliographyHTMLText($publicationsArray, $authorsLinks = false){
		$entries = array();
		$k = 1;
		
		foreach($publicationsArray as $pub){
			$appStyle =& JResearchCitationStyleFactory::getInstance(self::$name, $pub->pubtype);
			$entries[] = $appStyle->getReferenceHTMLText($pub, $authorsLinks);
			$k++;
		}

		return '<h1>'.JText::_('JRESEARCH_REFERENCES').'</h1><ol><li>'.implode('</li><li>', $entries)."</li></ol>";		
	}
	
	/**
	* Implemented due to parent interface, requirements, it just returns what 
	* it is passed as argument.
	*  
	* @param array Array of publications. 
	* @return array
	*/
	function sort(array $publicationsArray){
		return $publicationsArray;
	}
	
	
}
?>
