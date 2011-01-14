<?php
/**

 * @version			$Id$
 * @package			JResearch
 * @subpackage		Citation	
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
* Base class for implementation of Chicago citation style according to Chicago 
* Manual of Style, 15th edition, http://www.chicagomanualofstyle.org/. 
* The Chicago Manual of Style accepts two citing styles:
* - Author-Date system
* - Notes and Bibliography system 
* For more information, visit http://www.chicagomanualofstyle.org/tools_citationguide.html
* This class implements the first system. 
*/
class JResearchChicagoCitationStyle implements JResearchCitationStyle{
	
	/**
	 * The string used to enumerate the last of author of publication with several ones.
	 * @var string
	 */
	protected $lastAuthorSeparator;
	public static $name = 'Chicago';
	
	/**
	* Takes a publication and returns the string that would be printed when citing the work. Chicago
	* citation style only includes the concept of parenthetical citations, so this method has the same
	* effect as getParentheticalCitationText.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getCitationText($publication){
		return $this->getParentheticalCitation($publication, false);
	}

	/**
	* Takes a publication and returns the HTML string output that would be printed when citing the work 
	* in a non­parenthetical way. Chicago citation style only includes the concept of parenthetical citations, 
	* so this method has the same effect as getParentheticalCitationHTMLText.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getCitationHTMLText($publication){
		return $this->getParentheticalCitationHTMLText($publication, true);
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
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @param $html Add html tags for formats like italics or bold
	* @param $authorLinks If true, internal authors names are included as links to their profiles.
	* @return 	string
	*/
	protected function getReference(JResearchPublication $publication, $html=false, $authorLinks = false){

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
		$this->lastAuthorSeparator = JText::_('JRESEARCH_BIBTEXT_AUTHOR_SEP');
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
	 * Takes a single publication and returns the string that would be printed when citing   
	 * the work in a parenthetical way.
	 *
	 * @param JResearch $publication
	 * @return string Cite text
	 */
	protected function getParentheticalCitationFromSinglePublication(JResearchPublication $publication, $html=false){
		$text = $this->getAuthorsCitationTextFromSinglePublication($publication);
		$text .= $publication->year != '0000' && !empty($publication->year)? ' '.$publication->year:'';
		$text .= !empty($publication->pages)?', '.str_replace('--', '-', trim($publication->pages)):'';
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
		if(count($authors) == 0){
			if(isset($publication->editor))
				$authors = explode(',', trim($publication->editor));
			else
				$authors = array();	
		}
				
		foreach($authors as $auth){
			$result = JResearchPublicationsHelper::getAuthorComponents(($auth instanceof JResearchMember)?$auth->__toString():$auth);
			$formattedAuthors[] = (isset($result['von'])?JResearchPublicationsHelper::bibCharsToUtf8FromString($result['von']).' ':'').JResearchPublicationsHelper::bibCharsToUtf8FromString($result['lastname']);		
		}
		
		$text = "";
		if($nAuthors == 0){
			$text = "";
		}elseif($nAuthors == 1){
			$text .= $formattedAuthors[0];
		}elseif($nAuthors == 2 || $nAuthors == 3){
				$subtotal = array_slice($formattedAuthors, 0, count($formattedAuthors) - 1);
				$text .= implode(', ', $subtotal)." $this->lastAuthorSeparator ".$formattedAuthors[count($formattedAuthors) - 1];
		}elseif($nAuthors > 3){
			$text .= $formattedAuthors[0].' et al ';
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
		ksort(&$authorsArray);

		foreach($authorsArray as &$years){
			sort(&$years, SORT_NUMERIC);

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
		$n = count($authors);
		$i = 0;
		foreach($authors as $auth){
			$text = $this->formatAuthorForReferenceOutput(($auth instanceof JResearchMember)?$auth->__toString():$auth, ($i == $n - 1));			
			if($i == $n -1)
				$text = rtrim($text, '.');
				
			if($authorsLinks){
				if($auth instanceof JResearchMember){
					if($auth->published){
						$text = "<a href=\"index.php?option=com_jresearch&view=member&id=$auth->id&task=show\">$text</a>";
					}
				}	
			}
					
			$formattedAuthors[] = $text;
			$i++;
		}

		$n = count($authors);
		if($n <= 3){
			if($n == 1)
				$text = $formattedAuthors[0];
			else{	
				$text = implode(', ', $formattedAuthors);
			}
		}else{
			$text = $formattedAuthors[0].' '.JText::_('JRESEARCH_CSE_AND_OTHERS');
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
			$subtotal[] = $k.' '.implode(' ', $years);
		}
		
		return '('.implode('; ', $subtotal).')';
		
	}
	
	/**
	 * Returns an author name with the format used for Chicago in the reference list
	 *
	 * @param string $authorName In any of the formats supported by Bibtex.
	 * @param boolean $isLast Chicago citation style formats in a different way the last author.
	 */
	protected function formatAuthorForReferenceOutput($authorName, $isLast = false){
		$authorComponents = JResearchPublicationsHelper::bibCharsToUtf8FromArray(JResearchPublicationsHelper::getAuthorComponents($authorName));
		$text = '';

		// We have two components: firstname and lastname
		if(count($authorComponents) == 1){
			$text = JString::ucfirst($authorComponents['lastname']);
		}elseif(count($authorComponents) == 2){
			if($isLast)
				$text = JString::ucfirst($authorComponents['firstname']).' '.JString::ucfirst($authorComponents['lastname']);			
			else
				$text = JString::ucfirst($authorComponents['lastname']).', '.JString::ucfirst($authorComponents['firstname']); 
		}elseif(count($authorComponents) == 3){
			if($isLast)
				$text = JString::ucfirst($authorComponents['firstname']).' '.JString::ucfirst($authorComponents['von']).' '.JString::ucfirst($authorComponents['lastname']);			
			else
				$text = JString::ucfirst($authorComponents['von']).' '.JString::ucfirst($authorComponents['lastname']).', '.JString::ucfirst($authorComponents['firstname']);			
		}else{
			if($isLast)
				$text = JString::ucfirst($authorComponents['firstname']).' '.$authorComponents['jr'].' '.$authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']);			
			else
				$text = $authorComponents['von'].' '.JString::ucfirst($authorComponents['lastname']).', '.JString::ucfirst($authorComponents['firstname']).' '.$authorComponents['jr'];						
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
		if($n <= 3){
			if($n == 1)
				$text = $formattedEditors[0];
			else{	
				$text = implode(', ', $formattedEditors);
			}
		}else{
			$text = $formattedEditors[0].' '.JText::_('JRESEARCH_CSE_AND_OTHERS');
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
			$entries[] = $appStyle->getReferenceHTMLText($pub, $authorLinks);
		}
		
		return '<h1>'.JText::_('JRESEARCH_REFERENCES').'</h1><ul><li>'.implode('</li><li>', $entries)."</li></ul>";
	}
	
	/**
	* Returns an array of sorted publications according to CSE citation styles rules for generation
	* of "References" section. Publications should be sorted alphabetically by first author lastname and
	* then by year. If there are two or more publications with same author and year, a letter must written
	* next to the year. 
	* 
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
		ksort(&$authorsArray);		
		foreach($authorsArray as &$arr){
			ksort(&$arr);
			foreach($arr as $yearArray){
				if(count($yearArray) > 1){
					$letter = 'a';					
					foreach($yearArray as $pub){
						$pub->__sameAuthorAsBefore = true;
						$pub->__previousLetter = $letter;
						$result[] = $pub;
						$letter++;
					}
				}elseif(count($yearArray) == 1){
					$result[] = $yearArray[0];
				}
			}
		}
		
		return $result;
	}		
	
	
	/**
	 * Takes a publication and returns the address text according to CSE citation style.
	 *
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
	

}
?>
