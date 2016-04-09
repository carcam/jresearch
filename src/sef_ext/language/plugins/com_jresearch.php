<?php
/**
 * sh404SEF support for com_jresearch component.
 * Author : Carlos M. Cámara Mora from JResearch
 * contact : carcam@gnumla.com
 *
 * {shSourceVersionTag: Version 1.0 - 2009-01-01}
 *
 * This is a sh404SEF native plugin file
 * @license	GNU/GPL
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
	case 'publicationslist':
	case 'publications':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PUBLICATIONS_LIST'];
		break;
	case 'projectslist':
	case 'projects':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_PROJECTS_LIST'];
		break;
	case 'researchareaslist':
	case 'researchareas':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_RESEARCH_AREAS'];
		break;
	case 'staff':
		$title[] = $sh_LANG[$shLangIso]['_COM_SEF_SH_STAFF'];
		break;
	case 'theses':
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
		switch($task) {
			case 'edit':
				$title[]= $sh_LANG[$shLangIso]['_COM_SEF_SH_EDIT'];
				break;
			case 'show':
				// we get the firstname and the lastname from the author
				$q = 'SELECT id, firstname, lastname  FROM #__jresearch_member WHERE id = '.$id;
		        	$database->setQuery($q);
			        $shMember = $database->loadObject( );
				$id = isset($id) ? @$id : null;
				$title[]= $sh_LANG[$shLangIso]['_COM_SEF_SH_MEMBER'];
				$title[] =$shMember->firstname . '-' . $shMember->lastname;
				break;
		}
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
