<?php
/**
* @version		$Id$
* @package		JResearch
* @subpackage	Citation
* @copyright		Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'citation_style.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');


/**
* Base class for implementation of MLA citation style
*
*/
class JResearchMLACitationStyle implements JResearchCitationStyle{
	
	/**
	 * The string used to enumerate the last of author of publication with several ones.
	 * & --> For parenthetical citation, JText_('and') for non-parenthetical citation
	 * @var string
	 */
	protected $lastAuthorSeparator;
	public static $name = 'MLA';
	
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
		$this->lastAuthorSeparator = JText::_('JRESEARCH_BIBTEXT_AUTHOR_SEP');
		if($publication instanceof JResearchPublication){
			$pub = $publication;
			$text = "({page or volume})";
		}else{
			$pub = $publication[0];
			$text = "({pages or volumes})";
		}
		
		$title = trim($pub->title);
		if($pub->countAuthors() == 0){
			return "\"$title\" $text";
		}else{
			$authorText = $this->getAuthorsCitationTextFromSinglePublication($pub); 
			return "$authorText $text";
		}		
	}
	
	
		
	/**
	 * Takes a single publication and returns part of the cite containing the authors when citing
	 * a record in a parenthetical way.
	 *
	 * @param JResearch $publication
	 * @return string Cite text
	 */
	protected function getAuthorsCitationTextFromSinglePublication(JResearchPublication $publication){
		//Determine the number of authors of the publication
		$text = "";
		$nAuthors = $publication->countAuthors();
		$authors = $publication->getAuthors();
		$formattedAuthors  = array();
				
		foreach($authors as $auth){
			$result = JResearchPublicationsHelper::getAuthorComponents(($auth instanceof JResearchMember)?$auth->__toString():$auth);
			$formattedAuthors[] = (isset($result['von'])?JResearchPublicationsHelper::bibCharsToUtf8FromString($result['von']).' ':'').JResearchPublicationsHelper::bibCharsToUtf8FromString($result['lastname']);		
		}
		
		$text = "";
		if($nAuthors == 0){
			$text = "";
		}elseif($nAuthors == 1){
			$text .= $formattedAuthors[0];
		}elseif($nAuthors == 2){
			$text .= $formattedAuthors[0]." $this->lastAuthorSeparator ".$formattedAuthors[1];
		}elseif($nAuthors >= 3 && $nAuthors <= 5){
			for($i = 0; $i< $nAuthors; $i++){
				$text.= $formattedAuthors[$i];
				if($i == $nAuthors - 2)
					$text .= " $this->lastAuthorSeparator ";
				elseif($i == $nAuthors - 1)
					$text .= '';
				else
					$text .= ', ';		
			} 
		}elseif($nAuthors >= 6){
			$text .= $formattedAuthors[0].' et al ';
		}
		
		return $text;
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
		$this->lastAuthorSeparator = $html?'&amp;':'&';
		$nAuthors = $publication->countAuthors();
		$nEditors = count($publication->getEditors());
		
		$eds = $nEditors > 1? JText::_('JRESEARCH_APA_EDS').'.':JText::_('JRESEARCH_APA_ED').'.';
		
		if(!isset($publication->__authorPreviouslyCited)){
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
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');			
			$header = $authorsText.'. '.$title;
		}else
			$header = $title;	
		
		$year = trim($publication->year);		
		if($year != null && $year != '0000')		
			return "$header, $year.";
		else
			return $header.'.';	
	}
	
	
	
	/**
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way.
	* @return 	string
	*/  
	function getParentheticalCitationText($publication){
		$this->lastAuthorSeparator = $html?'&amp;':'&';
		if($publication instanceof JResearchPublication){
			$text = $this->getParentheticalCitationFromSinglePublication($publication); 
		}else{
			$text = $this->getParentheticalCitationFromPublicationArray($publication);
		}
		return $text;
	}
	
 	/** 
 	 * Takes a single publication and returns the string that would be printed when citing   
	 * the work in a parenthetical way.
	 *
	 * @param JResearch $publication
	 * @return string Cite text
	 */
	protected function getParentheticalCitationFromSinglePublication(JResearchPublication $publication, $html=false){
		$text = $this->getAuthorsCitationTextFromSinglePublication($publication);
		$text .= ' {page}';
		return "($text)";
	}
	

	/** Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way
	* @return 	string
	*/
	function getParentheticalCitationHTMLText($publication){
		return $this->getParentheticalCitationText($publication);
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
			$isFirst = ($k==0);
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
		$authorComponents = JResearchPublicationsHelper::bibCharsToUtf8FromArray(JResearchPublicationsHelper::getAuthorComponents($authorName));

		$firstname = $authorComponents['firstname']; 
			
		$jr = $authorComponents['jr']; 
		if($isFirst){
			// We have two components: firstname and lastname
			if(count($authorComponents) == 1){
				$text = JString::ucfirst($authorComponents['lastname']);
			}elseif(count($authorComponents) == 2){
				$text = JString::ucfirst($authorComponents['lastname']).', '.$firstname; 
			}elseif(count($authorComponents) == 3){
				$text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).', '.$firstname;
			}else{
				$text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).', '.$jr.', '.$firstname;
			}
		}else{
			// We have two components: firstname and lastname
			if(count($authorComponents) == 1){
				$text = JString::ucfirst($authorComponents['lastname']);
			}elseif(count($authorComponents) == 2){
				$text = $firstname.' '.JString::ucfirst($authorComponents['lastname']); 
			}elseif(count($authorComponents) == 3){
				$text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).' '.$firstname;
			}else{
				$text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).', '.$jr.' '.$firstname;
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
		$n = count($authors);
		foreach($authors as $auth){
			$isFirst = $k == 0?true:false;
			$text = $this->formatAuthorForReferenceOutput(($auth instanceof JResearchMember)?$auth->__toString():$auth, $isFirst);
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
		if($n <= 3){
			if($n == 1)
				$text = $formattedAuthors[0];
			elseif($n == 0){	
				$text = '';
			}else{
				$subtotal = array_slice($formattedAuthors, 0, $n-1);
				$text = implode(', ', $subtotal)." $this->lastAuthorSeparator ".$formattedAuthors[$n-1];				
			}
		}else{
			$text = "$formattedAuthors[0] et al";
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
	function sort(array $publicationsArray){
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
		$adr = isset($publication->address)?trim($publication->address):'';
		if(!empty($adr))
			$address = $adr;
		
		$publ = isset($publication->publisher)?trim($publication->publisher):'';	
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
	 * @param boolean $authorsLinks 
	 */
	function getBibliographyHTMLText($publicationsArray, $authorsLinks = false){
		$entries = array();
		
		$sortedArray = $this->sort($publicationsArray);	
		
		foreach($sortedArray as $pub){
			$appStyle =& JResearchCitationStyleFactory::getInstance(self::$name, $pub->pubtype);
			$entries[] = $appStyle->getReferenceHTMLText($pub, $authorsLinks);
		}
		
		return '<h1>'.JText::_('JRESEARCH_WORKS_CITED').'</h1><ul><li>'.implode('</li><li>', $entries)."</li></ul>";
		
	}
	
	
}
?>
