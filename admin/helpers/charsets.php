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

/**
 * This class holds useful methods for dealing with word special characters
 * 
 *
 */
class JResearchCharsetsHelper{
	
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


	public static function getLowerToUpperCaseTable(){
		return self::$UTF8_LOWER_TO_UPPER;
	}
	
	public static function getUpperToLowerCaseTable(){
		return self::$UTF8_UPPER_TO_LOWER;
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
                    "/ž/",
                    "/Ž/",
                    "/Ř/",
                    "/ř/",
                    "/Č/",
                    "/č/",
                    "/Š/",
                    "/š/",
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
		    "{\\vz}",
                    "{\\vZ}",
                    "{\\vR}",
                    "{\\vr}",
                    "{\\vC}",
                    "{\\vc}",
                    "{\\vS}",
            "{\\vs}",
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
                    "/{\\\([aeiou])}/i",
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
                    "/{(\\\v\s?([zrcs]|{[zrcs]}))}/i", //remove the outside braces...
                    "/\\\\v\s?(z|{z})/",
                    "/\\\\v\s?(Z|{Z})/",
                    "/\\\\v\s?(R|{R})/",
                    "/\\\\v\s?(r|{r})/",
                    "/\\\\v\s?(C|{C})/",
                    "/\\\\v\s?(c|{c})/",
                    "/\\\\v\s?(S|{S})/",
                    "/\\\\v\s?(s|{s})/",
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
                    "$1",
                    "ž",
                    "Ž",
                    "Ř",
                    "ř",
                    "Č",
                    "č",
                    "Š",
                    "š",
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
			     '\x{D5}',
                             '\x{C5BE}',
                             '\x{C5BD}',
                             '\x{C598}',
                             '\x{C599}',
                             '\x{C48C}',
                             '\x{C48D}',
                             '\x{C5A0}',
                             '\x{C5A1}'
			);
	}
	
	/**
	 * Returns a simple array with the non-ascii word-characters for latin based languages.
	 * @return array
	 */
	public static function getLatinWordSpecialChars(){
		return array(
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
                    "ž",
                    "Ž",
                    "Ř",
                    "ř",
                    "Č",
                    "č",
                    "Š",
                    "š"

	    );
	}
}

?>