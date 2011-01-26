<?php
/**
 * Currency Convertor Webservice
 * WSDL: http://www.webservicex.net/CurrencyConvertor.asmx?WSDL
 *
 */

class ConversionRate {
  public $FromCurrency; // Currency
  public $ToCurrency; // Currency
}

class Currency {
  const AFA = 'AFA';
  const ALL = 'ALL';
  const DZD = 'DZD';
  const ARS = 'ARS';
  const AWG = 'AWG';
  const AUD = 'AUD';
  const BSD = 'BSD';
  const BHD = 'BHD';
  const BDT = 'BDT';
  const BBD = 'BBD';
  const BZD = 'BZD';
  const BMD = 'BMD';
  const BTN = 'BTN';
  const BOB = 'BOB';
  const BWP = 'BWP';
  const BRL = 'BRL';
  const GBP = 'GBP';
  const BND = 'BND';
  const BIF = 'BIF';
  const XOF = 'XOF';
  const XAF = 'XAF';
  const KHR = 'KHR';
  const CAD = 'CAD';
  const CVE = 'CVE';
  const KYD = 'KYD';
  const CLP = 'CLP';
  const CNY = 'CNY';
  const COP = 'COP';
  const KMF = 'KMF';
  const CRC = 'CRC';
  const HRK = 'HRK';
  const CUP = 'CUP';
  const CYP = 'CYP';
  const CZK = 'CZK';
  const DKK = 'DKK';
  const DJF = 'DJF';
  const DOP = 'DOP';
  const XCD = 'XCD';
  const EGP = 'EGP';
  const SVC = 'SVC';
  const EEK = 'EEK';
  const ETB = 'ETB';
  const EUR = 'EUR';
  const FKP = 'FKP';
  const GMD = 'GMD';
  const GHC = 'GHC';
  const GIP = 'GIP';
  const XAU = 'XAU';
  const GTQ = 'GTQ';
  const GNF = 'GNF';
  const GYD = 'GYD';
  const HTG = 'HTG';
  const HNL = 'HNL';
  const HKD = 'HKD';
  const HUF = 'HUF';
  const ISK = 'ISK';
  const INR = 'INR';
  const IDR = 'IDR';
  const IQD = 'IQD';
  const ILS = 'ILS';
  const JMD = 'JMD';
  const JPY = 'JPY';
  const JOD = 'JOD';
  const KZT = 'KZT';
  const KES = 'KES';
  const KRW = 'KRW';
  const KWD = 'KWD';
  const LAK = 'LAK';
  const LVL = 'LVL';
  const LBP = 'LBP';
  const LSL = 'LSL';
  const LRD = 'LRD';
  const LYD = 'LYD';
  const LTL = 'LTL';
  const MOP = 'MOP';
  const MKD = 'MKD';
  const MGF = 'MGF';
  const MWK = 'MWK';
  const MYR = 'MYR';
  const MVR = 'MVR';
  const MTL = 'MTL';
  const MRO = 'MRO';
  const MUR = 'MUR';
  const MXN = 'MXN';
  const MDL = 'MDL';
  const MNT = 'MNT';
  const MAD = 'MAD';
  const MZM = 'MZM';
  const MMK = 'MMK';
  const NAD = 'NAD';
  const NPR = 'NPR';
  const ANG = 'ANG';
  const NZD = 'NZD';
  const NIO = 'NIO';
  const NGN = 'NGN';
  const KPW = 'KPW';
  const NOK = 'NOK';
  const OMR = 'OMR';
  const XPF = 'XPF';
  const PKR = 'PKR';
  const XPD = 'XPD';
  const PAB = 'PAB';
  const PGK = 'PGK';
  const PYG = 'PYG';
  const PEN = 'PEN';
  const PHP = 'PHP';
  const XPT = 'XPT';
  const PLN = 'PLN';
  const QAR = 'QAR';
  const ROL = 'ROL';
  const RUB = 'RUB';
  const WST = 'WST';
  const STD = 'STD';
  const SAR = 'SAR';
  const SCR = 'SCR';
  const SLL = 'SLL';
  const XAG = 'XAG';
  const SGD = 'SGD';
  const SKK = 'SKK';
  const SIT = 'SIT';
  const SBD = 'SBD';
  const SOS = 'SOS';
  const ZAR = 'ZAR';
  const LKR = 'LKR';
  const SHP = 'SHP';
  const SDD = 'SDD';
  const SRG = 'SRG';
  const SZL = 'SZL';
  const SEK = 'SEK';
  const CHF = 'CHF';
  const SYP = 'SYP';
  const TWD = 'TWD';
  const TZS = 'TZS';
  const THB = 'THB';
  const TOP = 'TOP';
  const TTD = 'TTD';
  const TND = 'TND';
  const TRL = 'TRL';
  const USD = 'USD';
  const AED = 'AED';
  const UGX = 'UGX';
  const UAH = 'UAH';
  const UYU = 'UYU';
  const VUV = 'VUV';
  const VEB = 'VEB';
  const VND = 'VND';
  const YER = 'YER';
  const YUM = 'YUM';
  const ZMK = 'ZMK';
  const ZWD = 'ZWD';
  const _TRY = 'TRY';
}

class ConversionRateResponse {
  public $ConversionRateResult; // double
}


/**
 * CurrencyConvertor class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
// For servers not including the SOAP extension
if(class_exists('SoapClient')){
	class CurrencyConvertor extends SoapClient {
	
	  private static $classmap = array(
	                                    'ConversionRate' => 'ConversionRate',
	                                    'Currency' => 'Currency',
	                                    'ConversionRateResponse' => 'ConversionRateResponse',
	                                   );
	
	  public function CurrencyConvertor($wsdl = "http://www.webservicex.net/CurrencyConvertor.asmx?WSDL", $options = array()) {
	    foreach(self::$classmap as $key => $value) {
	      if(!isset($options['classmap'][$key])) {
	        $options['classmap'][$key] = $value;
	      }
	    }
	    parent::__construct($wsdl, $options);
	  }
	
	  /**
	   * <br><b>Get conversion rate from one currency to another currency <b><br><p><b><font color='#000080' 
	   * size='1' face='Verdana'><u>Differenct currency Code and Names around the world</u></font></b></p><blockquote><p><font 
	   * face='Verdana' size='1'>AFA-Afghanistan Afghani<br>ALL-Albanian Lek<br>DZD-Algerian Dinar<br>ARS-Argentine 
	   * Peso<br>AWG-Aruba Florin<br>AUD-Australian Dollar<br>BSD-Bahamian Dollar<br>BHD-Bahraini 
	   * Dinar<br>BDT-Bangladesh Taka<br>BBD-Barbados Dollar<br>BZD-Belize Dollar<br>BMD-Bermuda 
	   * Dollar<br>BTN-Bhutan Ngultrum<br>BOB-Bolivian Boliviano<br>BWP-Botswana Pula<br>BRL-Brazilian 
	   * Real<br>GBP-British Pound<br>BND-Brunei Dollar<br>BIF-Burundi Franc<br>XOF-CFA Franc (BCEAO)<br>XAF-CFA 
	   * Franc (BEAC)<br>KHR-Cambodia Riel<br>CAD-Canadian Dollar<br>CVE-Cape Verde Escudo<br>KYD-Cayman 
	   * Islands Dollar<br>CLP-Chilean Peso<br>CNY-Chinese Yuan<br>COP-Colombian Peso<br>KMF-Comoros 
	   * Franc<br>CRC-Costa Rica Colon<br>HRK-Croatian Kuna<br>CUP-Cuban Peso<br>CYP-Cyprus Pound<br>CZK-Czech 
	   * Koruna<br>DKK-Danish Krone<br>DJF-Dijibouti Franc<br>DOP-Dominican Peso<br>XCD-East Caribbean 
	   * Dollar<br>EGP-Egyptian Pound<br>SVC-El Salvador Colon<br>EEK-Estonian Kroon<br>ETB-Ethiopian 
	   * Birr<br>EUR-Euro<br>FKP-Falkland Islands Pound<br>GMD-Gambian Dalasi<br>GHC-Ghanian Cedi<br>GIP-Gibraltar 
	   * Pound<br>XAU-Gold Ounces<br>GTQ-Guatemala Quetzal<br>GNF-Guinea Franc<br>GYD-Guyana Dollar<br>HTG-Haiti 
	   * Gourde<br>HNL-Honduras Lempira<br>HKD-Hong Kong Dollar<br>HUF-Hungarian Forint<br>ISK-Iceland 
	   * Krona<br>INR-Indian Rupee<br>IDR-Indonesian Rupiah<br>IQD-Iraqi Dinar<br>ILS-Israeli Shekel<br>JMD-Jamaican 
	   * Dollar<br>JPY-Japanese Yen<br>JOD-Jordanian Dinar<br>KZT-Kazakhstan Tenge<br>KES-Kenyan 
	   * Shilling<br>KRW-Korean Won<br>KWD-Kuwaiti Dinar<br>LAK-Lao Kip<br>LVL-Latvian Lat<br>LBP-Lebanese 
	   * Pound<br>LSL-Lesotho Loti<br>LRD-Liberian Dollar<br>LYD-Libyan Dinar<br>LTL-Lithuanian 
	   * Lita<br>MOP-Macau Pataca<br>MKD-Macedonian Denar<br>MGF-Malagasy Franc<br>MWK-Malawi Kwacha<br>MYR-Malaysian 
	   * Ringgit<br>MVR-Maldives Rufiyaa<br>MTL-Maltese Lira<br>MRO-Mauritania Ougulya<br>MUR-Mauritius 
	   * Rupee<br>MXN-Mexican Peso<br>MDL-Moldovan Leu<br>MNT-Mongolian Tugrik<br>MAD-Moroccan 
	   * Dirham<br>MZM-Mozambique Metical<br>MMK-Myanmar Kyat<br>NAD-Namibian Dollar<br>NPR-Nepalese 
	   * Rupee<br>ANG-Neth Antilles Guilder<br>NZD-New Zealand Dollar<br>NIO-Nicaragua Cordoba<br>NGN-Nigerian 
	   * Naira<br>KPW-North Korean Won<br>NOK-Norwegian Krone<br>OMR-Omani Rial<br>XPF-Pacific 
	   * Franc<br>PKR-Pakistani Rupee<br>XPD-Palladium Ounces<br>PAB-Panama Balboa<br>PGK-Papua 
	   * New Guinea Kina<br>PYG-Paraguayan Guarani<br>PEN-Peruvian Nuevo Sol<br>PHP-Philippine 
	   * Peso<br>XPT-Platinum Ounces<br>PLN-Polish Zloty<br>QAR-Qatar Rial<br>ROL-Romanian Leu<br>RUB-Russian 
	   * Rouble<br>WST-Samoa Tala<br>STD-Sao Tome Dobra<br>SAR-Saudi Arabian Riyal<br>SCR-Seychelles 
	   * Rupee<br>SLL-Sierra Leone Leone<br>XAG-Silver Ounces<br>SGD-Singapore Dollar<br>SKK-Slovak 
	   * Koruna<br>SIT-Slovenian Tolar<br>SBD-Solomon Islands Dollar<br>SOS-Somali Shilling<br>ZAR-South 
	   * African Rand<br>LKR-Sri Lanka Rupee<br>SHP-St Helena Pound<br>SDD-Sudanese Dinar<br>SRG-Surinam 
	   * Guilder<br>SZL-Swaziland Lilageni<br>SEK-Swedish Krona<br>TRY-Turkey Lira<br>CHF-Swiss 
	   * Franc<br>SYP-Syrian Pound<br>TWD-Taiwan Dollar<br>TZS-Tanzanian Shilling<br>THB-Thai Baht<br>TOP-Tonga 
	   * Pa'anga<br>TTD-Trinidad&amp;amp;Tobago Dollar<br>TND-Tunisian Dinar<br>TRL-Turkish Lira<br>USD-U.S. 
	   * Dollar<br>AED-UAE Dirham<br>UGX-Ugandan Shilling<br>UAH-Ukraine Hryvnia<br>UYU-Uruguayan 
	   * New Peso<br>VUV-Vanuatu Vatu<br>VEB-Venezuelan Bolivar<br>VND-Vietnam Dong<br>YER-Yemen 
	   * Riyal<br>YUM-Yugoslav Dinar<br>ZMK-Zambian Kwacha<br>ZWD-Zimbabwe Dollar</font></p></blockquote> 
	   * 
	   *
	   * @param ConversionRate $parameters
	   * @return ConversionRateResponse
	   */
	  public function ConversionRate(ConversionRate $parameters) {
	    return $this->__soapCall('ConversionRate', array($parameters),       array(
	            'uri' => 'http://www.webserviceX.NET/',
	            'soapaction' => ''
	           )
	      );
	  }
	
	}
}

?>
