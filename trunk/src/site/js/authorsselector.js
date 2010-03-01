/**
* Adds an additional input for a new author. By default this function adds
* a control for external authors which consists in a text field with a couple
* of links
* @param controlName Name of the control holding the last author. Usually in 
* the format someName{index} where index is an integer greater than 0.
*/
function addControl(controlName, addAuthorText, deleteAuthorText, buttonTextInternal, buttonText, maxAuthors, allowPrincipals, allowPrincipalsText){
	indexField = document.getElementById(maxAuthors);
	index = indexField.getAttribute('value');

	index++;
	baseNameArray = controlName.match(/^([_a-zA-Z]+)([0-9]+)$/);
	if(!baseNameArray) return;
	
	baseName = baseNameArray[1];
	baseControl = document.getElementById(baseName);
	// We construct a new item
	if(baseControl != null){
		div = document.createElement("div");
		div.setAttribute("id", "div_"+baseControl.getAttribute('id')+index);
		
		input = document.createElement("input");
		input.setAttribute("name", baseControl.getAttribute('id')+index);
		input.setAttribute("id", baseControl.getAttribute('id')+index);
		input.setAttribute("class", "inputbox");
		input.setAttribute("classname", "inputbox");
		input.setAttribute("size", "15");
		input.setAttribute("maxlength", "255");
		
		button = document.createElement('a');
		button.setAttribute('href', "javascript:switchType('"+baseControl.getAttribute('id')+index+"', '"+buttonTextInternal+"', '"+buttonText+"') ");
		button.setAttribute('id', 'a_'+baseControl.getAttribute('id')+index); 
		button.appendChild(document.createTextNode(buttonTextInternal));
 		
		addAuthor = document.getElementById("add"+baseName);
		addAuthor.setAttribute('href', "javascript:addControl('"+baseControl.getAttribute('id')+index+"', '"+addAuthorText+"', '"+deleteAuthorText+"', '"+ buttonTextInternal +"', '"+buttonText +"' ,'"+maxAuthors+"', '"+allowPrincipals+"', '"+allowPrincipalsText+"')");
		
		
		deleteAuthor = document.createElement("a");
		deleteAuthor.setAttribute("href", "javascript:deleteControl('"+baseControl.getAttribute('id')+index+"')");
		deleteAuthor.appendChild(document.createTextNode(deleteAuthorText));
		
		if(allowPrincipals){
			principalCheck = document.createElement("input");
			principalCheck.setAttribute('type', 'checkbox');
			principalCheck.setAttribute('name', 'check_'+baseControl.getAttribute('id')+index);
			principalCheck.setAttribute('id', 'check_'+baseControl.getAttribute('id')+index);
			principalCheckText = document.createTextNode(allowPrincipalsText+':  ');
		}
		
		enter = document.createElement("br");
		
		div.appendChild(input);
		div.appendChild(document.createTextNode("   "));
		div.appendChild(button);
		div.appendChild(enter);
		div.appendChild(deleteAuthor);
		
		if(allowPrincipals){
			div.appendChild(document.createTextNode("   "));		
			div.appendChild(principalCheckText);
			div.appendChild(principalCheck);
		}
		
		baseControl.appendChild(div);
	}	
	
	indexField.setAttribute("value", index);
}

/**
* Deletes the control whose id is sent as parameter. As a control is compound
* of several elements, it removes the parent of all them.
*/
function deleteControl(controlName){
	div = document.getElementById("div_"+controlName);
	parentControl = div.parentNode;
	baseControl = document.getElementById(controlName.substring(0, controlName.length - 1));

	if(div != null){
		parentControl.removeChild(div);	
	}
}

function switchType(controlName, internalText, externalText){
	control = document.getElementById(controlName);

	parentControl = control.parentNode;
	switchText = document.getElementById("a_"+controlName);
	
	switchText.setAttribute('href', "javascript:switchType('"+controlName+"', '"+internalText+"', '"+externalText+"')");	

 
	if(control.nodeName == 'INPUT'){
		switchText.innerHTML = externalText;
		sampleControl = document.getElementById('sample');
		newControl = sampleControl.cloneNode(true);
		newControl.setAttribute('id', controlName);
		newControl.setAttribute('name', controlName);
		newControl.style.display = 'inline';
	}else{
		switchText.innerHTML = internalText;
		newControl = document.createElement("input");
		newControl.setAttribute("name", controlName);
		newControl.setAttribute("id", controlName);
		newControl.setAttribute("class", "inputbox");
		newControl.setAttribute("classname", "inputbox");
		newControl.setAttribute("size", "15");
		newControl.setAttribute("maxlength", "255");
	}

	parentControl.insertBefore(newControl, control);
	parentControl.removeChild(control);	
}