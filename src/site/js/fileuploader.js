function createTagForm(controlName, labelText) {
    label = document.createElement('label');
    label.setAttribute('for', controlName);
    label.setAttribute('class', 'labelfiletag');
    label.appendChild(document.createTextNode(labelText));
    
    input = document.createElement('input');
    input.setAttribute('type', 'text');
    input.setAttribute('id', controlName);
    input.setAttribute('name', controlName);
    input.setAttribute('class', 'inputfiletag');
    input.setAttribute('size', '255');    
    
    span = document.createElement('span');
    span.appendChild(label);
    span.appendChild(input);
    
    return span;
}

/**
* Appends a upload control.
*/
function addUploader(controlName, deleteMessage, tagMessage, upload){
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
    if (upload) {
        newUpload.setAttribute('type', 'file');
        newUpload.setAttribute('class', 'attachmentfield');        
    } else {
        newUpload.setAttribute('class', 'urlfield validate-url');
        newUpload.setAttribute('type', 'text');        
    }
    newTagField = createTagForm('jform[file_tag_'+controlName+'_'+count+']', tagMessage);

    deleteAdd = document.createElement('a');
    deleteAdd.setAttribute('id', 'delete_'+controlName+'_'+count);
    deleteAdd.setAttribute('href', 'javascript:deleteUpload(\''+controlName+'\', '+count+')');
    deleteAdd.appendChild(document.createTextNode(deleteMessage));

    newLi = document.createElement('li');
    newLi.setAttribute('id', 'li_'+controlName +'_'+count);

    aAdd = document.getElementById('add_'+controlName);
    aAdd.style.display = 'inline';
    newLi.appendChild(newUpload);
    newLi.appendChild(newTagField);
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