<?php

function fixHTML($string){
	$search = array('[_H2_]','[_/H2_]','[_H3_]', '[_/H3_]' ,'[_P_]', '[_/P_]','<BR>', '<br>');
	$replace = array('<h2>', '</h2>', '<h3>', '</h3>', '<p>', '</p>', '<br />', '<br />');
	return str_replace($search, $replace, $string);	
}

function remove_site_prefix($url){
	return str_replace('http://www.osteopathicresearch.com/', '' ,$url);
}

function removeHTML($string){
	$result = str_replace('<b>', '', $string);
	$result = str_replace('</b>', '', $result);
	return $result;
}

/**
 * Importing journals
 */

$joomla_prefix = 'osteo_';
$connectionOrw = mysql_connect('localhost','root',''); 
$connectionOsteo = mysql_connect('localhost','root',''); 

$db_selected = mysql_select_db("orw", $connectionOrw);
$db_selected2 = mysql_select_db("osteo", $connectionOsteo);

$journalQuery = "SELECT * FROM orw.journal";
$journalResult = mysql_query($journalQuery, $connectionOrw);
$journals = array();
while(($row = mysql_fetch_array($journalResult)) != null){
	$journals[$row['journal_nr']] = $row['journal_name'];
}
	
$institutesQuery = "SELECT * FROM orw.institute";
$institutesResult = mysql_query($institutesQuery, $connectionOrw);

$institutes = array();
while(($row = mysql_fetch_array($institutesResult)) != null){
	$institutes[$row['institute_nr']] = $row;
	$ins_id = mysql_real_escape_string($row['institute_nr']);
	$ins_name = mysql_real_escape_string($row['institute_name']);
	/** Not currently in J!Research */
	$ins_name2 = mysql_real_escape_string($row['institute_name2']);
	$ins_contact_p = mysql_real_escape_string($row['institute_contact_p']);
	/****/
	$ins_street = mysql_real_escape_string($row['institute_street']);
	$ins_street2 = mysql_real_escape_string($row['institute_street2']);
	$ins_location = mysql_real_escape_string($row['institute_place']);
	$ins_zip = mysql_real_escape_string($row['institute_zip']);
	$ins_phone = mysql_real_escape_string($row['institute_phone']);
	$ins_fax = mysql_real_escape_string($row['institute_fax']);
	$ins_email = mysql_real_escape_string($row['institute_email']);
	$ins_url = mysql_real_escape_string($row['institute_homepage']);
	$ins_comment = mysql_real_escape_string($row['comment']);
	$ins_state_province = mysql_real_escape_string($row['institute_state_province']);
	$ins_name_english = mysql_real_escape_string($row['institute_name_english']);
	$ins_country = mysql_real_escape_string($row['land_nr']);

	$insQuery = "INSERT INTO osteo_jresearch_institute(`id`, `name`, `published`, `contact_p`, `street`, `place`, `zip`, 
			`id_country`, `phone`, `fax`, `email`, `url`, `comment`, `street2`, `state_province`, `name_english`)
			VALUES ('$ins_id', '$ins_name', 1, '$ins_contact_p', '$ins_street', '$ins_location', '$ins_zip',
			 '$ins_country', '$ins_phone', '$ins_fax', '$ins_email', '$ins_url', '$ins_comment', '$ins_street2', '$ins_state_province', '$ins_name_english')";

	if(!mysql_query($insQuery, $connectionOsteo)){
		echo 'The database said: '.mysql_error()."\n\n";
		die($insQuery);
	}

}

/** Time to load the journals **/

$journalsPapersQuery = "SELECT * FROM orw.l_journal_paper";
$journalsPapers = mysql_query($journalsPapersQuery, $connectionOrw);

$journalsQuery = "SELECT * FROM orw.journal";
$journals = mysql_query($journalsQuery, $connectionOrw);

/** Construct **/
$journalsMap = array();
while( ($journalRow = mysql_fetch_array($journals)) != null){
	$journalsMap[$journals['journal_nr']] = $journals['journal_name'];
}

$journalsPapersMap = array();
$journalsPapersIdMap = array();
while( ($journalPaperRow = mysql_fetch_array($journalsPapers)) != null){
	$journalsPapersMap[$journalPaperRow['paper_nr']] = $journalsMap[$journalPaperRow['journal_nr']];
	$journalsPapersIdMap[$journalPaperRow['paper_nr']] = $journalPaperRow['journal_nr'];
}

$query = "SELECT * FROM orw.papers";
$result = mysql_query($query, $connectionOrw);
while(($row = mysql_fetch_array($result)) != null){
	// Here we need to map fields
	$title = mysql_real_escape_string(removeHTML($row['paper_title']));
	$original_title = mysql_real_escape_string($row['paper_title_D']);
	$id_language = mysql_real_escape_string($row['language_nr']);
	$country = mysql_real_escape_string($row['land_nr']);

	/********* Date of publication **********/
	$paper_pub_date = $row['paper_pub_date'];
	$compsDate = explode(' ', $paper_pub_date);
	$comps = explode('-', $compsDate[0]);
	/****************************************/
	$year = mysql_real_escape_string($comps[0]);
	$month = mysql_real_escape_string($comps[1]);
	$day = mysql_real_escape_string($comps[2]);

	
	$online_source_type = '';
	$digital_source_type = '';
	
	switch($row['type_nr']){
		case '1': //Journal
			$pubtype = 'proceedings';
			break;
		case '2': //Abstract
			$pubtype = 'article';
			break;	
		case '4': //Audiovisual material
			$pubtype = 'digital_source';
			$digital_source_type = 'film';
			break;
		case '6': //Book chapter
			$pubtype = 'inbook';
			break;
		case '7': //Book whole
			$pubtype = 'book';
			break;
		case '8': //Case
			$pubtype = 'book';
			break;			
		case '9': //Catalog
			$pubtype = 'misc';
			break;
		case '12': //Data file
			$pubtype = 'digital_source';
			$digital_source_type = 'file';
			break;
		case '13': //Electronic citation
			$pubtype = 'online_source';
			$online_source_type = 'website';
			break;	
		case '14': //Generic
			$pubtype = 'misc';	
			break;
		case '16': //In press
			$pubtype = 'misc';
			break;
		case '19': //Journal full
			$pubtype = 'proceedings';
			break;
		case '20': //Journal Article
			$pubtype = 'article';	
			break;
		case '24': //Newspaper
			$pubtype = 'misc';
			break;	
		case '25': //Pamphlet
			$pubtype = 'misc';
			break;
		case '28':	//Report	
			$pubtype = 'misc';
			break;	
		case '29': //Serial
			$pubtype = 'misc';
			break;
		case '33': //Thesis
			$pubtype = 'pthesis';
			break;
		case '35': //Unpublished work
			$pubtype = 'unpublished';
			break;
		case '36': //Clinical trial
			$pubtype = 'misc';
			break;
		case '40': //Undergraduate Project
			$pubtype = 'misc';
			break;
		case '41': //Postgraduate Project
			$pubtype = 'misc';
			break;
		case '99': // Rejected Undergraduate Project
			$pubtype = 'unpublished';
			break;	
		case '100':	case '101':	//Review and Meta-Analysis
			$pubtype = 'misc';
			break;		
		default:
			$pubtype = 'misc';
			break;	
	}
	
	$status = '';
	switch($row['status_nr']){
		case '1':
			$status = 'finished';
			break;
		case '2':
			$status = 'in_progress';
			break;
		case '3':
			$status = 'not_started';
			break;			
		default:
			$status = '';
			break;	
	}

	$paper_abstract = fixHTML($row['paper_abstract']);
	$abstract = mysql_real_escape_string($paper_abstract);
	$paper_abstract_D = fixHTML($row['paper_abstract_D']);
	$original_abstract = mysql_real_escape_string($paper_abstract_D);
	$headings = mysql_real_escape_string($row['paper_headings']);
	$recommended = $row['paper_recom'] == 'Y'?true:false;
	$url = $row['link_fulltext'];
	$files = remove_site_prefix($row['link_pdf']);
	
	$source = 'ORW';
	switch($row['source_nr']){
		case '1':
			$source = 'ORW';
			break;
		case '2':
			$source = 'WSO';
			break;
	}
	
	$isbn = mysql_real_escape_string($row['ISBN']);
	$npages = mysql_real_escape_string($row['pages']);
	$pages = mysql_real_escape_string($row['pages_from_to']);
	
	$nimages = $row['images'];
	$volume = $row['volume'];
	$number = mysql_real_escape_string($row['issue']);
	$publisher = '';
	$issued = mysql_real_escape_string($row['issued']);
	$hidden = $row['hidden'] == 'Y'?true:false;
	$id = mysql_real_escape_string($row['paper_nr']);
	$journal = mysql_real_escape_string($journalsPapersMap[$row['paper_nr']]);
	$id_journal = mysql_real_escape_string($journalsPapersIdMap[$row['paper_nr']]);
	$created = mysql_real_escape_string($row['paper_entry_date']);
	$id_institute = mysql_real_escape_string($row['institute_nr']);

	$pubtypescp = mysql_real_escape_string($pubtype);
	$query = "INSERT INTO osteo_jresearch_publication
			(`title`, `original_title`, `id_language`, `id_research_area`, 
			`id_country`, `year`, `month`, `day`, 
			`pubtype`, `status`, `abstract`, `original_abstract`,
			`headings`, `recommended`, `source`, `issued`, `hidden`, `citekey`, `journal`,
			`pages`, `number`, `isbn`, `volume`, `online_source_type`, `digital_source_type`, 
			`created`, `files`, `url`, `publisher`, `id_institute`, `npages`, `nimages`, `created_by`, `id_journal`) 
			VALUES('$title','$original_title', '$id_language', 1 , 
				'$country', '$year', '$month', '$day', 
				'$pubtypescp', '$status', '$abstract', '$original_abstract',
				'$headings', '$recommended', '$source', '$issued', '$hidden', '$id', '$journal',
				'$pages', '$number', '$isbn', '$volume', '$online_source_type', '$digital_source_type',
				'$created', '$files', '$url', '$publisher', '$id_institute' , '$npages', '$nimages', 62, '$id_journal')";

	// 62 = Super administrator user

	if(!mysql_query($query, $connectionOsteo)){
		echo 'The database said: '.mysql_error()."\n\n";
		die($query);
	}
	
	$newPubid = mysql_insert_id($connectionOsteo); 
	
	//Insert the authors
	$author = $row['paper_author'];
	$email = mysql_real_escape_string($row['author_email']);
	if(!empty($author)){
		$authors = explode('/', $author);
		foreach($authors as $auth){	
			$authT = mysql_real_escape_string(trim($auth));	
			$authorsQuery = "INSERT INTO osteo_jresearch_publication_external_author(`id_publication`, `author_name`, `order`, `author_email`)
				VALUES('$newPubid', '$authT', '0', '$email')";
			if(!mysql_query($authorsQuery, $connectionOsteo)){
				echo 'The database said: '.mysql_error()."\n\n";
				die($authorsQuery);
			}
		}
	}
		
}

// Time to save the journals (this is for future phases)
foreach($journalsMap as $id => $name){
	$idq = mysql_real_escape_string($id);
	$nameq = mysql_real_escape_string($name);
	$journalsQuery = "INSERT INTO osteo_jresearch_journal(`id`, `name`) VALUES('$idq', '$nameq')";
	if(!mysql_query($journalsQuery, $connectionOsteo)){
		echo 'The database said: '.mysql_error()."\n\n";
		die($journalsQuery);
	}
}

mysql_close($connectionOsteo);
mysql_close($connectionOrw);
echo "The data was successfully imported :) ";
?>
