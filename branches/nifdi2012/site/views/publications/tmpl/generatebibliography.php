<?php 
/**
 * @package JResearch
 * @subpackage Publications
 * View for generating bibliography
 */
?>
<script type="text/javascript">
var selectedIndex;
var citedRecordsList;


function startSelectedRecordRemoval(){
	var citekey;
	var removeRequest;
	
	citedRecordsList = getRecordsList();
	selectedIndex = citedRecordsList.selectedIndex;
	if(selectedIndex >= 0){
		citekey = citedRecordsList.options[selectedIndex].value;
		removeRequest = new Request({method: 'get', async: true , onSuccess: removeSelectedRecord, onFailure: onRemovalFailure});
		removeRequest.send('option=com_jresearch&controller=publications&task=removeCitedRecord&format=text&citekey='+citekey, null);
	}
}
	
function removeSelectedRecord(response, responseXML){
	answer = responseXML.getElementsByTagName('answer');
	if(answer.length > 0){
		alert("<?php echo JText::_('CITED_RECORD_NOT_FOUND', true) ?>");			
	}else{
		alert("<?php echo JText::_('CITED_RECORD_REMOVAL_SUCCESSFUL', true) ?>");
		var list = getRecordsList();
		clean(list);
		keyList = responseXML.getElementsByTagName('key');
		titleList = responseXML.getElementsByTagName('title');
		for(i = 0; i< keyList.length; i++){
			keyText = keyList[i].firstChild.nodeValue;
			titleText = titleList[i].firstChild.nodeValue;
			
			option = document.createElement('option');
			option.setAttribute('value', keyText);
			option.appendChild(document.createTextNode(keyText+': '+titleText));
			list.appendChild(option);
		}
	}

}

function onRemovalFailure(){
	alert("<?php echo JText::_('CITED_RECORD_REMOVAL_FAILED', true) ?>");
}

function requestBibliographyGeneration(){
	keys = '';
	citedRecordsList = getRecordsList();
	n = citedRecordsList.options.length;
	
	if(n == 0){
		alert("<?php echo JText::_('NO_RECORDS_TO_GENERATE_BIBLIOGRAPHY'); ?>");
		return;
	}
			
	bibliographyRequest = new Request({method: 'get', async: true, onSuccess: generateBibliography, onFailure: onBibliographyGenerationFailure});
	bibliographyRequest.send('option=com_jresearch&controller=publications&task=ajaxGenerateBibliography&format=raw', null);
}
	
function generateBibliography(response){
	window.parent.jInsertEditorText(response, '<?php echo preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', JRequest::getVar('e_name') ); ?>');
	window.parent.document.getElementById('sbox-window').close();
	return false;
}

function onBibliographyGenerationFailure(){
	alert("<?php echo JText::_('BIBLIOGRAPHY_GENERATION_FAILED', true) ?>");
}

function startAllRemoval(){
	citedRecordsList = getRecordsList();
	n = citedRecordsList.options.length;
	
	bibliographyRequest = new Request({method: 'get', async: true ,onSuccess: removeAllRecords, onFailure: onAllRemovalFailure});
	bibliographyRequest.send('option=com_jresearch&controller=publications&task=ajaxRemoveAll&format=text', null);	
}

function removeAllRecords(response){
	if(response == 'success')
		clean(getRecordsList());
	else
		alert("<?php echo JText::_('BIBLIOGRAPHY_REMOVAL_FAILED', true) ?>");
}

function clean(element){
	while(element.hasChildNodes())
		element.removeChild(element.firstChild);	
}

function onAllRemovalFailure(){
	alert("<?php echo JText::_('BIBLIOGRAPHY_REMOVAL_FAILED', true) ?>");
}

function getRecordsList(){
	if(citedRecordsList == null){
		citedRecordsList = document.getElementById('citedRecords');
	}
	return citedRecordsList;
	
}
</script>


<div style="text-align: center; width:100%;">
	<label for="records"><?php echo JText::_('JRESEARCH_CITED_RECORDS').':' ?></label>
		<?php echo $this->citedRecordsListHTML; ?>
		<?php echo $this->removeButton; ?>
		<?php echo $this->removeAllButton; ?>
		<div>&nbsp;</div>
		<div style="float: right;">
			<?php echo $this->generateBibButton; ?>
		</div>
</div>