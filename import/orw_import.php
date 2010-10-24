<?php

function fixHTML($string){
	$search = array('[_H2_]','[_/H2_]','[_H3_]', '[_/H3_]' ,'[_P_]', '[_/P_]','<BR>');
	$replace = array('<h2>', '</h2>', '<h3>', '</h3>', '<p>', '</p>', '<br />');
	return str_replace($search, $replace, $string);	
}

/**
 * 
 */

$joomla_prefix = 'osteo_';
$connection = mysql_connect('localhost','root',''); 
$db_selected = mysql_select_db("orw", $connection);

$journalQuery = "SELECT * FROM journal";
$journalResult = mysql_query($journalQuery, $connection);
$journals = array();
while(($row = mysql_fetch_array($journalResult)) != null){
	$journals[$row['journal_nr']] = $row['journal_name'];
}
	



$query = "SELECT * FROM orw.papers";

$result = mysql_query($query, $connection);



while(($row = mysql_fetch_array($result)) != null){
	// Here we need to map fields
	$title = mysql_real_escape_string($row['paper_title']);
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
	
	switch($row['type_nr']){
		case '1': //Journal
			$pubtype = 'proceedings';
			break;
		case '2': //Abstract
			$pubtype = 'article';
			break;	
		case '3': //Audiovisual material
			$pubtype = 'digital_source';
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
			break;
		case '13': //Electronic citation
			$pubtype = 'online_source';
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
	$publisher = mysql_real_escape_string($row['publisher_nr']);
	$issued = mysql_real_escape_string($row['issued']);
	$hidden = $row['hidden'] == 'Y'?true:false;
	$id = mysql_real_escape_string($row['paper_nr']);
	$journal = mysql_real_escape_string($row['publisher_nr']);
	
	$db_selected = mysql_select_db("osteo_", $connection);

	$pubtypescp = mysql_real_escape_string($pubtype);
	$query = "INSERT INTO osteo_jresearch_publication
			(`title`, `original_title`, `id_language`, `id_research_area`, 
			`id_country`, `year`, `month`, `day`, 
			`pubtype`, `status`, `abstract`, `original_abstract`,
			`headings`, `recommended`, `source`, `issued`, `hidden`, `citekey`) 
			VALUES('$title','$original_title', '$id_language', 0 , '$country', '$year',
				'$month', '$day', '$pubtypescp', '$status', '$abstract', '$original_abstract',
				'$headings', '$recommended', '$source', '$issued', '$hidden', '$id')";
	
	if(!mysql_query($query, $connection)){
		echo 'The database said: '.mysql_error()."\n\n";
		die($query);
	}
	
	$newPubid = mysql_insert_id($connection); 
	
	switch($pubtype){
		case 'article':
			$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`, `journal`, `pages`, `number`) VALUES ('$newPubid', '$journal', '$pages', '$number')";
			break;
		case 'book':
			$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`, `isbn`, `volume`, `number`) VALUES ('$newPubid', '$isbn', '$volume', '$number')";
			break;			
		case 'proceedings':
			$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`, `isbn`, `volume`, `number`) VALUES ('$newPubid', '$isbn', '$volume', '$number')";
			break;
		case 'digital_source':
			$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`, `source_type`) VALUES ('$newPubid', 'film')";			
			break;
		case 'inbook':
			$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`, `pages`, `volume`, `number`) VALUES ('$newPubid', '$pages', '$volume', '$number')";						
			break;
		case 'online_source':
			$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`, `source_type`) VALUES ('$newPubid', 'website')";						
			break;											
		case 'misc': case 'unpublished':
			$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`) VALUES ('$newPubid')";
			break;	
		case 'pthesis':
			$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`, `type`) VALUES ('$newPubid', 'bsc')";
			break;
		default:
			break;	
	}
	$secondQuery = "INSERT INTO osteo_jresearch_$pubtype(`id_publication`) VALUES ('$newPubid')";
	
	
	
	//Insert the authors
	$author = mysql_real_escape_string($row['paper_author']);
	$email = mysql_real_escape_string($row['author_email']);
	$authorsQuery = "INSERT INTO osteo_jresearch_publication_external_author(`id_publication`, `author_name`, `order`, `author_email`)
		VALUES('$newPubid', '$author', '0', '$email')";
	
	if(!mysql_query($secondQuery, $connection)){
		echo 'The database said: '.mysql_error()."\n\n";
		die($secondQuery);
	}
		
	if(!mysql_query($authorsQuery, $connection)){
		echo 'The database said: '.mysql_error()."\n\n";
		die($authorsQuery);
	}
}

mysql_close($connection);
?>
