<?php
/**
 * @version		$Id$
 * @package		JResearch
 * @subpackage	Plugins
 * @copyright	Florian Prinz
 * @license		GNU/GPL
 */

require_once(JPATH_PLUGINS.DS.'xmlrpc'.DS.'jresearch'.DS.'includes'.DS.'services.php');
require_once(JPATH_PLUGINS.DS.'xmlrpc'.DS.'jresearch'.DS.'types'.DS.'eFetchPubmedService.php');

final class JResearchServicesPubmed extends JResearchServices 
{
	/**
	 * @var eFetchResult
	 */
	private $_result = null;
	
	/**
	 * @var stdClass
	 */
	private $_article = null;
	
	/**
	 * Creates a pubmed object for fetching correct pubmed informations
	 *
	 * @param eFetchResult $result
	 */
	public function __construct(eFetchResult $result)
	{
		$this->_result = $result;
		$this->_article = $this->_result->PubmedArticleSet->PubmedArticle->MedlineCitation->Article;
	}
	
	/**
	 * @see JResearchServices::getAbstract()
	 * @return string
	 */
	public function getAbstract()
	{
		//Abstract
		if(property_exists($this->_article, 'Abstract'))
		{
			if($this->_article->Abstract instanceof stdClass)
				$abstract = $this->_article->Abstract->AbstractText;
			else 
				$abstract = $this->_article->Abstract;
		}
		else 
		{
			$abstract = '';
		}
		
		return $abstract;
	}
	
	/**
	 * @see JResearchServices::getTitle()
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_article->ArticleTitle;
	}

	/**
	 * @see JResearchServices::getAuthors()
	 * @return array
	 */
	public function getAuthors()
	{
		global $xmlrpcStruct;
		
		$authors = array();
		
		//Authors
		if(property_exists($this->_article, 'AuthorList'))
		{
			$authorList = $this->_article->AuthorList;
			if(is_array($authorList->Author))
			{
				//Parse authors and create array of authors
				foreach($authorList->Author as $author)
				{
					$author = array(
						'lastname' => new xmlrpcval($author->LastName),
						'firstname' => new xmlrpcval($author->ForeName)
					);
					
					array_push($authors, new xmlrpcval($author, $xmlrpcStruct));
				}
			}
		}
		
		//Return ordered authors list as xmlrpc value
		return $authors;
	}
	
	/**
	 * @see JResearchServices::hasResult()
	 * @return bool
	 */
	public function hasResult()
	{
		if(!is_null($this->_result) && $this->_result instanceof eFetchResult)
		{
			return true;
		}
		
		return false;
	}
}

?>