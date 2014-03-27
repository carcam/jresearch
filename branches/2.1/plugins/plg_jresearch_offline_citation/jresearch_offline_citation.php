<?php
/**
* @package		JResearch
* @subpackage	
* @copyright	Copyright (C) 2008 Luis Galarraga.
* @license		GNU/GPL v2
* Description
*/

defined('_JEXEC') or die('Restricted access');

class plgContentJResearch_Offline_Citation extends JPlugin{

	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function onContentPrepare($context, &$article, &$params, $page = 0){
		if(!JComponentHelper::isEnabled('com_jresearch', true))
		 	return;
		 		
	 	if(!defined('JRESEARCH_COMPONENT_ADMIN'))
			define('JRESEARCH_COMPONENT_ADMIN', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jresearch');
	
		if(!defined('JRESEARCH_COMPONENT_SITE'))
			define('JRESEARCH_COMPONENT_SITE', JPATH_SITE.DS.'components'.DS.'com_jresearch');	
		
	 	require_once JRESEARCH_COMPONENT_ADMIN.DS.'includes'.DS.'import.php';
		require_once JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'cite.php';
		require_once JRESEARCH_COMPONENT_ADMIN.DS.'tables'.DS.'publication.php';
		require_once JRESEARCH_COMPONENT_ADMIN.DS.'helpers'.DS.'publications.php';
		require_once JRESEARCH_COMPONENT_SITE.DS.'citationStyles'.DS.'factory.php';
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jresearch', JPATH_SITE);		
		$component = JComponentHelper::getComponent('com_jresearch');
		$style = $component->params->get('citationStyle', 'APA');
		
	
	 	$regex = '/(cite|citep|citeyear|nocite){((?:\s*[-a-zA-Z0-9:.+_]+\s*,)*\s*[-a-zA-Z0-9:.+_]+\s*)}|(bibliography){}/';
 		$matches = array();
 		
		// check whether plugin has been unpublished
		if ( !$this->params->get( 'enabled', 1 ) ) {
			$row->text = preg_replace( $regex, '', $article->text );
			return true;
		}
 
	 	// find all instances of plugin and put in $matches
		preg_match_all( $regex, $article->text, $matches, PREG_PATTERN_ORDER);
 
		// Number of plugins
	 	$commands = $this->_extractCommands($matches);
 		$bibCommands = array();
	 	$runBibliography = false;
	 	$citedPublications = array();
	 	
 		foreach($commands as $cmd){
 			if($cmd->get('command') != 'bibliography'){
 				$this->_process($cmd, $citedPublications, $style);
 				$article->text = str_replace($cmd->get('target'), $cmd->get('citation'), $article->text);
 			}else{
 				$runBibliography = true;
 			}
 		}
	 	
 		if($runBibliography){
 			$replaceBib = $this->_processBibliography($citedPublications, $style);
 			$article->text = str_replace('bibliography{}', $replaceBib, $article->text);
 		}
	}
	
	protected function _extractCommands(array $matches){
		$commands = array();
		$arguments = array();
		$result = array();
		foreach($matches[1] as $command){
			if(!empty($command))
				$commands[] = $command;
			else
				$commands[] = 'bibliography';
		}
		
		foreach($matches[2] as $argument){
			$arguments[] = explode(',', $argument);
		}
		
		for($i = 0; $i < count($commands); ++$i){
			$instruction = new JObject();
			$instruction->set('command', $commands[$i]);
			$instruction->set('arguments', $arguments[$i]);
			$instruction->set('target', $matches[0][$i]);
			$result[] = $instruction;
		}
		
		return $result;
	}
	
	protected function _process(JObject $commandObject, array &$publications, $style){
		foreach($commandObject->get('arguments') as $citekey){
			$publication = JResearchPublicationsHelper::getPublicationFromCitekey(trim($citekey));
			if($publication != null){
				if($commandObject->get('command') == 'cite'){
					$styleObj = JResearchCitationStyleFactory::getInstance($style, $publication->pubtype);
					$commandObject->set('citation', $styleObj->getCitationHTMLText($publication));
				}else if($commandObject->get('command') == 'citep'){
					$styleObj = JResearchCitationStyleFactory::getInstance($style, $publication->pubtype);
					$commandObject->set('citation', $styleObj->getParentheticalCitationHTMLText($publication));
				}else if($commandObject->get('command') == 'citeyear'){
					$commandObject->set('citation', '('.$publication->year.')');
				}else if($commandObject->get('command') == 'nocite'){
					$commandObject->set('citation', '');
				}
				
				$publications[] = $publication;
			}
		}
	}
	
	protected function _processBibliography(array $citedPublications, $style){
		$styleObj = JResearchCitationStyleFactory::getInstance($style);
		return $styleObj->getBibliographyHTMLText($citedPublications);
	}	
}
?>