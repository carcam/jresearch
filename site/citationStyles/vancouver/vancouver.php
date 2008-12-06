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
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


/**
* Base class for implementation of Vancouver citation style
*
*/
class JResearchVancouverCitationStyle implements JResearchCitationStyle{
	
	/**
	 * The string used to enumerate the last of author of publication with several ones.
	 * & --> For parenthetical citation, JText_('and') for non-parenthetical citation
	 * @var string
	 */
	protected $lastAuthorSeparator;
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
				$result = '['.($key+1).']';
			else
				$result = '['.($n+1).']';

			if($html)
				$result = "<sup>$result</sup>";

			return $result;
		}else{
			$citations = array();
			foreach($publication as $pub){
				$key = array_search($pub->citekey, $citedRecords);
				if($key !==  false)
					$result = '['.($key+1).']';
				else
					$result = '['.($n+1).']';
				
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
	 * @param $html Defines if the output must include HTML tags.
	 *
	 */
	protected function abbreviate($indexes, $html){
		$start = 1;
		$end = 1;
		if($html)
			$output = '<sup>[';
		else
			$output = '[';
				
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
				if($start > 1)
					$output .= ",$fragment"; 
				else
					$output .= $fragment;					
				$start = $end = $indexes[$j];
			}
		}
		
		$output .= ']</sup>';
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
		$this->lastAuthorSeparator = '&';
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		
		$eds = $nEditors > 1? JText::_('Eds.'):JText::_('Ed.');
		
		if(!$publication->__authorPreviouslyCited){
			if($nAuthors <= 0){
				if($nEditors == 0){
					// If neither authors, nor editors
					$authorsText = '';
					$address = '';
					$editorsText = '';
				}else{
					// If no authors, but editors
					$authorsText = $this->getEditorsReferenceTextFromSinglePublication($publication);
					$authorsText .= " ($eds)";
				}
			}else{
				$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
			}
		}else{
			$authorsText = '---';
		}
		
		$title = trim($publication->title);
		$title = $html?"<i>$title</i>":$title;
		
		if(!empty($authorsText))
				$header = "$authorsText. $title";
		else
				$header = "$title";	
		
		if($publication->year != null && $publication->year != '0000')		
			return "$header, $publication->year";
		else
			return $header;	
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
	 * Takes an array of publications and returns the string that would be printed when citing   
	 * the work in a parenthetical way.
	 *
	 * @param array $publicationsArray
	 * @return string Cite text
	 */
	protected function getParentheticalCitationFromPublicationArray($publicationsArray){
		$textArray = array();
		
		// Apply the transformation to every publication
		foreach($publicationsArray as $pub){
			$text  = $this->getAuthorsCitationTextFromSinglePublication($pub);
			$text .= ' {pages}';
			if(!in_array($text, $textArray))
				$textArray[] = $text;
		}		
		
		$result = implode(', ', $textArray);
		return "($result)";
		
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
				$subtotal = array_slice($formattedEditors, 0, $n-1);
				$text = implode(', ', $subtotal)." $this->lastAuthorSeparator ".$formattedEditors[$n-1];
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
	protected function formatAuthorForReferenceOutput($authorName, $isFirst = false){
		$authorComponents = JResearchPublicationsHelper::getAuthorComponents($authorName);

		if($isFirst){
			// We have two components: firstname and lastname
			if(count($authorComponents) == 1){
				$text = ucfirst($authorComponents['lastname']);
			}elseif(count($authorComponents) == 2){
				$text = ucfirst($authorComponents['lastname']).', '.ucfirst($authorComponents['firstname']); 
			}elseif(count($authorComponents) == 3){
				$text = ucfirst($authorComponents['von']).' '.ucfirst($authorComponents['lastname']).', '.ucfirst($authorComponents['firstname']);
			}else{
				$text = ucfirst($authorComponents['von']).' '.ucfirst($authorComponents['lastname']).', '.ucfirst($authorComponents['firstname']).', '.ucfirst($authorComponents['jr']);
			}
		}else{
			// We have two components: firstname and lastname
			if(count($authorComponents) == 1){
				$text = ucfirst($authorComponents['lastname']);
			}elseif(count($authorComponents) == 2){
				$text = ucfirst($authorComponents['firstname']).' '.ucfirst($authorComponents['lastname']); 
			}elseif(count($authorComponents) == 3){
				$text = ucfirst($authorComponents['von']).' '.ucfirst($authorComponents['lastname']).' '.ucfirst($authorComponents['firstname']);
			}else{
				$text = ucfirst($authorComponents['von']).' '.ucfirst($authorComponents['lastname']).' '.ucfirst($authorComponents['firstname']).' '.ucfirst($authorComponents['jr']);
			}
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
		foreach($authors as $auth){
			$isFirst = $k == 0?true:false;
			$text = $this->formatAuthorForReferenceOutput($auth, $isFirst);
			if($authorLinks){
				if($auth instanceof JResearchMember)				
					$text = "<a href=\"index.php?option=com_jresearch&view=member&task=show&id=$auth->id\">$text</a>";
			}
			
			$formattedAuthors[] = $text;					
			$k++;
		}

		$n = count($authors);
		if($n <= 3){
			if($n == 1)
				$text = $formattedAuthors[0];
			else{	
				$subtotal = array_slice($formattedAuthors, 0, $n-1);
				$text = implode(', ', $subtotal)." $this->lastAuthorSeparator ".$formattedAuthors[$n-1];
			}
		}else{
			$text = "$formattedAuthors[0] et al.";
		}	

		return $text;		
	}
	
	
	/**
	* Returns an array of sorted publications according to MLA citation styles rules for generation
	* of "References" section. Publications should be sorted alphabetically by first author lastname or
	* title when authors are absent. Additionally, the method sets a flag (field authorPreviouslyCited=true) 
	* to a publication if the previous record in the array belongs to the same author. That is useful for
	* the method that generates the complete text.
	* 
	* @param array $publicationsArray Array of cited publications
	* @return array Sorted array, according to MLA rules for "Works Cited" section.
	* 
	*/
	protected function sort($publicationsArray){
		$authorsArray = array();
		$result = array();
		$k = 1;
		foreach($publicationsArray as $p){
			$authorsText = $this->getAuthorsCitationTextFromSinglePublication($p); 
			if(isset($authorsArray[$authorsText])){
				$p->__authorPreviouslyCited = true;
				$authorsArray[$authorsText.$k] = $p;
				$k++;
			}else{
				$authorsArray[$authorsText] = $p;	
			}
		}
		// Sort the array
		ksort($authorsArray);		
		return array_values($authorsArray);
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
				$address .= " : $publ";	
		}
		
		return $address;
	}
		
	
	/**
	 * Takes an array of JResearchPublication objects and returns the HTML that
	 * would be printed considering those publications were cited (Works Cited section).
	 *
	 * @param array $publicationsArray
	 */
	function getBibliographyHTMLText($publicationsArray){
		$entries = array();
		
		$sortedArray = $this->sort($publicationsArray);	
		
		foreach($sortedArray as $pub){
			$appStyle =& JResearchCitationStyleFactory::getInstance(self::$name, $pub->pubtype);
			$entries[] = $appStyle->getReferenceHTMLText($pub);
		}
		
		return '<h1>'.JText::_('JRESEARCH_WORKS_CITED').'</h1><ul><li>'.implode('</li><li>', $entries)."</li></ul>";
		
	}
	
	
}
?>