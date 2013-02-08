function startSelectedRecordRemoval(selectListId){
	var citekey, citedRecordsList, selectedIndex;
	var removeRequest;
	citedRecordsList = document.getElementById(selectListId);
	selectedIndex = citedRecordsList.selectedIndex;
	if(selectedIndex > 0){
		citekey = citedRecordsList.options[citedRecordsList.selectedIndex].value;
		removeRequest = new Request({method: 'get', url: 'index.php?option=com_jresearch&controller=publications&task=removeCitedRecord&citekey='+citekey, onSuccess: removeSelectedRecord }).send(null);				
	}
}
	
function removeSelectedRecord(responseText, responseXML){
		
}