<?php
/**
* @version		$Id$
* @package		J!Research
* @subpackage	Citation
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'citationStyles'.DS.'citation_style.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'publications.php');


/**
* Base class for implementation of IEEE citation style
*
*/
class JResearchIEEECitationStyle implements JResearchCitationStyle{

	public static $name = 'IEEE';
	
	/**
	* Takes a publication and returns the string that would be printed when citing the work in a non­
	* parenthetical way. It is used when the author is subject or object in the sentence that includes
	* the cite.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getCitationText($publication){
		return $this->getCitation($publication);
	}

	/**
	* Takes a publication and returns the HTML string output that would be printed when citing the work 
	* in a non­parenthetical way.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getCitationHTMLText($publication){
		return $this->getCitation($publication);
	}
	
	/**
	* Returns the non-parenthetical citation text for a single publication.
	* @param $publication JResearchPublication instance
	*
	* @return string
	*/
	private function getCitation($publication){
		$session =& JFactory::getSession();
		$citedRecords = $session->get('citedRecords', array(), 'jresearch');
		$n = count($citedRecords);
		
		if($publication instanceof JResearchPublication){
			$key = array_search($publication->citekey, $citedRecords);
			if($key !==  false)
				return '['.($key+1).']';
			else
				return '['.($n+1).']';					
		}else{
			$citations = array();
			foreach($publication as $pub){
				$key = array_search($pub->citekey, $citedRecords);
				if($key !==  false)
					$citations[] = '['.($key+1).']';
				else
					$citations[] = '['.($n+1).']';				
			}		
			return implode(',', $citations);
		}
		
	}
	
		
	/**
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way. It means the author is neither subject nor object in 
	* the sentence that includes the cite.
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/  
	function getParentheticalCitationText($publication){
		return $this->getCitation($publication);
	}
	

	/** 
	* Takes a publication and returns the string that would be printed when citing   
	* the work in a parenthetical way
	* 
	* @param mixed $publication JResearchPublication object or array of them
	* @return 	string
	*/
	function getParentheticalCitationHTMLText($publication){
		return $this->getCitation($publication);		
	}
	

	/**
	* Takes a publication and returns the complete reference text. This is the text used in the Publications 
	* page and in the Works Cited section at the end of a document.
	* 
	* @param JResearchPublication $publication
	* @return 	string
	*/
	function getReferenceText(JResearchPublication $publication){
		$nAuthors = $publication->countAuthors();
		
		if($nAuthors > 0){
			$authorsText = $this->getAuthorsReferenceTextFromSinglePublication($publication, $authorLinks);
		}
		$title = '"'.trim($publication->title).'",';	

		if(!empty($authorsText))
			$header = "$authorsText. $title";
		else
			$header = $title;			
			
		$month = trim($publication->month);
		if(!empty($month))
			$header .= ', '.$month;	
			
		if($publication->year != null && $publication->year != '0000')		
			if(!empty($month))
				$header =  "$header $publication->year";
			else
				$header =  "$header, $publication->year";
	
		return $header;			
		
	}
	
	/**
	* Takes a publication and returns the complete reference text in HTML format.
	* 
	* @param JResearchPublication $publication 
	* @param $authorLinks If true, internal authors names are included as links to their profiles.
	* @return 	string
	*/
	function getReferenceHTMLText(JResearchPublication $publication, $authorLinks = false){
		
	}
			
	/**
	 * Takes an array of JResearchPublication objects and returns the HTML that
	 * would be printed considering those publications were cited.
	 *
	 * @param array $publicationsArray Array of publication sorted according to the moment
	 * they were cited.
	 */
	function getBibliographyHTMLText($publicationsArray){
		$entries = array();
		$k = 1;
		
		foreach($publicationsArray as $pub){
			$appStyle =& JResearchCitationStyleFactory::getInstance(self::$name, $pub->pubtype);
			$entries[] = "[$k] ".$appStyle->getReferenceHTMLText($pub);
			$k++;
		}
		
		return '<h1>'.JText::_('JRESEARCH_REFERENCES').'</h1><ul><li style="list-style:none;">'.implode('</li><li style="list-style:none;"i>', $entries)."</li></ul>";
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
			$text = $formattedEditors[0]." et al";
		}	
		
		return $text;		
		
	}
	
	
	/**
	 * Returns an author name with the format used for IEEE in the reference list
	 *
	 * @param string $authorName In any of the formats supported by Bibtex.
	 */
	protected function formatAuthorForReferenceOutput($authorName){
		$authorComponents = JResearchPublicationsHelper::getAuthorComponents($authorName);

		// We have two components: firstname and lastname
		if(count($authorComponents) == 1){
			$text = ucfirst($authorComponents['lastname']);
		}elseif(count($authorComponents) == 2){
			$text = ucfirst($authorComponents['firstname']{0}).'. '.ucfirst($authorComponents['lastname']); 
		}elseif(count($authorComponents) == 3){
			$text = ucfirst($authorComponents['firstname']{0}).'. '.ucfirst($authorComponents['von']).' '.ucfirst($authorComponents['lastname']);
		}else{
			$text = ucfirst($authorComponents['firstname']{0}).'. '.ucfirst($authorComponents['jr']{0}).'. '.ucfirst($authorComponents['von']).' '.ucfirst($authorComponents['lastname']);
		}
		
		return $text;
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
		
		foreach($authors as $auth){
			$text = $this->formatAuthorForReferenceOutput($auth);			
			
			if($authorsLinks){
				if($auth instanceof JResearchMember){
					if($auth->published){
						$text = "<a href=\"index.php?option=com_jresearch&view=member&id=$auth->id&task=show\">$text</a>";
					}
				}	
			}
					
			$formattedAuthors[] = $text;
		}

		$n = count($authors);
		if($n <= 6){
			if($n == 1)
				$text = $formattedAuthors[0];
			else{	
				$subtotal = array_slice($formattedAuthors, 0, $n-1);
				$text = implode(', ', $subtotal).' '.JText::_('JRESEARCH_BIBTEXT_AUTHOR_SEP').' '.$formattedAuthors[$n-1];
			}
		}else{
			$text = $formattedAuthors[0]." et al.";
		}	

		return $text;		
		
	}
	
		
	/**
	 * Takes a publication and returns the address text according to IEEE citation style.
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
