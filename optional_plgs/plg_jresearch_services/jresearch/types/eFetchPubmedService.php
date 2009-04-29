<?php

if (!class_exists("IdListType")) {
/**
 * IdListType
 */
class IdListType {
	/**
	 * @access public
	 * @var string[]
	 */
	public $Id;
}}

if (!class_exists("eFetchRequest")) {
/**
 * eFetchRequest
 */
class eFetchRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $id;
	/**
	 * @access public
	 * @var string
	 */
	public $WebEnv;
	/**
	 * @access public
	 * @var string
	 */
	public $query_key;
	/**
	 * @access public
	 * @var string
	 */
	public $tool;
	/**
	 * @access public
	 * @var string
	 */
	public $email;
	/**
	 * @access public
	 * @var string
	 */
	public $retstart;
	/**
	 * @access public
	 * @var string
	 */
	public $retmax;
	/**
	 * @access public
	 * @var string
	 */
	public $rettype;
}}

if (!class_exists("eFetchResult")) {
/**
 * eFetchResult
 */
class eFetchResult {
	/**
	 * @access public
	 * @var string
	 */
	public $ERROR;
	/**
	 * @access public
	 * @var tnsPubmedArticleSet
	 */
	public $PubmedArticleSet;
	/**
	 * @access public
	 * @var IdListType
	 */
	public $IdList;
}}

if (!class_exists("eFetchPubmedService")) {
/**
 * eFetchPubmedService
 * @author WSDLInterpreter
 */
class eFetchPubmedService extends SoapClient {
	/**
	 * Default class map for wsdl=>php
	 * @access private
	 * @var array
	 */
	private static $classmap = array(
		"IdListType" => "IdListType",
		"eFetchRequest" => "eFetchRequest",
		"eFetchResult" => "eFetchResult",
	);

	/**
	 * Constructor using wsdl location and options array
	 * @param string $wsdl WSDL location for this service
	 * @param array $options Options for the SoapClient
	 */
	public function __construct($wsdl="http://www.ncbi.nlm.nih.gov/entrez/eutils/soap/v2.0/efetch_pubmed.wsdl", $options=array()) {
		foreach(self::$classmap as $wsdlClassName => $phpClassName) {
		    if(!isset($options['classmap'][$wsdlClassName])) {
		        $options['classmap'][$wsdlClassName] = $phpClassName;
		    }
		}
		parent::__construct($wsdl, $options);
	}

	/**
	 * Checks if an argument list matches against a valid argument type list
	 * @param array $arguments The argument list to check
	 * @param array $validParameters A list of valid argument types
	 * @return boolean true if arguments match against validParameters
	 * @throws Exception invalid function signature message
	 */
	public function _checkArguments($arguments, $validParameters) {
		$variables = "";
		foreach ($arguments as $arg) {
		    $type = gettype($arg);
		    if ($type == "object") {
		        $type = get_class($arg);
		    }
		    $variables .= "(".$type.")";
		}
		if (!in_array($variables, $validParameters)) {
		    throw new Exception("Invalid parameter types: ".str_replace(")(", ", ", $variables));
		}
		return true;
	}

	/**
	 * Service Call: run_eFetch
	 * Parameter options:
	 * (eFetchRequest) inpp
	 * @param mixed,... See function description for parameter options
	 * @return eFetchResult
	 * @throws Exception invalid function signature message
	 */
	public function run_eFetch($mixed = null) {
		$validParameters = array(
			"(eFetchRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("run_eFetch", $args);
	}


}}

?>