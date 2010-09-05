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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'charsets.php');

define('LASTNAME_FIRSTNAME', 1);
define('FIRSTNAME_LASTNAME', 0);

/**
 * This class holds useful methods for dealing with publications records.
 *
 */
class JResearchPublicationsHelper{
	
	const UPPERCASE = 1;
	const LOWERCASE = -1;
	const CASELESS = 0;
	
	private static $months = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');
	
	
	/**
	 * Returns an associative array with the components of an author name according to 
	 * Bibtex definition.
	 * Authors names in Bibtex records may have the following formats:
	 *  - First von Last;
	 *  - von Last, First;
	 *  - von Last, Jr, First.
	 * @param string The complete author name in one of the format exposed above.
	 * @return array Associative array of the components using the keys: firstname, lastname, von and jr 
	 * for the name components. 
	 */
	public static function getAuthorComponents($authorname){
		$result = array();
		
		// Count the number of commas in the authorname
		$nCommas = substr_count($authorname, ',');
		// In this case we are in the first format		
		if($nCommas == 0){
			$words = array_reverse(self::getWordsArray($authorname));
			$n = count($words);
			if($n == 0)
				return $result;
				
			// The first element (last in the original array) belongs to lastname.
			$result['lastname'] = $words[0];
			
			if($n == 1)
				return $result;
			
			$j = 1;
			$m = $n - 1;
			if(self::getBibtexCase($words[$m]) == self::UPPERCASE)
				$result['firstname'] = $words[$m];

			if(isset($result['firstname'])){
				$m--;
				while(self::getBibtexCase($words[$m]) != self::LOWERCASE && $m >= $j){
					$result['firstname'] .= ' '.$words[$m];
					$m--;
				}
			}

			while(self::getBibtexCase($words[$j]) != self::LOWERCASE && $j < $m){
				$result['lastname'] = $words[$j].' '.$result['lastname'];
				$j++;			
			}
			


			// The remaining elements are part of von component
			if($m >= $j){
				$result['von'] = '';
				for($i = $m; $i >= $j; $i--)
					$result['von'] .= ' '.$words[$i];
				$result['von'] = trim($result['von']);	
			}

			
		}elseif($nCommas == 1){
			$components = explode(',', $authorname);
			
			// Everything after the comma is considered as first
			$result['firstname'] = trim($components[1]);
			// The other words go in last and von
			$words = self::getWordsArray(trim($components[0]));

			$n = count($words);
			$result['lastname'] = $words[$n - 1];
			if($n <= 1)
				return $result;
			
			$j = $n - 2;
			while($j >= 0 && self::getBibtexCase($words[$j]) == self::UPPERCASE){
				$result['lastname'] = $words[$j].' '.$result['lastname'];
				$j--;
			}

			for($i = $j; $i >= 0; $i--){
				if(!isset($result['von']))
					$result['von'] = $words[$i];
				else
					$result['von'] = $words[$i].' '.$result['von'];
			}
		}else{
			// In that 2 commas are expected
			$components = explode(',', $authorname);			
		
			$result['firstname'] = trim($components[2]);
			$result['jr'] = trim($components[1]);

			$words = self::getWordsArray(trim($components[0]));			
			$n = count($words);
			$result['lastname'] = $words[$n - 1];

			$j = $n - 2;
			while(self::getBibtexCase($words[$j]) == self::UPPERCASE && $j >= 0){
				$result['lastname'] = $words[$j].' '.$result['lastname'];
				$j--;
			}

			for($i = $j; $i >= 0; $i--){
				if(!isset($result['von']))
					$result['von'] = $words[$i];
				else
					$result['von'] = $words[$i].' '.$result['von'];
			}
			
				
		}

		return $result;
	}
	
	/**
	 * Gets the initials of a given name component.
	 *
	 * @param string $nameComponent
	 */
	public static function getInitials($nameComponent){
		$components = preg_split('/([-\s])/', $nameComponent, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);		
		if(count($components) > 1){
			return JString::ucfirst(JString::substr($components[0], 0, 1)).'.'.$components[1].JString::ucfirst(JString::substr($components[2], 0, 1)).'.';
		}
		$result = JString::ucfirst(JString::substr($nameComponent, 0, 1)).'.'; 
		return $result;
		
	}

	
	/**
	 * Determines if two publications has the same authors.
	 *
	 * @param JResearchPublication $publication1
	 * @param JResearchPublication $publication2
	 * 
	 * @return boolean True if publications have the same authors even if they are
	 * in different order.
	 */
	public static function sameAuthors($publication1, $publication2){
		$authors1 = $publication1->getAuthors();
		$authors2 = $publication2->getAuthors();
		
		if(count($authors1) != count($authors2))
			return false;
		foreach($authors1 as $auth){
			if(!in_array($auth, $authors2))
				return false;
		}
		
		return true;
	}

	/**
	 * Returns the first word of the specified title.
	 *
	 * @param string $title
	 * @return string
	 */

	public static function getFirstWord($title){
		$words =  self::getWordsArray($title);
		return $words[0];
	}
	
	/**
	 * Returns an array with the compounding words of an author name.
	 *
	 * @param string $authorName.
	 */
	public static function getWordsArray($authorName){
		$separators = '/([,\\s;.:])/';
		$words =  preg_split($separators, $authorName, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
		$newWords = array();
		$k = 0;
		for($i=0; $i<count($words); $i++){
			if($words[$i] == '.')
				$newWords[$k - 1] .= $words[$i];
			elseif(preg_match('/^([,\\s;:])$/', $words[$i]) == 0){
				$newWords[] = $words[$i];
				$k++;				
			}	
		}
		
		return $newWords;
	}
	
	/**
	 * Returns the case of the word according to Bibtex rules to determine a word
	 * is uppercase, lowercase or caseless. For a better description, please visit
	 * http://artis.imag.fr/~Xavier.Decoret/resources/xdkbibtex/bibtex_summary.html#splitting_examples
	 * This function works in strings in printable format (non-ASCII characters) as well as with
	 * strings containing Bibtex entities.
	 * 
	 * @param int 1 if the word is considered UPPERCASE, 0 if CASELESS, -1 if LOWERCASE.
	 */
	public static function getBibtexCase($word){
		if(!function_exists('utf8_to_unicode'))
			jimport('phputf8.utils.unicode');


		// Always bring the word to a printable representation.
		$printableWord = self::bibCharsToUtf8FromString($word);
		// Get UTF8 lower characters
		$lowerCharacters = array_keys(JResearchCharsetsHelper::getLowerToUpperCaseTable());
		$upperCharacters = array_values(JResearchCharsetsHelper::getLowerToUpperCaseTable());
		// Now divide the word into several tokens
		$tokens = self::getWordBibtexTokens($printableWord);
		// The first token that is no caseless determines the case of the word
		foreach($tokens as $token){
			// For single characters
			if(JString::strlen($token) == 1){
				if(is_numeric($token))
					return self::LOWERCASE;
				else{
					$utf8Codes = utf8_to_unicode($token);
					foreach($utf8Codes as $c){
						if(array_search($c, $lowerCharacters) !== FALSE)
							return self::LOWERCASE;
						if(array_search($c, $upperCharacters) !== FALSE)
							return self::UPPERCASE;
					}
				}
				//Any other character is considered caseless
			}
			// Balanced braced tokens are considered CASELESS			
		}

		return self::CASELESS;

	}

	/**
	* Returns an array with the compounding tokens of a word. A bibtex
	* token is any single character or sequence of characters surrounded
	* with braces ({}).
	*/
	public static function getWordBibtexTokens($word){
		$codes = JResearchCharsetsHelper::getLatinCharsCodesArray();	
		$codesString = implode($codes, '');		
		preg_match_all("/([-$codesString\w\d]|\{[-$codesString\w\d]+\})/ui", $word, $matches);
		return $matches[0];
	}
	
	
	/**
	 * Encodes all the strings in the array by replacing non-ascii word characters with
	 * their correspoding Bibtex entity.
	 *
	 * @param array $array
	 * @return array
	 */
	public static function utf8ToBibCharsFromArray($array)
	{
	    $keys = array_keys($array);
	    foreach ($keys as $key)
	    {
		$array[$key] = self::utf8ToBibCharsFromString($array[$key]);
	    }
	    return $array;
	}

	/**
	 * Encodes a string by replacing non-ascii word characters with their correspoding 
	 * Bibtex entity.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function utf8ToBibCharsFromString($string)
	{
	    $specialUtf8Chars = JResearchCharsetsHelper::getUtf8CharsArray();
	    $replaceChars        = JResearchCharsetsHelper::getUtf8CharsReplaceArray();

	    $string = preg_replace($specialUtf8Chars, $replaceChars, $string);
	    return $string;
	}

	/**
	 * Converts bibtex special chars to utf8 chars from an array.
	 *
	 * @param array $array
	 * @return array
	 */
	public static function bibCharsToUtf8FromArray($array) {
	    $keys = array_keys($array);
	    foreach ($keys as $key)
	    {
		$array[$key] = self::bibCharsToUtf8FromString($array[$key]);
	    }
	    return $array;
	}

	/**
	 * Converts bibtex to utf8 chars special chars from a string.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function bibCharsToUtf8FromString($string) {
	    //DR: if string contains math, don't convert at all, as it only leads to problems... 
	    if (preg_match("/(^\\$|[^\\\\]\\$)/i", $string) ==1) return $string;
	    if (preg_match("/\\\\ensuremath(\\s)*\\{/i", $string) ==1) return $string;
	    if (preg_match("/\\\\\\(/i", $string) ==1) return $string;
	    if (preg_match("/\\\\begin(\\s)*\\{math\\}/i", $string) ==1) return $string;
	    
	    $specialBibtexChars = JResearchCharsetsHelper::getBibtexCharsArray();
	    $replaceChars        = JResearchCharsetsHelper::getBibtexCharsReplaceArray();

	    $string = preg_replace($specialBibtexChars, $replaceChars, $string);
	    return $string;
	}
	
	/**
	 * Takes Bibtex code for months and translate it into a printable form.
	 *
	 * @return string
	 */
	public static function formatMonth($month, $abbreviate=false){
            $pieces = explode('#', $month);
            $monthsText = implode('|', self::$months);
            $result = '';


            foreach($pieces as $piece){
                    $piece = trim($piece);
                    if(preg_match("/^($monthsText)$/i", $piece)){
                            if($abbreviate)
                                    $content = JText::_('JRESEARCH_ABB_'.strtoupper($piece));
                            else
                                    $content = JText::_('JRESEARCH_'.strtoupper($piece));
                    }elseif(preg_match('/^[{"](.+)[}"]$/', $piece, $matches)){
                            $content = $matches[1];
                    }else{
                            $content = $piece;
                    }
                    $result .= $content.' ';
            }

            return rtrim($result);
	}
	
	/**
	 * Takes an array of authors (strings and JResearchMember objects) and 
	 * format them for output as a list separated by commas. 
	 * @param array $authors
	 * @param string $format (null, LASTNAME_FIRSTNAME or FIRSTNAME_LASTNAME)
	 * @return string
	 */
	public static function formatAuthorsArray($authors, $format = null){
	    if(!class_exists('JResearchMember'))
	      require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'member.php');
	      
	    $text = '';
	    foreach($authors as $author){
		    if($author instanceof JResearchMember){
		    	if($format === null)
		        	$text.= ' '.$author->__toString().',';	
		        else
		        	$text.= ' '.self::formatAuthor($author->__toString(), $format).';';	
		    }else{
		    	if($format === null)
		        	$text.= ' '.$author.',';
		        else
		        	$text .= ' '.self::formatAuthor($author, $format).';'; 	
		    }
	    }
	    $text = JString::rtrim($text, ',;');
	    return $text;
	}
	
	/**
	 * Takes an author (string or JResearchMember object) and 
	 * apply a one of the available formats: "lastname, firstname" or
	 * "firstname lastname"
	 * @param string $author
	 * @return string
	 */
	public static function formatAuthor($author, $format){
		$authorComponents = self::getAuthorComponents($author);
		if($format == LASTNAME_FIRSTNAME){
			$text = (isset($authorComponents['von'])?$authorComponents['von'].' ':'').$authorComponents['lastname'].', '.(isset($authorComponents['firstname'])?' '.$authorComponents['firstname']:'').(isset($authorComponents['jr'])?' '.$authorComponents['jr']:''); 
		}else{
			$text = (isset($authorComponents['firstname'])?$authorComponents['firstname'].' ':'').(isset($authorComponents['jr'])?$authorComponents['jr'].' ':'').(isset($authorComponents['von'])?$authorComponents['von'].' ':'').$authorComponents['lastname'];			
		}

		return $text;
	}
	
	/**
	 * Remove braces {} from Bibtex titles. It assumes bibtex entities for accented 
	 * characters have been parsed into utf8 characters, it is recommended to call this 
	 * function after bibCharsToUtf8FromString function.
	 * 
	 * @param string $title
	 */
	public static function formatBibtexTitleForImport($title){
            $title = str_replace('{', '', $title);
            $title = str_replace('}', '', $title);
            $title = str_replace("\\", '', $title);
            return $title;
	}
	
	/**
	 * Takes a title and embraces with{} all words (except the 1st) containing non-lower case characters, so
	 * external tools interprets the word-case literally. 
	 * @param string $title
	 * @return string
	 */
	public static function formatBibtexTitleForExport($text){
  		$n = JString::strlen($text);
		if($n <= 0) 
			return '';
		
		$previousUpperCase = false;
		$title = JString::ucfirst($text);
		$result = '';
		$openBraces = false;
		for($i = 0; $i < $n; $i++){
			if(ctype_alpha($title{$i})){
				if(ctype_upper($title{$i}) && !$previousUpperCase){
					$result .= '{'.$title{$i};
					$previousUpperCase = true;
					$openBraces = true;
				}elseif(!ctype_upper($title{$i}) && $previousUpperCase){
					$result .= '}'.$title{$i};
					$previousUpperCase = false;					
					$openBraces = false;
				}else{
					$result .= $title{$i};					
				}
			}else{
				if($openBraces){  
					$result .= '}'.$title{$i};
            		$openBraces = false;
        		}else{
					$result .= $title{$i};        
        		}
        		$previousUpperCase = false;        
			}
			
			if($openBraces && $i == $n - 1)
        		$result .= '}';
		}
		
		return $result;		
	}

        /*
         * Takes an issn an 8 digits number and introduces a hash in the middle.
         */
        public static function formatISSN($issn){
            if($issn{4} != '-')
                return substr($issn, 0, 4).'-'.substr($issn, 4, 4);
            else
                return $issn;
        }

}
?>
