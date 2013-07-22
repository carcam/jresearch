<?php 
// Short script to fix the columns authors, research areas and keywords for publications
// =========================== 
$publicationsTable = 'ni2_jresearch_publication';
$keywordsTable = 'ni2_jresearch_keyword';
$projectsTable = 'ni2_jresearch_project';
$thesesTable = 'ni2_jresearch_thesis';

$publicationKeywordsTable = 'ni2_jresearch_publication_keyword';
$publicationResearchAreaTable = 'ni2_jresearch_publication_researcharea';
$publicationInternalAuthorTable = 'ni2_jresearch_publication_internal_author';
$publicationExternalAuthorTable = 'ni2_jresearch_publication_external_author';

$projectResearchAreaTable = 'ni2_jresearch_project_researcharea';
$projectInternalAuthorTable = 'ni2_jresearch_project_internal_author';
$projectExternalAuthorTable = 'ni2_jresearch_project_external_author';

$thesisResearchAreaTable = 'ni2_jresearch_thesis_researcharea';
$thesisInternalAuthorTable = 'ni2_jresearch_thesis_internal_author';
$thesisExternalAuthorTable = 'ni2_jresearch_thesis_external_author';


$server = 'localhost';
$user = 'root';
$password = '';
$dbName = 'nifdi';

function fixKeywords($db){
	$query = 'SELECT id, keywords from '.$publicationsTable;
	$result = mysql_query($query, $db);
	$finalResult = array();
	$keywordsArray = array();
	while(true) {	
		$line = mysql_fetch_assoc($result);
		if($line === FALSE) break;
		$keywords = explode(',', $line['keywords']);
		foreach($keywords as $word){
			$word = trim($word);
			if(!empty($word)){			
				$finalResult[] = array($line['id'], $word);
				$keywordsArray[] = $word;
			}
		}
	}
	
	$cleanKeywords = array_unique($keywordsArray);
	foreach($cleanKeywords as $keyword){
		$insertQuery = "INSERT INTO $keywordsTable VALUES('$keyword')";
		if(!mysql_query($insertQuery, $db))
			echo mysql_error($db).': '.$insertQuery."\n"; 
	}
	
	foreach($finalResult as $row){
		$insertQuery = "INSERT INTO $publicationKeywordsTable VALUES(".$row[0].",'".$row[1]."')";
		if(!mysql_query($insertQuery, $db))
			echo mysql_error($db).': '.$insertQuery."\n"; 		
	}

}


//Fix research areas
function fixResearchAreas($db){
	$query = 'SELECT id, id_research_area FROM '.$publicationsTable;
	$result = mysql_query($query, $db);
	while(true){	
		$row = mysql_fetch_assoc($result);
		if($row === FALSE) break;
		$idPub = $row['id'];
		$idResearchArea = $row['id_research_area'];
		if($idResearchArea != '1'){
			$insertQuery = "INSERT INTO $publicationResearchAreaTable VALUES('$idPub', '$idResearchArea')";
			if(!mysql_query($insertQuery, $db))
				echo mysql_error($db).': '.$insertQuery."\n";
		}
	}

	$query = 'SELECT id, id_research_area FROM '.$projectsTable;
	$result = mysql_query($query, $db);
	while(true){	
		$row = mysql_fetch_assoc($result);
		if($row === FALSE) break;
		$idProj = $row['id'];
		$idResearchArea = $row['id_research_area'];
		if($idResearchArea != '1'){
			$insertQuery = "INSERT INTO $projectResearchAreaTable VALUES('$idProj', '$idResearchArea')";
			if(!mysql_query($insertQuery, $db))
				echo mysql_error($db).': '.$insertQuery."\n";
		}
	}

	$query = 'SELECT id, id_research_area FROM '.$thesesTable;
	$result = mysql_query($query, $db);
	while(true){	
		$row = mysql_fetch_assoc($result);
		if($row === FALSE) break;
		$idThes = $row['id'];
		$idResearchArea = $row['id_research_area'];
		if($idResearchArea != '1'){
			$insertQuery = "INSERT INTO $thesisResearchAreaTable VALUES('$idThes', '$idResearchArea')";
			if(!mysql_query($insertQuery, $db))
				echo mysql_error($db).': '.$insertQuery."\n";
		}
	}
}

function fixAuthors($db){
	$query = "SELECT id_publication, author, `order` FROM 
	(SELECT id_publication, id_staff_member as author, `order` FROM $publicationInternalAuthorTable UNION 
	SELECT id_publication, author_name as author, `order` FROM $publicationExternalAuthorTable) R1 ORDER BY R1.id_publication, `R1`.`order`";
	$result = mysql_query($query, $db);
	$finalLines = array();
	while(true){	
		$row = mysql_fetch_assoc($result);
		if($row === FALSE) break;
		if(!isset($finalLines[$row['id_publication']])){
			$finalLines[$row['id_publication']] = array($row['author']);			
		}else{
			$finalLines[$row['id_publication']][] = $row['author'];
		} 
	}
	
	//Now insert them back
	foreach($finalLines as $pub => $authors){
		$authorsText = implode(';', $authors);
		$insertQuery = "UPDATE $publicationsTable SET authors='$authorsText' WHERE id = $pub";
		if(!mysql_query($insertQuery, $db))
			echo mysql_error($db).': '.$insertQuery."\n";
	}

	$query = "SELECT id_project, author, `order` FROM 
	(SELECT id_project, id_staff_member as author, `order`, is_principal FROM $projectInternalAuthorTable UNION 
	SELECT id_project, author_name as author, `order`, is_principal FROM $projectExternalAuthorTable) R1 ORDER BY R1.id_project, `R1`.`order`";
	$result = mysql_query($query, $db);
	$finalLines = array();
	while(true){	
		$row = mysql_fetch_assoc($result);
		if($row === FALSE) break;
		$principalFlag = $row['is_principal'] == 1 ? '|on' : '';
		if(!isset($finalLines[$row['id_project']])){
			$finalLines[$row['id_project']] = array($row['author'].$principalFlag);			
		}else{
			$finalLines[$row['id_project']][] = $row['author'].$principalFlag;
		} 
	}
	
	//Now insert them back
	foreach($finalLines as $proj => $authors){
		$authorsText = implode(';', $authors);
		$insertQuery = "UPDATE $projectsTable SET authors='$authorsText' WHERE id = $proj";
		if(!mysql_query($insertQuery, $db))
			echo mysql_error($db).': '.$insertQuery."\n";
	}

	$query = "SELECT id_thesis, author, `order` FROM 
	(SELECT id_thesis, id_staff_member as author, `order`, is_director FROM $thesisInternalAuthorTable UNION 
	SELECT id_thesis, author_name as author, `order`, is_director FROM $thesisExternalAuthorTable) R1 ORDER BY R1.id_thesis, `R1`.`order`";
	$result = mysql_query($query, $db);
	$finalLines = array();
	while(true){	
		$row = mysql_fetch_assoc($result);
		if($row === FALSE) break;
		$directorFlag = $row['is_director'] == 1 ? '|on' : '';
		
		if(!isset($finalLines[$row['id_thesis']])){
			$finalLines[$row['id_thesis']] = array($row['author'].$directorFlag);			
		}else{
			$finalLines[$row['id_thesis']][] = $row['author'].$directorFlag;
		} 
	}
	
	//Now insert them back
	foreach($finalLines as $thes => $authors){
		$authorsText = implode(';', $authors);
		$insertQuery = "UPDATE $thesesTable SET authors='$authorsText' WHERE id = $thes";
		if(!mysql_query($insertQuery, $db))
			echo mysql_error($db).': '.$insertQuery."\n";
	}		 
}

$db = mysql_connect($server, $user, $password);
mysql_select_db($dbName, $db);
fixKeywords($db);
fixResearchAreas($db);
fixAuthors($db);
echo "Done!!!";
?>
