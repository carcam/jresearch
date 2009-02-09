
/**
* Allows to switch between single field for externals URLs or
* a set of file uploaders.
*/
function switchUploader(controlName){
	textdiv = document.getElementById('div_text_'+controlName);
	uploaddiv = document.getElementById('div_upload_'+controlName);
	aupload = document.getElementById('a_upload_'+controlName);
	aenter = document.getElementById('a_enter_'+controlName);	

	if(textdiv && uploaddiv && aupload && aenter){
		if(textdiv.style.display == 'block'){
			textdiv.style.display = 'none';
			aenter.style.display = 'block';			
			uploaddiv.style.display = 'block';
			aupload.style.display = 'none';
		}else{
			textdiv.style.display = 'block';
			aenter.style.display = 'none';						
			uploaddiv.style.display = 'none';
			aupload.style.display = 'block';				
		}
	}
}

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
	newUpload.setAttribute('name', 'file_'+controlName+count);
	newUpload.setAttribute('id', 'file_'+controlName+count);	
	newUpload.setAttribute('type', 'file');
	
	deleteAdd = document.createElement('a');
	deleteAdd.setAttribute('id', 'delete_'+controlName+count);
	deleteAdd.setAttribute('href', 'javascript:deleteUpload(\''+controlName+'\', '+count+')');
	deleteAdd.appendChild(document.createTextNode(deleteMessage));
	
	br = document.createElement('br');
	br.setAttribute('id', 'br_'+controlName+count);
	
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
	uploadControl = document.getElementById('file_'+controlName+count);
	deleteA = document.getElementById('delete_'+controlName+count);
	br = document.getElementById('br_'+controlName+count);
	
	if(uploaddiv){
		if(uploadControl)
			uploaddiv.removeChild(uploadControl);
		if(deleteA)
			uploaddiv.removeChild(deleteA);	
		if(br)
			uploaddiv.removeChild(br);	
	}
}