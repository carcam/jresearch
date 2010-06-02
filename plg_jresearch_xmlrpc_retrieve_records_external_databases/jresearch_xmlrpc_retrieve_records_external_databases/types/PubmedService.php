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

$interfaceFile = JPATH_SITE.DS.'components'.DS.'com_jresearch'.DS.'includes'.DS.'interfaces'.DS.'database_service_interface.php';
if (!class_exists("eFetchPubmedService")) {
if(file_exists($interfaceFile)){
require_once($interfaceFile);

/**
 * Wrapper class used to create a common interface for databases.
 *
 */
class PubmedService implements JResearchDatabaseServiceInterface{
	
	private $_efetchPubmedService;
	
	public function __construct(){
		$this->_efetchPubmedService = new eFetchPubmedService();
	}
	
	public function getRequestClass(){ 
		return "eFetchRequest"; 
	}
	
	public function getResponseClass(){ 
		return "eFetchRequest"; 
	}	
	
	public function getPreparedRequest(){
		$requestClass = $this->getRequestClass();
		$request = new $requestClass();
		$request->retstart = '0';
		$request->retmax = '1';
		$request->id = JRequest::getVar('pmid');	
		return $request;
	}
	
	public function call($request){
		return $this->_efetchPubmedService->run_eFetch($request);
	}
	
	public function serialize($result, $format='xml'){
		if($format == 'xml' || $format != 'json')
			return $this->_xmlSerialize($result);
		else{
			$short = JRequest::getVar('short', '0');
			return $this->_jsonSerialize($result, $short);
		}
	}
	
	private function _xmlSerialize($result){
		global $mainframe, $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;
		$publication = array();
		$publication['citekey'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->PMID, $xmlrpcString);		
		$publication['title'] = new xmlrpcval(rtrim($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->ArticleTitle, '.'), $xmlrpcString);		
		$publication['abstract'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText, $xmlrpcString);

                $year = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->DateCreated->Year;
		$month = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->DateCreated->Month;
		$day = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->DateCreated->Day;
		$publication['year'] = new xmlrpcval($year, $xmlrpcString);
		$publication['month'] = new xmlrpcval($month, $xmlrpcString);
		$publication['day'] = new xmlrpcval($day, $xmlrpcString);

		if(!empty($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->ISOAbbreviation)){
                    $publication['journal'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->ISOAbbreviation, $xmlrpcString);
                    $publication['fulljournal'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->Title, $xmlrpcString);
                    $journal = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->ISOAbbreviation;
		}else{			
                    $publication['journal'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->Title, $xmlrpcString);
                    $journal = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->Title;
		}
                		
		$publication['volume'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->Volume, $xmlrpcString);		
		$publication['number'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->Issue, $xmlrpcString);		
		$publication['issn'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->ISSN->_, $xmlrpcString);				
		$publication['pages'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Pagination->MedlinePgn, $xmlrpcString);								
		$authors = array();
		$authorResult = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->AuthorList->Author;
		if(is_array($authorResult)){
				foreach($authorResult as $author){
					$authors[] = new xmlrpcval($author->LastName.' '.$author->Initials, $xmlrpcString);
				}
		}elseif(is_object($authorResult)){
				$authors[] = new xmlrpcval($author->LastName.' '.$author->Initials, $xmlrpcString);        
		}
		
		$publication['authors'] = new xmlrpcval($authors, $xmlrpcArray);		
		$xmlpub = new xmlrpcval($publication, $xmlrpcStruct);
		return new xmlrpcresp($xmlpub);
	}
	
	private function _jsonSerialize($result, $short){
		if($short == '0')
			return json_encode($result);
		else{
			$publication = array();
			$publication['citekey'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->PMID, $xmlrpcString);		
			$publication['title'] = new xmlrpcval(rtrim($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->ArticleTitle, '.'), $xmlrpcString);		
			$publication['abstract'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText, $xmlrpcString);		

			if(!empty($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->ISOAbbreviation)){
                            $publication['journal'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->ISOAbbreviation, $xmlrpcString);
                            $publication['fulljournal'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->Title, $xmlrpcString);
                            $journal = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->ISOAbbreviation;
			}else{			
                            $journal = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->Title;
                        }
//                        //Once the journal is retrieved, get the impact_factor in local database
//                        $db = JFactory::getDBO();
//                        $db->setQuery('SELECT impact_factor FROM #__jresearch_journals WHERE title = '.$db->Quote($journal));
//                        $impact_factor = $db->loadResult();
//                        $publication['impact_factor'] = new xmlrpcval($impact_factor);
		
			$publication['volume'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->Volume, $xmlrpcString);		
			$publication['number'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->JournalIssue->Issue, $xmlrpcString);		
			$publication['issn'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Journal->ISSN->_, $xmlrpcString);				
			$publication['pages'] = new xmlrpcval($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->Pagination->MedlinePgn, $xmlrpcString);								
			$authors = array();
			if(is_array($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->AuthorList->Author)){
				foreach($result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article->AuthorList->Author as $author){
					$authors[] = new xmlrpcval($author->LastName.' '.$author->Initials, $xmlrpcString);
				}
			}
			$publication['authors'] = new xmlrpcval($authors, $xmlrpcArray);
			
			$year = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->DateCreated->Year;
			$month = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->DateCreated->Month;
			$day = $result->PubmedArticleSet->PubmedArticle->MedlineCitation->DateCreated->Day;
			$publication['year'] = new xmlrpcval($year, $xmlrpcString);	
			$publication['month'] = new xmlrpcval($month, $xmlrpcString);
			$publication['day'] = new xmlrpcval($day, $xmlrpcString);
			echo json_encode($publication);	
		}	
	}
}


/**
 * eFetchPubmedService
 * @author WSDLInterpreter
 */
class eFetchPubmedService extends SoapClient{
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

}}}

?>