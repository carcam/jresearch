/**
* If this plugin is installed, it modifies normal publication form
* to add a button which retrieves information about a publication
* by its pmkid.
*/
var newButton;
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ''); };

function addDatabasesSearchButton(){
	citekey = document.getElementById('citekey');
	if(citekey!= null){
		parent = citekey.parentNode;
		text = document.createTextNode(' ');
		newButton = document.createElement('button');
		newButton.setAttribute('type', 'button');
		newButton.appendChild(document.createTextNode(document.jresearch_plugins_buttonText));
		insertAfter(newButton, citekey);
		newButton.onclick = queryDatabase;
	}
}


function queryDatabase(){
	// Disable the button
	citekey = document.getElementById('citekey');
	if(citekey){
		key = citekey.value;	
		key = key.trim();
		if(key != null && key != ''){
			newButton.firstChild.nodeValue = document.jresearch_loading_text+'...';
			newButton.setAttribute('disabled', 'disabled');
			xmlDocument = '<methodCall><methodName>jresearch.getRemotePublication</methodName>';
			xmlDocument += '<params><param><value><string>'+key+'</string></value></param></params></methodCall>';
			request = new XHR({method: 'post', onSuccess: mapPublicationToForm, onFailure: onCallFailure});
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
	newButton.firstChild.nodeValue = document.jresearch_plugins_buttonText;
	newButton.removeAttribute('disabled');
	
	if(responsexml == null){
		alert(document.jresearch_call_failure_message);
		return;
	}
	
	// Now check if the result is not a fault
	faults = responsexml.getElementsByTagName('fault');
	if(faults.length > 0){
		strings = responsexml.getElementsByTagName('string');
		if(strings.length > 0){
			alert(strings[0].firstChild.nodeValue);
		}
		return;
	}
	
	//Time to map the information
	members = responsexml.getElementsByTagName('member');
	for(i=0; i<members.length; i++){
		// Map result to fields
		namesArray = members[i].getElementsByTagName('name');
		valuesArray = members[i].getElementsByTagName('value');
		name = namesArray[0].firstChild.nodeValue;
		if(name == 'authors'){
			maxauthorsControl = document.getElementById('maxauthors');
			// Clear existing authors. The first author control is never removed.
			if(maxauthorsControl && deleteControl){
				maxauthorsValue = parseInt(maxauthorsControl.getAttribute('value'));
				for(k = 1; k<= maxauthorsValue; k++)
					deleteControl('authors'+k);
				maxauthorsControl.setAttribute('value', 0);	
			}
			// Add the new authors
			stringsArray = valuesArray[0].getElementsByTagName('string');
			for(j=0; j<stringsArray.length; j++){
				authorName = stringsArray[j].firstChild.nodeValue;
				if(j == 0){
					//Update the first author field
					authors0 = document.getElementById('authors0');
					if(authors0)
						authors0.setAttribute('value', authorName);
				}else{
					// The other ones are added. We take the invocation from the "Add" link
					addAuthors = document.getElementById('addauthors');
					if(addAuthors){
						href = addAuthors.getAttribute('href');
						hrefArray = href.split(':');
						callableText = hrefArray[1];
						// That is the same as addControl	
						eval(callableText);
						// Once the control has been added, we update its value
						newControl = document.getElementById('authors'+j);
						newControl.setAttribute('value', authorName);
					}
					
				}
			}
		}else{
			stringsArray = valuesArray[0].getElementsByTagName('string');
			if(stringsArray[0].firstChild){
				value = stringsArray[0].firstChild.nodeValue;
				inputName = document.getElementById(name);
				if(inputName){
					//For month, we make a conversion
					if(name == 'month'){
						months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dic'];
						if(value)
							inputName.setAttribute('value', months[parseInt(value) - 1]);
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