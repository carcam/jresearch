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

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'language.php');

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
	* UTF-8 Case lookup table
	* This lookuptable defines the lower case letters to their correspponding
	* upper case letter in UTF-8
	* @author Andreas Gohr <andi@splitbrain.org>
	* @see http://dev.splitbrain.org/view/darcs/dokuwiki/inc/utf8.php
	* @see utf8_strtolower
	* @package utf8
	* @subpackage strings
	*/
	private static $UTF8_UPPER_TO_LOWER = array(
	    0x0041=>0x0061, 0x03A6=>0x03C6, 0x0162=>0x0163, 0x00C5=>0x00E5, 0x0042=>0x0062,
	    0x0139=>0x013A, 0x00C1=>0x00E1, 0x0141=>0x0142, 0x038E=>0x03CD, 0x0100=>0x0101,
	    0x0490=>0x0491, 0x0394=>0x03B4, 0x015A=>0x015B, 0x0044=>0x0064, 0x0393=>0x03B3,
	    0x00D4=>0x00F4, 0x042A=>0x044A, 0x0419=>0x0439, 0x0112=>0x0113, 0x041C=>0x043C,
	    0x015E=>0x015F, 0x0143=>0x0144, 0x00CE=>0x00EE, 0x040E=>0x045E, 0x042F=>0x044F,
	    0x039A=>0x03BA, 0x0154=>0x0155, 0x0049=>0x0069, 0x0053=>0x0073, 0x1E1E=>0x1E1F,
	    0x0134=>0x0135, 0x0427=>0x0447, 0x03A0=>0x03C0, 0x0418=>0x0438, 0x00D3=>0x00F3,
	    0x0420=>0x0440, 0x0404=>0x0454, 0x0415=>0x0435, 0x0429=>0x0449, 0x014A=>0x014B,
	    0x0411=>0x0431, 0x0409=>0x0459, 0x1E02=>0x1E03, 0x00D6=>0x00F6, 0x00D9=>0x00F9,
	    0x004E=>0x006E, 0x0401=>0x0451, 0x03A4=>0x03C4, 0x0423=>0x0443, 0x015C=>0x015D,
	    0x0403=>0x0453, 0x03A8=>0x03C8, 0x0158=>0x0159, 0x0047=>0x0067, 0x00C4=>0x00E4,
	    0x0386=>0x03AC, 0x0389=>0x03AE, 0x0166=>0x0167, 0x039E=>0x03BE, 0x0164=>0x0165,
	    0x0116=>0x0117, 0x0108=>0x0109, 0x0056=>0x0076, 0x00DE=>0x00FE, 0x0156=>0x0157,
	    0x00DA=>0x00FA, 0x1E60=>0x1E61, 0x1E82=>0x1E83, 0x00C2=>0x00E2, 0x0118=>0x0119,
	    0x0145=>0x0146, 0x0050=>0x0070, 0x0150=>0x0151, 0x042E=>0x044E, 0x0128=>0x0129,
	    0x03A7=>0x03C7, 0x013D=>0x013E, 0x0422=>0x0442, 0x005A=>0x007A, 0x0428=>0x0448,
	    0x03A1=>0x03C1, 0x1E80=>0x1E81, 0x016C=>0x016D, 0x00D5=>0x00F5, 0x0055=>0x0075,
	    0x0176=>0x0177, 0x00DC=>0x00FC, 0x1E56=>0x1E57, 0x03A3=>0x03C3, 0x041A=>0x043A,
	    0x004D=>0x006D, 0x016A=>0x016B, 0x0170=>0x0171, 0x0424=>0x0444, 0x00CC=>0x00EC,
	    0x0168=>0x0169, 0x039F=>0x03BF, 0x004B=>0x006B, 0x00D2=>0x00F2, 0x00C0=>0x00E0,
	    0x0414=>0x0434, 0x03A9=>0x03C9, 0x1E6A=>0x1E6B, 0x00C3=>0x00E3, 0x042D=>0x044D,
	    0x0416=>0x0436, 0x01A0=>0x01A1, 0x010C=>0x010D, 0x011C=>0x011D, 0x00D0=>0x00F0,
	    0x013B=>0x013C, 0x040F=>0x045F, 0x040A=>0x045A, 0x00C8=>0x00E8, 0x03A5=>0x03C5,
	    0x0046=>0x0066, 0x00DD=>0x00FD, 0x0043=>0x0063, 0x021A=>0x021B, 0x00CA=>0x00EA,
	    0x0399=>0x03B9, 0x0179=>0x017A, 0x00CF=>0x00EF, 0x01AF=>0x01B0, 0x0045=>0x0065,
	    0x039B=>0x03BB, 0x0398=>0x03B8, 0x039C=>0x03BC, 0x040C=>0x045C, 0x041F=>0x043F,
	    0x042C=>0x044C, 0x00DE=>0x00FE, 0x00D0=>0x00F0, 0x1EF2=>0x1EF3, 0x0048=>0x0068,
	    0x00CB=>0x00EB, 0x0110=>0x0111, 0x0413=>0x0433, 0x012E=>0x012F, 0x00C6=>0x00E6,
	    0x0058=>0x0078, 0x0160=>0x0161, 0x016E=>0x016F, 0x0391=>0x03B1, 0x0407=>0x0457,
	    0x0172=>0x0173, 0x0178=>0x00FF, 0x004F=>0x006F, 0x041B=>0x043B, 0x0395=>0x03B5,
	    0x0425=>0x0445, 0x0120=>0x0121, 0x017D=>0x017E, 0x017B=>0x017C, 0x0396=>0x03B6,
	    0x0392=>0x03B2, 0x0388=>0x03AD, 0x1E84=>0x1E85, 0x0174=>0x0175, 0x0051=>0x0071,
	    0x0417=>0x0437, 0x1E0A=>0x1E0B, 0x0147=>0x0148, 0x0104=>0x0105, 0x0408=>0x0458,
	    0x014C=>0x014D, 0x00CD=>0x00ED, 0x0059=>0x0079, 0x010A=>0x010B, 0x038F=>0x03CE,
	    0x0052=>0x0072, 0x0410=>0x0430, 0x0405=>0x0455, 0x0402=>0x0452, 0x0126=>0x0127,
	    0x0136=>0x0137, 0x012A=>0x012B, 0x038A=>0x03AF, 0x042B=>0x044B, 0x004C=>0x006C,
	    0x0397=>0x03B7, 0x0124=>0x0125, 0x0218=>0x0219, 0x00DB=>0x00FB, 0x011E=>0x011F,
	    0x041E=>0x043E, 0x1E40=>0x1E41, 0x039D=>0x03BD, 0x0106=>0x0107, 0x03AB=>0x03CB,
	    0x0426=>0x0446, 0x00DE=>0x00FE, 0x00C7=>0x00E7, 0x03AA=>0x03CA, 0x0421=>0x0441,
	    0x0412=>0x0432, 0x010E=>0x010F, 0x00D8=>0x00F8, 0x0057=>0x0077, 0x011A=>0x011B,
	    0x0054=>0x0074, 0x004A=>0x006A, 0x040B=>0x045B, 0x0406=>0x0456, 0x0102=>0x0103,
	    0x039B=>0x03BB, 0x00D1=>0x00F1, 0x041D=>0x043D, 0x038C=>0x03CC, 0x00C9=>0x00E9,
	    0x00D0=>0x00F0, 0x0407=>0x0457, 0x0122=>0x0123,
	);
	
	/**
	* UTF-8 Case lookup table
	* This lookuptable defines the upper case letters to their correspponding
	* lower case letter in UTF-8
	* @author Andreas Gohr <andi@splitbrain.org>
	* @see utf8_strtoupper
	* @package utf8
	* @subpackage strings
	* @see http://dev.splitbrain.org/view/darcs/dokuwiki/inc/utf8.php
	*/
	private static $UTF8_LOWER_TO_UPPER = array(
	    0x0061=>0x0041, 0x03C6=>0x03A6, 0x0163=>0x0162, 0x00E5=>0x00C5, 0x0062=>0x0042,
	    0x013A=>0x0139, 0x00E1=>0x00C1, 0x0142=>0x0141, 0x03CD=>0x038E, 0x0101=>0x0100,
	    0x0491=>0x0490, 0x03B4=>0x0394, 0x015B=>0x015A, 0x0064=>0x0044, 0x03B3=>0x0393,
	    0x00F4=>0x00D4, 0x044A=>0x042A, 0x0439=>0x0419, 0x0113=>0x0112, 0x043C=>0x041C,
	    0x015F=>0x015E, 0x0144=>0x0143, 0x00EE=>0x00CE, 0x045E=>0x040E, 0x044F=>0x042F,
	    0x03BA=>0x039A, 0x0155=>0x0154, 0x0069=>0x0049, 0x0073=>0x0053, 0x1E1F=>0x1E1E,
	    0x0135=>0x0134, 0x0447=>0x0427, 0x03C0=>0x03A0, 0x0438=>0x0418, 0x00F3=>0x00D3,
	    0x0440=>0x0420, 0x0454=>0x0404, 0x0435=>0x0415, 0x0449=>0x0429, 0x014B=>0x014A,
	    0x0431=>0x0411, 0x0459=>0x0409, 0x1E03=>0x1E02, 0x00F6=>0x00D6, 0x00F9=>0x00D9,
	    0x006E=>0x004E, 0x0451=>0x0401, 0x03C4=>0x03A4, 0x0443=>0x0423, 0x015D=>0x015C,
	    0x0453=>0x0403, 0x03C8=>0x03A8, 0x0159=>0x0158, 0x0067=>0x0047, 0x00E4=>0x00C4,
	    0x03AC=>0x0386, 0x03AE=>0x0389, 0x0167=>0x0166, 0x03BE=>0x039E, 0x0165=>0x0164,
	    0x0117=>0x0116, 0x0109=>0x0108, 0x0076=>0x0056, 0x00FE=>0x00DE, 0x0157=>0x0156,
	    0x00FA=>0x00DA, 0x1E61=>0x1E60, 0x1E83=>0x1E82, 0x00E2=>0x00C2, 0x0119=>0x0118,
	    0x0146=>0x0145, 0x0070=>0x0050, 0x0151=>0x0150, 0x044E=>0x042E, 0x0129=>0x0128,
	    0x03C7=>0x03A7, 0x013E=>0x013D, 0x0442=>0x0422, 0x007A=>0x005A, 0x0448=>0x0428,
	    0x03C1=>0x03A1, 0x1E81=>0x1E80, 0x016D=>0x016C, 0x00F5=>0x00D5, 0x0075=>0x0055,
	    0x0177=>0x0176, 0x00FC=>0x00DC, 0x1E57=>0x1E56, 0x03C3=>0x03A3, 0x043A=>0x041A,
	    0x006D=>0x004D, 0x016B=>0x016A, 0x0171=>0x0170, 0x0444=>0x0424, 0x00EC=>0x00CC,
	    0x0169=>0x0168, 0x03BF=>0x039F, 0x006B=>0x004B, 0x00F2=>0x00D2, 0x00E0=>0x00C0,
	    0x0434=>0x0414, 0x03C9=>0x03A9, 0x1E6B=>0x1E6A, 0x00E3=>0x00C3, 0x044D=>0x042D,
	    0x0436=>0x0416, 0x01A1=>0x01A0, 0x010D=>0x010C, 0x011D=>0x011C, 0x00F0=>0x00D0,
	    0x013C=>0x013B, 0x045F=>0x040F, 0x045A=>0x040A, 0x00E8=>0x00C8, 0x03C5=>0x03A5,
	    0x0066=>0x0046, 0x00FD=>0x00DD, 0x0063=>0x0043, 0x021B=>0x021A, 0x00EA=>0x00CA,
	    0x03B9=>0x0399, 0x017A=>0x0179, 0x00EF=>0x00CF, 0x01B0=>0x01AF, 0x0065=>0x0045,
	    0x03BB=>0x039B, 0x03B8=>0x0398, 0x03BC=>0x039C, 0x045C=>0x040C, 0x043F=>0x041F,
	    0x044C=>0x042C, 0x00FE=>0x00DE, 0x00F0=>0x00D0, 0x1EF3=>0x1EF2, 0x0068=>0x0048,
	    0x00EB=>0x00CB, 0x0111=>0x0110, 0x0433=>0x0413, 0x012F=>0x012E, 0x00E6=>0x00C6,
	    0x0078=>0x0058, 0x0161=>0x0160, 0x016F=>0x016E, 0x03B1=>0x0391, 0x0457=>0x0407,
	    0x0173=>0x0172, 0x00FF=>0x0178, 0x006F=>0x004F, 0x043B=>0x041B, 0x03B5=>0x0395,
	    0x0445=>0x0425, 0x0121=>0x0120, 0x017E=>0x017D, 0x017C=>0x017B, 0x03B6=>0x0396,
	    0x03B2=>0x0392, 0x03AD=>0x0388, 0x1E85=>0x1E84, 0x0175=>0x0174, 0x0071=>0x0051,
	    0x0437=>0x0417, 0x1E0B=>0x1E0A, 0x0148=>0x0147, 0x0105=>0x0104, 0x0458=>0x0408,
	    0x014D=>0x014C, 0x00ED=>0x00CD, 0x0079=>0x0059, 0x010B=>0x010A, 0x03CE=>0x038F,
	    0x0072=>0x0052, 0x0430=>0x0410, 0x0455=>0x0405, 0x0452=>0x0402, 0x0127=>0x0126,
	    0x0137=>0x0136, 0x012B=>0x012A, 0x03AF=>0x038A, 0x044B=>0x042B, 0x006C=>0x004C,
	    0x03B7=>0x0397, 0x0125=>0x0124, 0x0219=>0x0218, 0x00FB=>0x00DB, 0x011F=>0x011E,
	    0x043E=>0x041E, 0x1E41=>0x1E40, 0x03BD=>0x039D, 0x0107=>0x0106, 0x03CB=>0x03AB,
	    0x0446=>0x0426, 0x00FE=>0x00DE, 0x00E7=>0x00C7, 0x03CA=>0x03AA, 0x0441=>0x0421,
	    0x0432=>0x0412, 0x010F=>0x010E, 0x00F8=>0x00D8, 0x0077=>0x0057, 0x011B=>0x011A,
	    0x0074=>0x0054, 0x006A=>0x004A, 0x045B=>0x040B, 0x0456=>0x0406, 0x0103=>0x0102,
	    0x03BB=>0x039B, 0x00F1=>0x00D1, 0x043D=>0x041D, 0x03CC=>0x038C, 0x00E9=>0x00C9,
	    0x00F0=>0x00D0, 0x0457=>0x0407, 0x0123=>0x0122,
	);	
	
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
		$lowerCharacters = array_keys(self::$UTF8_LOWER_TO_UPPER);
		$upperCharacters = array_values(self::$UTF8_LOWER_TO_UPPER);
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
		$codes = self::getLatinCharsCodesArray();	
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
	    $specialUtf8Chars = self::getUtf8CharsArray();
	    $replaceChars        = self::getUtf8CharsReplaceArray();

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
	    
	    $specialBibtexChars = self::getBibtexCharsArray();
	    $replaceChars        = self::getBibtexCharsReplaceArray();

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
			$text = ($authorComponents['von']?$authorComponents['von'].' ':'').$authorComponents['lastname'].', '.($authorComponents['firstname']?' '.$authorComponents['firstname']:'').($authorComponents['jr']?' '.$authorComponents['jr']:''); 
		}else{
			$text = ($authorComponents['firstname']?$authorComponents['firstname'].' ':'').($authorComponents['jr']?$authorComponents['jr'].' ':'').($authorComponents['von']?$authorComponents['von'].' ':'').$authorComponents['lastname'];			
		}

		return $text;
	}

	/**
	 * Returns an array containing patterns that match single non-ascii 
	 * word characters (for latin alphabets)
	 *
	 * @return array.
	 */
	public static function getUtf8CharsArray()
	{
	    return array(
		    "/À/",
		    "/Á/",
		    "/Â/",
		    "/Æ/",
		    "/È/",
		    "/É/",
		    "/Ê/",
		    "/Ì/",
		    "/Í/",
		    "/Î/",
		    "/Ò/",
		    "/Ó/",
		    "/Ô/",
		    "/Ù/",
		    "/Ú/",
		    "/Û/",
		    "/à/",
		    "/á/",
		    "/â/",
		    "/æ/",
		    "/è/",
		    "/é/",
		    "/ê/",
		    "/ì/",
		    "/í/",
		    "/î/",
		    "/ò/",
		    "/ó/",
		    "/ô/",
		    "/ù/",
		    "/ú/",
		    "/û/",
		    "/ä/",
		    "/Ä/",
		    "/ë/",
		    "/Ë/",
		    "/ï/",
		    "/ï/",
		    "/ü/",
		    "/Ü/",
		    "/ö/",
		    "/Ö/",
		    "/ç/",
		    "/Ç/",
		    "/Œ/",
		    "/ÿ/",
		    "/Ÿ/",
		    "/ß/",
		    "/å/",
		    "/Å/",
		    "/ý/",
		    "/Ý/",
		    "/ø/",
		    "/Ø/",
		    "/ñ/",
		    "/Ñ/",
		    "/ã/",
		    "/Ã/",
		    "/õ/",
		    "/Õ/",
		    "/&/" //added because latex thinks & indicates a table, and it occurs often enough in a title
	    );
	}

	/**
	 * Returns an array with the entities that are used by Bibtex to represent non-ascii
	 * word characters.
	 *
	 * @return array
	 */
	public static function getUtf8CharsReplaceArray()
	{
	    return array(
		    "{\\`A}",
		    "{\\'A}",
		    "{\\^A}",
		    "{\\AE}",
		    "{\\`E}",
		    "{\\'E}",
		    "{\\^E}",
		    "{\\`I}",
		    "{\\'I}",
		    "{\\^I}",
		    "{\\`O}",
		    "{\\'O}",
		    "{\\^O}",
		    "{\\`U}",
		    "{\\'U}",
		    "{\\^U}",
		    "{\\`a}",
		    "{\\'a}",
		    "{\\^a}",
		    "{\\ae}",
		    "{\\`e}",
		    "{\\'e}",
		    "{\\^e}",
		    "{\\`i}",
		    "{\\'i}",
		    "{\\^i}",
		    "{\\`o}",
		    "{\\'o}",
		    "{\\^o}",
		    "{\\`u}",
		    "{\\'u}",
		    "{\\^u}",
		    "{\\\"a}",
		    "{\\\"A}",
		    "{\\\"e}",
		    "{\\\"E}",
		    "{\\\"i}",
		    "{\\\"I}",
		    "{\\\"u}",
		    "{\\\"U}",
		    "{\\\"o}",
		    "{\\\"O}",
		    "{\\c{c}}",
		    "{\\c{C}}",
		    "{\\OE}",
		    "{\\\"y}",
		    "{\\\"Y}",
		    "{\\ss}",
		    "{\\aa}",
		    "{\\AA}",
		    "{\\'y}",
		    "{\\'Y}",
		    "{\\o}", 
		    "{\\O}",
		    "{\\~n}",
		    "{\\~N}",
		    "{\\~a}",
		    "{\\~A}",
		    "{\\~o}",
		    "{\\~O}",
		    "{\&}"
	    );
	}

	/**
	 * Returns an array with all the patterns that match Bibtex entities used to
	 * represent non-ascii word characters (latin alphabets).
	 *
	 * @return unknown
	 */
	function getBibtexCharsArray()
	{
	    return array(
		    "/{(\\\`([aeiou]|{[aeiou]}))}/i",//remove the outside braces...
		    "/{(\\\'([aeiou]|{[aeiou]}))}/i",
		    "/{(\\\\\^([aeiou]|{[aeiou]}))}/i",
		    "/{(\\\~([aon]|{[aon]}))}/i",
		    '/{(\\\"([aeiouy]|{[aeiouy]}))}/i',
		    "/{(\\\a\s?(a|{a}))}/i",
		    "/{(\\\c\s?(c|{c}))}/i",
		    "/{(\\\ae|oe)}/i",
		    '/{(\\\s\s?(s|{s}))}/i',
		    "/{(\\\o)}/",
		    "/{(\\\.(I|{I}))}/",
		    "/\\\\`(A|{A})/",  //and the remaining entries: convert to the right utf8 char
		    "/\\\\'(A|{A})/",
		    "/\\\\\\^(A|{A})/",
		    "/\\\\AE/",
		    "/\\\\`(E|{E})/",
		    "/\\\\'(E|{E})/",
		    "/\\\\\^(E|{E})/",
		    "/\\\\`(I|{I})/",
		    "/\\\\'(I|{I})/",
		    "/\\\\\^(I|{I})/",
		    "/\\\\`(O|{O})/",
		    "/\\\\'(O|{O})/",
		    "/\\\\\^(O|{O})/",
		    "/\\\\`(U|{U})/",
		    "/\\\\'(U|{U})/",
		    "/\\\\\^(U|{U})/",
		    "/\\\\`(a|{a})/",
		    "/\\\\'(a|{a})/",
		    "/\\\\\^(a|{a})/",
		    "/\\\\ae/",
		    "/\\\\`(e|{e})/",
		    "/\\\\'(e|{e})/",
		    "/\\\\\^(e|{e})/",
		    "/\\\\`(i|{i})/",
		    "/\\\\'(i|{i})/",
		    "/\\\\\^(i|{i})/",
		    "/\\\\`(o|{o})/",
		    "/\\\\'(o|{o})/",
		    "/\\\\\^(o|{o})/",
		    "/\\\\`(u|{u})/",
		    "/\\\\'(u|{u})/",
		    "/\\\\\^(u|{u})/",
		    "/\\\\\"(a|{a})/",
		    "/\\\\\"(A|{A})/",
		    "/\\\\\"(e|{e})/",
		    "/\\\\\"(E|{E})/",
		    "/\\\\\"(i|{i})/",
		    "/\\\\\"(I|{I})/",
		    "/\\\\\/(u|{u})/",
		    "/\\\\\"(U|{U})/",
		    "/\\\\\"(o|{o})/",
		    "/\\\\\"(O|{O})/",
		    "/\\\\c\s?(c|{c})/",
		    "/\\\\c\s?(C|{C})/",
		    "/\\\\OE/",
		    "/\\\\\"(y|{y})/",
		    "/\\\\\"(Y|{Y})/",
		    "/\\\\ss/",
		    "/\\\\aa/",
		    "/\\\\AA/",
		    "/\\\\'(y|{y})/",
		    "/\\\\'(Y|{Y})/",
		    "/\\\\o/",
		    "/\\\\O/",
		    "/\\\\~(n|{n})/",
		    "/\\\\~(N|{N})/",
		    "/\\\\~(a|{a})/",
		    "/\\\\~(A|{A})/",
		    "/\\\\~(o|{o})/",
		    "/\\\\~(O|{O})/",
		    "/\\\\&/"
	    );
	}


	function getBibtexCharsReplaceArray()
	{
	    return array(
		    "$1",
		    "$1",
		    "$1",
		    "$1",
		    "$1",
		    "$1",
		    "$1",
		    "$1",
		    "$1",
		    "$1",
		    "$1",
		    "À",
		    "Á",
		    "Â",
		    "Æ",
		    "È",
		    "É",
		    "Ê",
		    "Ì",
		    "Í",
		    "Î",
		    "Ò",
		    "Ó",
		    "Ô",
		    "Ù",
		    "Ú",
		    "Û",
		    "à",
		    "á",
		    "â",
		    "æ",
		    "è",
		    "é",
		    "ê",
		    "ì",
		    "í",
		    "î",
		    "ò",
		    "ó",
		    "ô",
		    "ù",
		    "ú",
		    "û",
		    "ä",
		    "Ä",
		    "ë",
		    "Ë",
		    "ï",
		    "ï",
		    "ü",
		    "Ü",
		    "ö",
		    "Ö",
		    "ç",
		    "Ç",
		    "Œ",
		    "ÿ",
		    "Ÿ",
		    "ß",
		    "å",
		    "Å",
		    "ý",
		    "Ý",
		    "ø",
		    "Ø",
		    "ñ",
		    "Ñ",
		    "ã",
		    "Ã",
		    "õ",
		    "Õ",
		    "&"
	    );
	}

	/**
	 * Returns an array containing the hexadecimal representation for all
	 * non-ascii word characters.
	 *
	 * @return array
	 */
	public static function getLatinCharsCodesArray(){
		return array('\x{C0}',
			     '\x{C1}',
			     '\x{C2}',
			     '\x{C8}',
			     '\x{C9}',
			     '\x{CA}',
			     '\x{CC}',
			     '\x{CD}',
			     '\x{CE}',
			     '\x{D2}',
			     '\x{D3}',
			     '\x{D4}',
			     '\x{D9}',
			     '\x{DA}',
			     '\x{DB}',
			     '\x{E0}',
			     '\x{E1}',
			     '\x{E2}',
			     '\x{E8}',
			     '\x{E9}',
			     '\x{EA}',
			     '\x{EC}',
			     '\x{ED}',
			     '\x{EE}',
			     '\x{F2}',
			     '\x{F3}',
			     '\x{F4}',
			     '\x{F9}',
			     '\x{FA}',
			     '\x{FB}',
			     '\x{E4}',
			     '\x{C4}',
			     '\x{EB}',
			     '\x{CB}',
			     '\x{EF}',
			     '\x{CF}',
			     '\x{FC}',
			     '\x{DC}',
			     '\x{F6}',
			     '\x{D6}',
			     '\x{E7}',
			     '\x{C7}',
			     '\x{98}',
			     '\x{FF}',
			     '\x{B2}',
			     '\x{DF}',
			     '\x{E5}',
			     '\x{C5}',
			     '\x{FD}',
			     '\x{DD}',
			     '\x{FE}',
			     '\x{DE}',
			     '\x{F8}',
			     '\x{D8}',
			     '\x{F1}',
			     '\x{D1}',
			     '\x{E3}',
			     '\x{C3}',
			     '\x{F5}',
			     '\x{D5}'
			);
	}

}

?>
