<script type="text/javascript">
var citedRecordsList = null;
var selectedCitekeys = new Array();
var resultsTable;
var limitstart;

function getResultsTable(){
	if(resultsTable == null){
		resultsTable = document.getElementById("results");
	}
	return resultsTable;
}

function startPublicationSearch(key){
	var searchRequest;
	var criteria = 'all';
	if(key == ''){
		tbody = getResultsTable();
		clean(tbody);
		return;
	}
	
	if(document.getElementById('allRadio').checked)
		criteria = 'all';
	else if(document.getElementById('titleRadio').checked)
		criteria = 'title';
	else if(document.getElementById('keywordsRadio').checked)
		criteria = 'keywords';		
	else if(document.getElementById('yearRadio').checked)
		criteria = 'year';
	else if(document.getElementById('citekeyRadio').checked)
		criteria = 'citekey';
	else if(document.getElementById('authorsRadio').checked)
		criteria = 'authors';	

	searchRequest = new Request({method: 'get', async: true , onSuccess: addSearchResults, onFailure: onSearchFailure});
	searchRequest.send('option=com_jresearch&controller=publications&task=searchByPrefix&format=xml&key='+key+'&criteria='+criteria+'&limitstart='+limitstart, null);	
		
}

function addPagination(lower, upper){
  var title;
	var tbody = getResultsTable();
	var th = document.createElement("th");
	th.setAttribute("colspan", "5");
	th.setAttribute("style", "text-align:center;height:5px;width:100%;");

	
	title = document.getElementById('title');
	var container = document.createElement("div");
	container.setAttribute("style", "margin-left:auto;margin-right:auto;width:100%;");

	if(lower > 0){
		div = document.createElement("div");
		div.setAttribute("class", "button2-right");
		div.setAttribute("className", "button2-right");		
		subdiv = document.createElement("div");
		subdiv.setAttribute("class", "prev");		
		subdiv.setAttribute("className", "prev");
						
		lowerA = document.createElement("a");
		lowerA.setAttribute("href", "javascript:limitstart="+(lower - 10)+";clean(getResultsTable());startPublicationSearch('"+title.value+"');");
		lowerA.appendChild(document.createTextNode("<?php echo JText::_('Back');?>"));
		subdiv.appendChild(lowerA);
		div.appendChild(subdiv);
		container.appendChild(div);
	}
	
	th.appendChild(document.createTextNode("\t\t"));	

	if(upper > 0){
	
		div = document.createElement("div");
		div.setAttribute("class", "button2-left");
		div.setAttribute("className", "button2-left");
		div.setAttribute("style", "margin-left:auto;margin-right:auto");		
		subdiv = document.createElement("div");
		subdiv.setAttribute("class", "next");		
		subdiv.setAttribute("className", "next");	
		
		upperA = document.createElement("a");
		upperA.setAttribute("href", "javascript:limitstart="+upper+";clean(getResultsTable());startPublicationSearch('"+title.value+"');");
		upperA.appendChild(document.createTextNode("<?php echo JText::_('Next');?>"));		
		
		subdiv.appendChild(upperA);
		div.appendChild(subdiv);
		container.appendChild(div);
		
	}

	th.appendChild(container);
	limitstart = 0;
	if(upper > 0 || lower > 0){
		tr = document.createElement("tr");
		tr.setAttribute("align", "center");
		tr.appendChild(th);
		tbody.appendChild(tr);
	}
}

function addSearchResults(response, responseXML){
	var xmlDocument = responseXML;
	var tbody = getResultsTable();
	clean(tbody);
	var publications = xmlDocument.getElementsByTagName("publication");

	var lowerlimit = xmlDocument.getElementsByTagName("lowerlimit");
	lowerlimit = parseInt(lowerlimit[0].firstChild.nodeValue);
	var upperlimit = xmlDocument.getElementsByTagName("upperlimit");
	upperlimit = parseInt(upperlimit[0].firstChild.nodeValue);
	addPagination(lowerlimit, upperlimit);

	
	for(i=0; i<publications.length; i++){
		var tr = document.createElement('tr');

		var titleElement = publications[i].getElementsByTagName('title');
		titleElement = titleElement[0];
		var title = '';
		if(titleElement.hasChildNodes())
			title = titleElement.firstChild.nodeValue;
		
		var citekeyElement = publications[i].getElementsByTagName('citekey');
		citekeyElement = citekeyElement[0];
		var citekey = '';
		if(citekeyElement.hasChildNodes())
			citekey = citekeyElement.firstChild.nodeValue;
			
		var aTitle = document.createElement('a');
		title2 = title.replace(/\'/, "\\'");
		citekey2 = citekey.replace(/\'/, "\\'");
		aTitle.setAttribute('href', "javascript:addRecordToCiteList('"+title2+"','"+citekey2+"')");	
		aTitle.appendChild(document.createTextNode(title));
		
		var authorsElement = publications[i].getElementsByTagName('authors');
		authorsElement = authorsElement[0];
		var authors = '';
		if(authorsElement.hasChildNodes())
			authors = authorsElement.firstChild.nodeValue;
		
		var pubtypeElement = publications[i].getElementsByTagName('type');
		pubtypeElement = pubtypeElement[0];
		var pubtype = '';
		if(pubtypeElement.hasChildNodes())
			pubtype = pubtypeElement.firstChild.nodeValue;
		
		var yearElement = publications[i].getElementsByTagName('year');
		yearElement = yearElement[0];
		var year = '';
		if(yearElement.hasChildNodes())
			year = yearElement.firstChild.nodeValue;
		
		tdTitle = document.createElement('td');
		tdTitle.setAttribute('width', '40%');
		tdTitle.appendChild(aTitle);
		tdCitekey = document.createElement('td');
		tdCitekey.setAttribute('width', '10%');		
		tdCitekey.appendChild(document.createTextNode(citekey));
		tdAuthors = document.createElement('td');
		tdAuthors.setAttribute('width', '30%');		
		tdAuthors.appendChild(document.createTextNode(authors));
		tdType = document.createElement('td');
		tdType.setAttribute('width', '10%');		
		tdType.appendChild(document.createTextNode(pubtype));
		tdYear = document.createElement('td');
		tdYear.setAttribute('width', '10%');
		tdYear.appendChild(document.createTextNode(year));
		
		tr.appendChild(tdTitle);
		tr.appendChild(tdCitekey);
		tr.appendChild(tdAuthors);
		tr.appendChild(tdType);
		tr.appendChild(tdYear);
		
		tbody.appendChild(tr);
	}
	
}

function existsCitekey(key){
	for(i=0; i<selectedCitekeys.length; i++){
		if(selectedCitekeys[i] == key)
			return true;
	}
	
	return false;
	
}


function addRecordToCiteList(title, key){
	if(existsCitekey(key)){
		alert("<?php echo JText::_('CITED_RECORD_REPEATED') ?>");
		return;
	}
	
	var list = getCitedRecordsList();	
	var option = document.createElement('option');
	option.setAttribute('value', key);
	option.appendChild(document.createTextNode(key+': '+title));

	list.appendChild(option);
	selectedCitekeys.push(key);
}

function clean(element){
	while(element.hasChildNodes())
		element.removeChild(element.firstChild);	
}

function getCitedRecordsList(){
	if(citedRecordsList == null)
		citedRecordsList = document.getElementById('citedRecords');
		
	return citedRecordsList;	
}

function onSearchFailure(){
	
}

function makeCitation(command){
	var citeRequest;
	if(selectedCitekeys.length == 0){
		alert("<?php echo JText::_('JRESEARCH_NO_ITEMS_TO_CITE'); ?>");
		return;
	}
		
	var citekeys = selectedCitekeys.join(",");		
	window.parent.document.getElementById('jform_publications').value = citekeys;
	list = getCitedRecordsList();
	clean(list);
	selectedCitekeys = new Array();	
	window.parent.SqueezeBox.close();
}

/**
* Removes the selected record from the list of cited records so it will not 
* be sent to the server when making the citation.
*/
function removeSelectedRecord(){
	var selectedIndex;
	var citekey;
	
	var list = getCitedRecordsList();
	selectedIndex = list.selectedIndex;
	
	if(selectedIndex >= 0){
		var selectedOption = list.options[selectedIndex];
		citekey = selectedOption.value;		
		list.removeChild(selectedOption);
		var citekeyIndex = -1;
		for(j=0; j<selectedCitekeys.length; j++){
			if(selectedCitekeys[j] == citekey){
				citekeyIndex = j;
				break;
			}
		}

		if(citekeyIndex >= 0)
			selectedCitekeys[citekeyIndex] = null;
	}
}

</script>
<div style="text-align:center; width:100%;">
	<label for="citedRecords"><?php echo JText::_('JRESEARCH_CITED_RECORDS').':'; ?></label>
	<?php echo $this->citedRecords; ?>
	<?php echo $this->removeButton; ?>
</div>
<div>&nbsp;</div>
<div style="float: right;">
	<?php echo $this->citeButton; ?>
</div>
<div style="width:100%">
	<label for="title"><?php echo JText::_('JSEARCH').': '; ?></label> <input id="title" name="title" type="text" onkeyup="javascript:limitstart=0;startPublicationSearch(this.value);" />
	<table style="width: 100%">
		<tr>
			<td><label for="all"><?php echo JText::_('JRESEARCH_ALL').':' ?></label><input name="criteria" id="allRadio" value="all" type="radio" /></td>
			<td><label for="keywords"><?php echo JText::_('JRESEARCH_BY_KEYWORDS').':' ?></label><input id="keywordsRadio" name="criteria" value="keywords" type="radio" /></td>
			<td><label for="title"><?php echo JText::_('JRESEARCH_BY_TITLE').':' ?></label><input name="criteria" id="titleRadio" value="title" type="radio" /></td>
			<td><label for="year"><?php echo JText::_('JRESEARCH_BY_YEAR').':' ?></label><input name="criteria" value="year" id="yearRadio" type="radio" /></td>
			<td><label for="authors"><?php echo JText::_('JRESEARCH_BY_AUTHORS').':' ?></label><input name="criteria" id="authorsRadio" value="authors" type="radio" /></td>
			<td><label for="citekey"><?php echo JText::_('JRESEARCH_BY_CITEKEY').':' ?></label><input name="criteria" id="citekeyRadio" value="citekey" type="radio" /></td>
		</tr>
	</table>
	<div>&nbsp;</div>
	<table class="adminlist" style="text-align:center;">
	<thead>
		<tr>
			<th class="title" width="40%"><?php echo JText::_('JRESEARCH_TITLE'); ?></th>
			<th class="title" width="10%"><?php echo JText::_('JRESEARCH_CITEKEY'); ?></th>
			<th class="title" width="30%"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>
			<th class="title" width="10%"><?php echo JText::_('JRESEARCH_PUBLICATION_TYPE'); ?></th>
			<th class="title" width="10%"><?php echo JText::_('JRESEARCH_YEAR'); ?></th>
		</tr>
	</thead>
	<tbody id="results">
	</tbody>
	</table>
</div>