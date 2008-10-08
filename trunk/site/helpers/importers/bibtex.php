<?php

/**
* @version		$Id$
* @package		JResearch
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'helpers'.DS.'importers'.DS.'importer.php');

class JResearchBibtexImporter extends JResearchPublicationImporter{
	
	/**
	 * Parse the text sent as parameter in Bibtex format and converts it into 
	 * an array of JResearchPublication objects.
	 *
	 * @param string $text
	 * @param array of JResearchPublication objects
	 */
	public function parse($text){
		$commandsArray = array();
		$resultArray = array();
		$parsedStrings = array();
		$publicationsResult = array();
		$types = JResearchPublication::getPublicationsSubtypes();
		$pubtypesStr = implode("|", $types);
		$savedRecords = 0;

		// Regexp for searching the commands 
		$commands_regexp = "/@($pubtypesStr)\s*\{\s*([\w_][-\w\d_:+]*)\s*,/imsDU";
		// Regexp for searching strings 
		$string_regexp = "/@(string)\s*\{\s*([\w_][-\w\d_:+]*)\s*=\s*\"(.*)\"\s*\}/imsDU"; 
		preg_match_all($commands_regexp, $text, &$commandsArray, PREG_SET_ORDER);
		preg_match_all($string_regexp, $text, &$stringsArray, PREG_SET_ORDER);

		// Associative array of string values. Later evaluated 
		foreach($stringsArray as $str){
			$key = trim($str[2]);
			$value = trim($str[3]);
			$parsedStrings[$key] = $value;		
			// Once parsed, remove them from text
			$text = str_replace($str[0],'', $text);
		}


		$initialPosition = 0;
		$nextPosition = 0;

		for($i=0; $i<count($commandsArray); $i++){
			$pubArray = array();
			$initialPosition = strpos($text, $commandsArray[$i][0], $initialPosition) + strlen($commandsArray[$i][0]);		

			if(isset($commandsArray[$i+1]))
				$nextPosition = strpos($text, $commandsArray[$i+1][0], $initialPosition);
			else
				$nextPosition = strlen($text) - 1;
		
			$type = strtolower($commandsArray[$i][1]);
			if($type == 'inproceedings')
				$type = 'conference';
			$pubArray['pubtype'] = strtolower($type);
			$citekey = $commandsArray[$i][2];
			$pubArray['citekey'] = $citekey;

			$field_regexp = '/\s*([-_\d\w]+)\s*=/imsDU';
			$substr = trim(substr($text, $initialPosition, $nextPosition - $initialPosition));

			if($substr{strlen($substr) - 1} == '}')
				$substr = substr($substr, 0, strlen($substr) - 1);


			preg_match_all($field_regexp, $substr, &$contentsArray, PREG_SET_ORDER);

			$initialFieldPosition = 0;
			$nextFieldPosition = 0;
			for($j = 0; $j < count($contentsArray); $j++){
				$initialFieldPosition = strpos($substr, $contentsArray[$j][0], $initialFieldPosition) + strlen($contentsArray[$j][0]);
		
				if(isset($contentsArray[$j+1]))
					$nextFieldPosition = strpos($substr, $contentsArray[$j+1][0], $initialFieldPosition);
				else
					$nextFieldPosition = strlen($substr) - 1;
			
				$fieldName = strtolower($contentsArray[$j][1]);
				$rawValue = trim(substr($substr, $initialFieldPosition, $nextFieldPosition - $initialFieldPosition + 1));

				// Time to process the raw value. Eliminate the trailing comma
				if($rawValue{strlen($rawValue) - 1} == ',')
					$rawValue = substr($rawValue, 0, strlen($rawValue) - 1);

				// Delete newlines and tabs
				$rawValue = preg_replace('/[\s\t\n]+/', ' ', $rawValue, -1, &$count);	
				$rawValue = $this->evaluateStrings($rawValue, $parsedStrings);
				$rawValue = $this->concatenateStrings($rawValue);

				$pubArray[$fieldName] = $rawValue;
				if($fieldName == 'author'){
					// Time to get the authors
					$authorsArray = explode("and", $rawValue); 
				}

			}
			$newpub = JResearchPublication::getSubclassInstance($type);
			if($newpub != null){
				if(isset($authorsArray)){
					$m = count($authorsArray); 					
					for($x=0; $x<$m; $x++){
						$newpub->setAuthor(trim($authorsArray[$x]), $x, false);
					}
					unset($authorsArray);	
				}
				// Set the publication as external
				$newpub->internal = false;
					
				$newpub->bind($pubArray);				
				$publicationsResult[] = $newpub;

			}
	
		}


		return $publicationsResult;
	}
	
	/**
	 * Evaluates string components and performs the concatenation. In bibtex formats,
	 * operator # is used to concatenate strings.
	 * 
	 * @param string $text
	 * @param string Result string
	 */
	private function concatenateStrings($text){
		// Correct: Use regexp to accept more than one space in each case.
		$pieces = explode(' # ', $text);
		$result = '';
		$i = 0;
	
		foreach($pieces as $str){
			$trimedString = $str;
			$n = strlen($trimedString);
			$braces = 0;
			$quotes = 0;
			for($j=0; $j<$n; $j++){
				if(($j == 0  || $trimedString{$j-1} != "\\")  && $trimedString{$j} == '{')
					$braces++;
				elseif(($j == 0  || $trimedString{$j-1} != "\\") && $trimedString{$j} == "}")	
					$braces--;
				elseif(($j == 0 || $trimedString{$j-1} != "\\") && $trimedString{$j} == '"')	
					$quotes++;
			}
			// If the delimiter is inside a string
			if($braces == 0 && $quotes%2 == 0){
				$result .= $this->cleanBraces($trimedString);
			}else{
				if($i > 0)
					$result .= '#'.$this->cleanBraces($trimedString);
				else
					$result .= $this->cleanBraces($trimedString);
			}
			$i++;
				
		}
		
		return $this->cleanBraces($result);
	}
	
	/**
	* Cleans the embracing characters of Bibtex strings ("" or {})
	* 
	* @param string $text The text that will be cleaned
	* @return string
	*/
	private function cleanBraces($text){
		$result = $text;	
		if($result{0} == '"' && $result{strlen($result) - 1 } == '"'){
			$result = substr($result, 1, strlen($result) - 2);
		}elseif($result{0} == '{' && $result{strlen($result) - 1 } == '}'){
			$result = substr($result, 1, strlen($result) - 2);
		}
	
		return $result;
	
	}
	
	/**
	 * Takes a string and evaluates any constant present in $stringsHashmap. E.g:
	 * If string is [PN # "&" EN], the function will look for entries with 'PN' and 'EN' as keys 
	 * and replace them with the value in the resultant string.
	 *
	 * @param string $textValue The string that will be evaluated.
	 * @param array $stringsHashmap Associative array with strings constant names as strings and values.
	 * @return Evaluated string
	 */
	private function evaluateStrings($textValue, $stringsHashmap){
		$contentsArray = array();
		$evaluatedString = $textValue;
		
		foreach($stringsHashmap as $k=>$v){
			$offset = 0;
			$positions = array();
			while(($pos = @strpos($evaluatedString, $k, $offset)) !== false){
				// Count the number of {} or "" to see if it needs to be parsed.				
				$braces = 0;
				$quotes = 0;
				for($i=0; $i<$pos; $i++){
					if($evaluatedString{$i} == '{' && $evaluatedString{$i-1} != "\\" )
						$braces++;
					elseif($evaluatedString{$i} == '}' && $evaluatedString{$i-1} != "\\")	
						$braces--;
					elseif($evaluatedString{$i} == '"' && $evaluatedString{$i-1} != "\\")	
						$quotes++;
						
				}
	
				// It needs to be evaluated
				$empty = array();
				if($braces == 0 && $quotes % 2 == 0 && preg_match("/\b$k\b/", $evaluatedString, &$empty, null, $pos) == 1 ){
					$evaluatedString = substr_replace($evaluatedString, "\"$v\"", $pos, strlen($k));
					$offset = $pos + strlen($v);
				}else{
					$offset = $pos + strlen($k);
				}
				
	
			}
			
		}
		return $evaluatedString;
	
	}
}


?>