<?php
/**
 * @version		$Id$
* @package		JResearch
* @subpackage	Citation
 * @copyright		Copyright (C) 2008 Luis Galarraga.
 * @license			GNU/GPL
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'citation_style.php');
require_once(JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php');


/**
* Base class for implementation of APA citation style
*
*/
class JResearchAPACitationStyle implements JResearchCitationStyle{
	
	/**
	 * The string used to enumerate the last of author of publication with several ones.
	 * & --> For parenthetical citation, JText_('and') for non-parenthetical citation
	 * @var string
	 */
	protected $lastAuthorSeparator;
	public static $name = 'APA';
	
	/**
	* Takes a publication and returns the string that would be printed when citing the work in a non­
	* parenthetical way. It is used when the author is subject or object in the sentence that includes
	* the cite.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getCitationText($publication){
		return $this->getCitation($publication, false);
	}

	/**
	* Takes a publication and returns the HTML string output that would be printed when citing the work 
	* in a non­parenthetical way.
	* 
	* @param mixed $publication JResearchPublication object or array of them
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
	private function getCitation($publication, $html = false){
		$this->lastAuthorSeparator = JText::_('JRESEARCH_BIBTEXT_AUTHOR_SEP');
		if($publication instanceof JResearchPublication){
			if($publication->countAuthors() == 0){
				if($html)
					return "<i>$publication->title</i> ($publication->year)";
				else				
					return "$publication->title ($publication->year)";
			}
			
			$authorText = $this->getAuthorsCitationTextFromSinglePublication($publication); 
			$year = $publication->year;
		}else{
			// In this case, citation make sense if all the cited records belong to the same authors
			$authorText = $this->getAuthorsCitationTextFromSinglePublication($publication[0]); 
			$authorsIndexedArray = $this->getAuthorsIndexedArray($publication);
			$year = implode(', ', $authorsIndexedArray[$authorText]);
		}
		
		if($year != null && $year != '0000')
			return "$authorText ($year)";
		else
			return $authorText;	
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
	* the work in a parenthetical way. It means, the author is subject nor object in 
	* the sentence containing the cite.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/  
	function getParentheticalCitationText($publication){
		$this->lastAuthorSeparator = '&';
		if($publication instanceof JResearchPublication){
			$text = $this->getParentheticalCitationFromSinglePublication($publication); 
		}else{
			$text = $this->getParentheticalCitationFromPublicationArray($publication);
		}
		return $text;
	}
	
	/** 
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getParentheticalCitationHTMLText($publication){
		return $this->getParentheticalCitationText($publication);
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
		$this->lastAuthorSeparator = $html?'&amp;':'&';
				
		$authorsText = trim($this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks));		
		$title = trim($publication->title);
		$title = $html?"<i>$title</i>":$title;

		$year = trim($publication->year);
		$letter = isset($publication->__yearLetter)?$publication->__yearLetter:'';

		if($year != null && $year != '0000')
			$year = ". ($year$letter)";
		else
			$year = '';	
		
		if(!empty($authorsText)){
			$authorsText = rtrim($authorsText, '.');
			$header = "$authorsText$year. $title.";
		}else
			$header = "$title$year.";	
			
		return $header;	

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
		$text .= $publication->year != '0000' && $publication->year != null? ', '.$publication->year:'';
		return "($text)";
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
			$text .= $formattedAuthors[0].' et al. ';
		}
		return $text;
	}
	
	/**
	 * Returns a sorted associative array where the keys are the authors cite text
	 * of each publication and the value is an array with the years of publication. 
	 * For instance: If receiving the following publications (authors and years are taken just to illustrate):
	 * [Valentin-Macias, Galarraga, 2008
	 * Galarraga, 2007
	 * Moreno, 2008
	 * Galarraga, 2007
	 * Moreno 2009
	 * Galarraga, 2008]
	 * 
	 * The method would return an array like this:
	 * [Galarraga] ---> [2007a, 2007b, 2008]
	 * [Moreno] ---> [2008, 2009]
	 * [Valentin-Macias, Galarraga]--->[2008]
	 *  
	 * @param $publicationsArray 
	 */
	protected function getAuthorsIndexedArray($publicationsArray){
		$authorsArray = array();
		foreach($publicationsArray as $p){
			$authorsText = $this->getAuthorsCitationTextFromSinglePublication($p); 
			if(!isset($authorsArray[$authorsText]))
				$authorsArray[$authorsText] = array();
			if($p->year != null && $p->year != '0000')	
				$authorsArray[$authorsText][] = $p->year;
			else
				$authorsArray[$authorsText][] = '';	
		}
		// Sort the array
		ksort($authorsArray);

		foreach($authorsArray as &$years){
			sort($years, SORT_NUMERIC);

			$n = count($years);
			
			$letter = 'a';
			for($i=0; $i<$n; $i++){
				if(isset($years[$i-1])){
					if($years[$i] == $years[$i-1]){
						if($letter == 'a'){
							$years[$i - 1] .= $letter;
							$letter++;
							$years[$i] .= $letter;
						}else{
							$years[$i] .= $letter;
						}		
						$letter++;	
					}else{
						$letter = 'a';
					}
				}
			}
		}

		return $authorsArray;

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
						$text = "<a href=\"index.php?option=com_jresearch&view=member&id=$auth->id&task=show\">$text</a>";
					}
				}	
			}
			$k++;		
			$formattedAuthors[] = $text;
		}


		if($n <= 6){
			if($n == 0)
				return '';
			elseif($n == 1)
				$text = $formattedAuthors[0];
			else{	
				$subtotal = array_slice($formattedAuthors, 0, $n-1);
				$text = implode(', ', $subtotal)." $this->lastAuthorSeparator ".$formattedAuthors[$n-1];
			}
		}else{
			$subtotal = array_slice($formattedAuthors, 0, 6);
			$text = implode(', ', $subtotal)." et al";
		}	

		return $text;		
		
	}
	
	/**
	 * Takes an array of publications and returns the string that would be printed when citing   
	 * the work in a parenthetical way.
	 *
	 * @param array $publicationsArray
	 * @return string Cite text
	 */
	protected function getParentheticalCitationFromPublicationArray($publicationsArray){
		// This array permits to know if a publication has been considered in the output text
		$authorsArray = $this->getAuthorsIndexedArray($publicationsArray);
		$subtotal = array();
		
		// Time to output
		foreach($authorsArray as $k=>$years){
			$subtotal[] = $k.' '.implode(', ', $years);
		}
		
		return '('.implode('; ', $subtotal).')';
		
	}
	
	/**
	 * Returns an author name with the format used for APA in the reference list
	 *
	 * @param string $authorName In any of the formats supported by Bibtex.
	 */
	protected function formatAuthorForReferenceOutput($authorName){
		$authorComponents = JResearchPublicationsHelper::bibCharsToUtf8FromArray(JResearchPublicationsHelper::getAuthorComponents($authorName));
		// We have two components: firstname and lastname
		if(count($authorComponents) == 1){
			$text = JString::ucfirst($authorComponents['lastname']);
		}elseif(count($authorComponents) == 2){
			$text = JString::ucfirst($authorComponents['lastname']).', '.JResearchPublicationsHelper::getInitials($authorComponents['firstname']); 
		}elseif(count($authorComponents) == 3){
			$text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).', '.JResearchPublicationsHelper::getInitials($authorComponents['firstname']);
		}else{
			$text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).', '.utf8_ucfirst($authorComponents['jr']).', '.JResearchPublicationsHelper::getInitials($authorComponents['firstname']);
		}
		
		return $text;
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
		
		foreach($editorsArray as $ed){
			$formattedEditors[] = $this->formatAuthorForReferenceOutput($ed);	
		}
		
		$n = count($formattedEditors);
		if($n <= 6){
			if($n == 1)
				$text = $formattedEditors[0];
			else{	
				$subtotal = array_slice($formattedEditors, 0, $n-1);
				$text = implode(', ', $subtotal)." $this->lastAuthorSeparator ".$formattedEditors[$n-1];
			}
		}else{
			$subtotal = array_slice($formattedEditors, 0, 6);
			$text = implode(', ', $subtotal)." et al";
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
			$appStyle =& JResearchCitationStyleFactory::getInstance(self::$name, $pub->pubtype);
			$entries[] = $appStyle->getReferenceHTMLText($pub, $authorsLinks);
		}
		
		return '<h1>'.JText::_('JRESEARCH_REFERENCES').'</h1><ul><li>'.implode('</li><li>', $entries)."</li></ul>";
	}
	
	/**
	* Returns an array of sorted publications according to APA citation styles rules for generation
	* of "References" section. Publications should be sorted alphabetically by first author lastname and
	* then by year (when having publications of the same author in different years).
	* 
	*/
	function sort(array $publicationsArray){
		$authorsArray = array();
		$result = array();
		
		foreach($publicationsArray as $p){
			$authorsText = $this->getAuthorsCitationTextFromSinglePublication($p); 
			if(!isset($authorsArray[$authorsText]))
				$authorsArray[$authorsText] = array();
			if(!isset($authorsArray[$authorsText][$p->year]))			
				$authorsArray[$authorsText][$p->year] = array();
			
			$authorsArray[$authorsText][$p->year][] = $p;
				
		}
		
		// Sort the array
		ksort($authorsArray);		
		foreach($authorsArray as &$arr){			
			ksort($arr);
			foreach($arr as $yearArray)
				$letter = 'a';
				$n = count($yearArray);
				foreach($yearArray as $pub){
					if($n > 1){
						$pub->__yearLetter = $letter;
						$letter++;
					}
					$result[] = $pub;
				}
		}
		
		return $result;
	}		
	
	
	/**
	 * Takes a publication and returns the address text according to APA citation style.
	 *
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
	

}
?>