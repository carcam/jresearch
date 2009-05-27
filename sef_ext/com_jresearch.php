<?php
/**
 * sh404SEF support for com_jresearch component.
 * Author : Carlos M. Cámara Mora from JResearch
 * contact : carcam@gnumla.com
 * 
 * {shSourceVersionTag: Version 1.0 - 2009-01-01}
 * 
 * This is a Xmap sh404SEF native plugin file
 *    
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $sefConfig;  
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  standard plugin initialize function - don't change ---------------------------

// ------------------  load language file - adjust as needed ----------------------------------------
$shLangIso = shLoadPluginLanguage( 'com_jresearch', $shLangIso, '_COM_SEF_SH_CREATE_NEW');
// ------------------  load language file - adjust as needed ----------------------------------------

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
shRemoveFromGETVarsList('view');
if (!empty($Itemid))
  shRemoveFromGETVarsList('Itemid');
if (!empty($limit))  
shRemoveFromGETVarsList('limit');
if (isset($limitstart)) 
  shRemoveFromGETVarsList('limitstart'); // limitstart can be zero
    

$Itemid = isset($Itemid) ? @$Itemid : null;
$task = isset($task) ? @$task : null;
$shLangName = isset($shLangName) ? @$shLangName : null;
$id = isset($id) ? @$id : null;


$view = isset($view) ? @$view : null;
$layout = isset($layout) ? @$layout : null;

shRemoveFromGETVarsList('id');
shRemoveFromGETVarsList('task');
shRemoveFromGETVarsList('layout');


switch ($view)
{
	case 'cooperation':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_COOPERATION'];
		break;
	case 'cooperations':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_COOPERATIONS'];
		break;
	case 'facilities':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_FACILITIES'];
		break;
	case 'facility':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_FACILITY'];
		break;
	case 'publicationslist':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PUBLICATIONS_LIST'];
		break;
	case 'projectslist':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PROJECTS_LIST'];
		break;
	case 'researchareaslist':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_RESEARCH_AREAS'];
		break;
	case 'staff':		
		if($layout=='staffflow')
			$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_STAFF_FLOW'];
		else
			$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_STAFF'];
		break;
	case 'theseslist':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_THESIS_LIST'];
		break;
	case 'publication':
		$q = 'SELECT id, title  FROM #__jresearch_publication WHERE id = '.$id;
        	$database->setQuery($q);
	        $shPublication = $database->loadObject( );
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PUBLICATION'];
		$title[] = $shPublication->title;
		break;
	case 'project':
		$q = 'SELECT id, title  FROM #__jresearch_project WHERE id = '.$id;
        	$database->setQuery($q);
	        $shPublication = $database->loadObject( );
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PROJECT'];
		$title[] = $shPublication->title;
		break;
	case 'researcharea':
		$q = 'SELECT id, name  FROM #__jresearch_research_area WHERE id = '.$id;
        	$database->setQuery($q);
	        $shPublication = $database->loadObject( );
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_RESEARCH_AREA'];
		$title[] = $shPublication->name;
		break;
	case 'member':
		if($layout=='edit'){
			$title[]= $sh_LANG[$shLangIso]['_COM_SEF_SH_MEMBER_EDIT'];
		}else{
			$title[]= $sh_LANG[$shLangIso]['_COM_SEF_SH_MEMBER'];
			// we get the firstname and the lastname from the author
			$q = 'SELECT id, firstname, lastname  FROM #__jresearch_member WHERE id = '.$id;
	      $database->setQuery($q);
		   $shMember = $database->loadObject( );
			$id = isset($id) ? @$id : null;		
			$title[]= 'Miembro';		
			$title[] =$shMember->firstname . '-' . $shMember->lastname;
		}
		break;
	case 'thesis':
		$q = 'SELECT id, title  FROM #__jresearch_thesis WHERE id = '.$id;
        	$database->setQuery($q);
	        $shPublication = $database->loadObject( );
		$title[] = $shPublication->title;
		break;
	}

  
// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString, 
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), 
      (isset($shLangName) ? @$shLangName : null));
}      
// ------------------  standard plugin finalize function - don't change ---------------------------
  
?>
