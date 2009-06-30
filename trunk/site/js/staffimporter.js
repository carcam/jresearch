/**
* Move the selected item in the first list to the second.
* @param usersSelect Id of the source list
* @param staffSelect Id of the target list
*/
function moveFrom(source, target){
	sourceControl = document.getElementById(source);
	targetControl = document.getElementById(target);
	
	selectedOption = sourceControl.options[sourceControl.selectedIndex];
	if(selectedOption != null){
		sourceControl.removeChild(selectedOption);
		targetControl.appendChild(selectedOption);		
	}
}

/**
* Adds a hidden field with the value sent as parameter. The name
* follows this convention: member{N+1} where N is the index of the
* last created hidden field.
*/
function addHiddenField(value){
	staffCount = document.getElementById("staffCount");
	n = parseInt(staffCount.getAttribute('value'));
	staffCount.setAttribute('value', n + 1);
	newInput = document.createElement("input");
	newInput.setAttribute("name", "member"+n);
	newInput.setAttribute("value", value);
	newInput.setAttribute("id", "member"+n);
	newInput.setAttribute("type", "hidden");
	document.adminForm.appendChild(newInput);
}

function moveAllFrom(source, target, addFields){
	sourceControl = document.getElementById(source);
	targetControl = document.getElementById(target);
	
	for(i=0; i<sourceControl.options.length; i++){
		control = sourceControl.options[i].cloneNode(true);
		targetControl.appendChild(control);
		if(addFields)
			addHiddenField(control.value);
		else
			removeHiddenField(control.value);					
	}
	
	sourceControl.innerHTML = '';
	
	

}



function removeHiddenField(value){
	staffCount = document.getElementById("staffCount");
	n = parseInt(staffCount.getAttribute('value'));

	for(i=0; i<=n; i++){
		input = document.getElementById("member"+i);
		if(input != null){
			if(input.getAttribute('value') == value){
				document.adminForm.removeChild(input);
				break;
			}
		}
	}	
}