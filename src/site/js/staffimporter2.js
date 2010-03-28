/**
* Move the selected item in the first list to the second.
* @param usersSelect Id of the source list
* @param staffSelect Id of the target list
* @param add True if a new staff member is being added (from left control to right)
*/
function moveFrom(source, target, add){
	var sourceControl = document.getElementById(source);
	var targetControl = document.getElementById(target);
	var current = sourceControl.options[0];
	while(current){
		previous = current.previousSibling;
		if(current.selected){
			sourceControl.removeChild(current);
			targetControl.appendChild(current);
			if(add)
				addHiddenField(current.value);
			else
				removeHiddenField(current.value);			
			if(previous){
				current = previous;
			}else{
				current = sourceControl.options[0];
			}
			
		}else		
			current = current.nextSibling;		
		
	}

}

/**
 * Moves all items from source to target
 * @param source
 * @param target
 * @param add True if we are adding staff members (from left control to right)
 * @return
 */
function moveAllFrom(source, target, add){
	sourceControl = document.getElementById(source);
	targetControl = document.getElementById(target);
	
	for(i=0; i<sourceControl.options.length; i++){
		control = sourceControl.options[i].cloneNode(true);
		targetControl.appendChild(control);					
		if(add)
			addHiddenField(sourceControl.options[i].value);
	}
	
	if(!add){
		var staffCount = document.adminForm["staffCount"];
		var n = parseInt(staffCount.getAttribute('value'));
		for(j = 0; j < n; j++){
			if(document.adminForm['member'+j] != null)
				document.adminForm.removeChild(document.adminForm['member'+j]);
		}
		staffCount.setAttribute('value', 0);
	}
	
	sourceControl.innerHTML = '';
}

/**
 * Moves one place up the selected item in the control
 * @param selectControl
 * @return
 */
function goUp(selectControl){
	var control = document.getElementById(selectControl);
	
	var selectedIndex = control.selectedIndex;
	if(selectedIndex > 0){
		var tmp = control.options[selectedIndex].cloneNode(true);

		//Change the order of hidden fields
		member1 = document.getElementById('member'+selectedIndex);
		member2 = document.getElementById('member'+(selectedIndex- 1));
		member1.setAttribute('id', 'member'+(selectedIndex - 1));
		member1.setAttribute('name', 'member'+(selectedIndex - 1));
		member2.setAttribute('id', 'member'+selectedIndex);
		member2.setAttribute('name', 'member'+selectedIndex);
		
		control.insertBefore(tmp, control.options[selectedIndex - 1]);
		control.removeChild(control.options[selectedIndex + 1]);
		control.selectedIndex = selectedIndex - 1;		
	}

		
}

/**
 * Moves one place down the selected item in the control
 * @param selectControl
 * @return
 */
function goDown(selectControl){
	var control = document.getElementById(selectControl);
	
	var selectedIndex = control.selectedIndex;
	if(selectedIndex < control.options.length - 1){
		var tmp = control.options[selectedIndex].cloneNode(true);
		
		//Change the order of hidden fields
		member1 = document.getElementById('member'+selectedIndex);
		member2 = document.getElementById('member'+(selectedIndex + 1));
		member1.setAttribute('id', 'member'+(selectedIndex + 1));
		member1.setAttribute('name', 'member'+(selectedIndex + 1));
		member2.setAttribute('id', 'member'+selectedIndex);
		member2.setAttribute('name', 'member'+selectedIndex);

		
		insertAfter(control.options[selectedIndex + 1], tmp);
		control.removeChild(control.options[selectedIndex]);
		control.selectedIndex = selectedIndex + 1;		
	}
}

function insertAfter( referenceNode, newNode )
{
    referenceNode.parentNode.insertBefore( newNode, referenceNode.nextSibling );
}

function addHiddenField(value){
	staffCount = document.adminForm["staffCount"];
	n = parseInt(staffCount.getAttribute('value'));
	staffCount.setAttribute('value', n + 1);
	newInput = document.createElement("input");
	newInput.setAttribute("name", "member"+n);
	newInput.setAttribute("value", value);
	newInput.setAttribute("id", "member"+n);
	newInput.setAttribute("type", "hidden");
	document.adminForm.appendChild(newInput);
}

function removeHiddenField(value){
	var staffCount = document.adminForm["staffCount"];
	var n = parseInt(staffCount.getAttribute('value'));
	var k = -1;
	for(i = 0; i < n; i++){
		input = document.getElementById("member"+i);
		if(input != null){
			if(input.getAttribute('value') == value){
				document.adminForm.removeChild(input);
				staffCount.setAttribute('value', n - 1);
				//Lets adjust indices
				k = i;
				break;
			}
		}
	}
	
	if(k != -1){
		for(j = k + 1; j < n; j++){
			if(document.adminForm['member'+j] != null){
				document.adminForm['member'+j].setAttribute('name', 'member'+(j - 1));
				document.adminForm['member'+j].setAttribute('id', 'member'+(j - 1));				
			}
		}
	}
}