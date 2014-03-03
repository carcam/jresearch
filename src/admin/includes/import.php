<?php

/**
 * Wrapper function for jimport functionality. It allows to import classes and files
 * from J!Research code space
 * @param string $entity The file to import
 * @param string $space Where to look for
 */

function jresearchimport($entity, $space = 'system'){
	$basePath = null;
    switch($space){
    	case 'jresearch.admin':
			$basePath = JRESEARCH_COMPONENT_ADMIN;    		
    		break;
    	case 'jresearch.site':
    		$basePath = JRESEARCH_COMPONENT_SITE;
    		break;
    	case 'jresearch':
    		break;
    	case 'system': 
    	default: 
    		jimport($entity); 
    		return;    	
    }
    
    $components = explode('.', $entity);
	$tail = implode('/', array_slice($components, 0, count($components) - 1));
	$file = $components[count($components) - 1].'.php';
	require_once $basePath.'/'.$tail.'/'.$file;
}

?>
