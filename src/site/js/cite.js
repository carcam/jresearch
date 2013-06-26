var citedRecordsList = null;
var selectedCitekeys = new Array();
var resultsTable;
var limitstart;

function getMessage(key){
	return messages[key];
}

function getResultsTable(){
	if(resultsTable == null){
		resultsTable = document.getElementById("results");
	}
	return resultsTable;
}

function startPublicationSearch(e){
	limitstart = 0;
	var targ;
	if (!e) var e = window.event;
	if (e.target) targ = e.target;
	else if (e.srcElement) targ = e.srcElement;
	if (targ.nodeType == 3) // defeat Safari bug
		targ = targ.parentNode;
	key = targ.value;

	runPublicationSearch(key);	
}

function runPublicationSearch(key){		
	var searchRequest;	
	var criteria = 'all';

	if(key == ''){
		key = '%%';
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
		lowerA.setAttribute("href", "javascript:limitstart="+(lower - 10)+";clean(getResultsTable());runPublicationSearch('"+title.value+"');");
		lowerA.appendChild(document.createTextNode(getMessage('back')));
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
		upperA.setAttribute("href", "javascript:limitstart="+upper+";clean(getResultsTable());runPublicationSearch('"+title.value+"');");
		upperA.appendChild(document.createTextNode(getMessage('next')));		
		
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
		alert(getMessage("citeRepeated"));
		//alert("<?php echo JText::_('CITED_RECORD_REPEATED') ?>");
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

function onCiteSuccessful(response){
	window.parent.jInsertEditorText(response, getMessage('citeSuccessful'));
	list = getCitedRecordsList();
	clean(list);
	selectedCitekeys = new Array();
	alert(getMessage('citeSuccessful'));
	//alert("<?php echo JText::_('JRESEARCH_CITE_SUCCESSFUL')?>");	
}

/**
* Invoked when the cite request fails. 
*/
function onCiteFailure(){
	alert(getMessage('citeFailed'));
//	alert("<?php echo JText::_('JRESEARCH_CITE_FAILED')?>");	
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