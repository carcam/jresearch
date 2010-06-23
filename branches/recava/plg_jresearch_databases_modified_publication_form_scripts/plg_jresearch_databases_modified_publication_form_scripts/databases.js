/**
* If this plugin is installed, it modifies normal publication form
* to add a button which retrieves information about a publication
* by its pmkid.
*/
var newButton;
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ''); };

function addDatabasesSearchButton(){
	var citekey = document.getElementById('citekey');
	if(citekey!= null){
		newButton = document.createElement('button');
		newButton.setAttribute('type', 'button');
		newButton.appendChild(document.createTextNode(document.jresearch_plugins_buttonText));
		insertAfter(newButton, citekey);
		newButton.onclick = queryDatabase;
	}
}


function queryDatabase(){
	// Disable the button
	var citekey = document.getElementById('citekey');
	if(citekey){
		var key = citekey.value;	
		key = key.trim();
		if(key != null && key != ''){
			newButton.firstChild.nodeValue = document.jresearch_loading_text+'...';
			newButton.setAttribute('disabled', 'disabled');
			var xmlDocument = '<methodCall><methodName>jresearch.getRemotePublication</methodName>';
			xmlDocument += '<params><param><value><string>'+key+'</string></value></param></params></methodCall>';
			var request = new XHR({method: 'post', onSuccess: mapPublicationToForm, onFailure: onCallFailure});
			request.send(document.jresearch_plugins_xmlrpc_url+'/index.php?service='+document.jresearch_plugins_external_service, xmlDocument);			
		}
	}
}

function onCallFailure(){
	newButton.firstChild.nodeValue = document.jresearch_plugins_buttonText;
	newButton.removeAttribute('disabled');
	alert(document.jresearch_call_failure_message);
}

function mapPublicationToForm(response, responsexml){
	// Return the button to normal state
	var i;
	newButton.firstChild.nodeValue = document.jresearch_plugins_buttonText;
	newButton.removeAttribute('disabled');
	
	if(responsexml == null){
		alert(document.jresearch_call_failure_message);
		return;
	}
	

	
	// Now check if the result is not a fault
	var faults = responsexml.getElementsByTagName('fault');
	if(faults.length > 0){
		var strings = responsexml.getElementsByTagName('string');
		if(strings.length > 0){
			alert(strings[0].firstChild.nodeValue);
		}
		return;
	}
	
	//Time to map the information
	var members = responsexml.getElementsByTagName('member');
	for(i=0; i<members.length; i++){
		// Map result to fields
		var namesArray = members[i].getElementsByTagName('name');
		var valuesArray = members[i].getElementsByTagName('value');
		var name = namesArray[0].firstChild.nodeValue;
		
		if(name == 'authors'){
			var oldHtmlAuthorsList = document.getElementById('authorsfieldresult');
			if (oldHtmlAuthorsList.hasChildNodes())
			{
			    while (oldHtmlAuthorsList.childNodes.length >= 1)
			    {
			    	oldHtmlAuthorsList.removeChild( oldHtmlAuthorsList.firstChild );
			    } 
			}

			//Set the authors counter to 0
			var maxauthorsControl = document.getElementById('nauthorsfield');
			if(maxauthorsControl){
				maxauthorsControl.setAttribute('value', 0);	
			}
			
			// Add the new authors
			var stringsArray = valuesArray[0].getElementsByTagName('string');
			var authorsfieldControl = document.getElementById('authorsfield');
			if(authorsfieldControl && as_xml1_authors){
				for(j=0; j<stringsArray.length; j++){
					var authorName = stringsArray[j].firstChild.nodeValue;
					//Set the author name as control value
					authorsfieldControl.setAttribute('value', authorName);
					as_xml1_authors.fld.value = authorName;
					if(appendAuthor)
						appendAuthor();	
				}
			}
		}else{
			var stringsArray = valuesArray[0].getElementsByTagName('string');
			if(stringsArray[0].firstChild){
				var value = stringsArray[0].firstChild.nodeValue;
				var inputName = document.getElementById(name);
				if(inputName){
					//For month, we make a conversion
					if(name == 'month'){
						var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dic'];
						if(value)
							inputName.setAttribute('value', months[parseInt(value) - 1]);
					}else if(name == 'journal'){
						// Added in customization for 2010: Try to map journal with one in database
						var journalItems = document.forms['adminForm'].list_journal.options;
						var mapped = false;
						for(k = 0; k < journalItems.length; k++){
							var journalName = journalItems[k].firstChild.nodeValue;
							// Applying a trim
							journalName.replace(/^\s+|\s+$/g,"");
							value.replace(/^\s+|\s+$/g,"");
							//Sequences of more than one space are replaced by only one
							journalName.replace(/\s\s+/g," ");
							value.replace(/\s\s+/g," ");
							if(value.toLowerCase() == journalName.toLowerCase()){
								// The journal is in the database
								switchTo('journalslist');
								document.forms['adminForm'].list_journal.selectedIndex = k;
                                                                // It is valid since I changed the RPC server to ensure the year is always before                                                                
                                                                bringImpactFactor(document.forms['adminForm'].year.value);
								mapped = true;
								break;
							}
						}
						
						if(!mapped){
							switchTo('inputtext');
							inputName.setAttribute('value', value);
						}
					}else{
						if(inputName.nodeName.toLowerCase() == 'input')
							inputName.setAttribute('value', value);
						else if(inputName.nodeName.toLowerCase() == 'textarea')
							inputName.appendChild(document.createTextNode(value));						
					}
				}
			}
		}
	}		
}

function insertAfter(newNode, referenceNode){
	if(referenceNode.nextSibling != null)
		referenceNode.parentNode.insertBefore( newNode, referenceNode.nextSibling );
	else
		referenceNode.parentNode.appendChild(node);	
}