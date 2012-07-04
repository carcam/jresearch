/**
* Appends a upload control.
*/
function addUploader(controlName, deleteMessage){
	uploaddiv = document.getElementById('div_upload_'+controlName);
	if(!uploaddiv)
		return;
	hiddenCount = document.getElementById('jform[count_'+controlName+']');
	if(!hiddenCount)
		return;
		
	count = hiddenCount.getAttribute('value');
	count++;
	
	newUpload = document.createElement('input');
	newUpload.setAttribute('name', 'jform[file_'+controlName+'_'+count+']');
	newUpload.setAttribute('id', 'jform[file_'+controlName+'_'+count+']');	
	newUpload.setAttribute('type', 'file');
	
	deleteAdd = document.createElement('a');
	deleteAdd.setAttribute('id', 'delete_'+controlName+'_'+count);
	deleteAdd.setAttribute('href', 'javascript:deleteUpload(\''+controlName+'\', '+count+')');
	deleteAdd.appendChild(document.createTextNode(deleteMessage));
	
	newLi = document.createElement('li');
	newLi.setAttribute('id', 'li_'+controlName +'_'+count);
	
	aAdd = document.getElementById('add_'+controlName);
	aAdd.style.display = 'inline';
	newLi.appendChild(newUpload);
	newLi.appendChild(deleteAdd);
	uploaddiv.appendChild(newLi);
	
	hiddenCount.setAttribute('value', count);
	
}

/**
* Removes an upload control.
*/
function deleteUpload(controlName, count){
	uploaddiv = document.getElementById('div_upload_'+controlName);
	uploadControl = document.getElementById('li_'+controlName+'_'+count);
	
	if(uploaddiv){
		if(uploadControl)
			uploaddiv.removeChild(uploadControl);
	}
}