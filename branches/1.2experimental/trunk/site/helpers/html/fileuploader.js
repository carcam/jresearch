/**
* Appends a upload control.
*/
function addUploader(controlName, deleteMessage){
	uploaddiv = document.getElementById('div_upload_'+controlName);
	if(!uploaddiv)
		return;
	hiddenCount = document.getElementById('count_'+controlName);
	if(!hiddenCount)
		return;
		
	count = hiddenCount.getAttribute('value');
	count++;
	
	newUpload = document.createElement('input');
	newUpload.setAttribute('name', 'file_'+controlName+'_'+count);
	newUpload.setAttribute('id', 'file_'+controlName+'_'+count);	
	newUpload.setAttribute('type', 'file');
	
	deleteAdd = document.createElement('a');
	deleteAdd.setAttribute('id', 'delete_'+controlName+'_'+count);
	deleteAdd.setAttribute('href', 'javascript:deleteUpload(\''+controlName+'\', '+count+')');
	deleteAdd.appendChild(document.createTextNode(deleteMessage));
	
	br = document.createElement('br');
	br.setAttribute('id', 'br_'+controlName+'_'+count);
	
	aAdd = document.getElementById('add_'+controlName);
	uploaddiv.insertBefore(newUpload, aAdd);
	uploaddiv.insertBefore(deleteAdd, aAdd);
	uploaddiv.insertBefore(br, aAdd);	
	
	hiddenCount.setAttribute('value', count);
	
}

/**
* Removes an upload control.
*/
function deleteUpload(controlName, count){
	uploaddiv = document.getElementById('div_upload_'+controlName);
	uploadControl = document.getElementById('file_'+controlName+'_'+count);
	deleteA = document.getElementById('delete_'+controlName+'_'+count);
	br = document.getElementById('br_'+controlName+'_'+count);
	
	if(uploaddiv){
		if(uploadControl)
			uploaddiv.removeChild(uploadControl);
		if(deleteA)
			uploaddiv.removeChild(deleteA);	
		if(br)
			uploaddiv.removeChild(br);	
	}
}