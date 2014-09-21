<?php 
/**
* @package JResearch
* @subpackage Publications
* @license GNU/GPL
* View for showing the citation dialog.
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<script type="text/javascript">
function makeCitation(command){
	var citeRequest;
	if(selectedCitekeys.length == 0){
		alert("<?php echo JText::_('JRESEARCH_NO_ITEMS_TO_CITE'); ?>");
		return;
	}
		
	var citekeys = selectedCitekeys.join(",");		
	var queryString = "option=com_jresearch&controller=publications&task=cite&command="+command+"&citekeys="+encodeURIComponent(citekeys)+"&format=text";
	citeRequest = new Request({method: 'get', async: true , onSuccess: onCiteSuccessful, onFailure: onCiteFailure});
	citeRequest.send(queryString, null);		
}
</script>
<div style="text-align:center; width:100%;">
	<label for="citedRecords"><?php echo JText::_('JRESEARCH_CITED_RECORDS').':'; ?></label>
	<?php echo $this->citedRecords; ?>
	<?php echo $this->removeButton; ?>
</div>
<div>&nbsp;</div>
<div style="float: right;">
	<?php echo $this->citeButton; ?>
	<?php echo $this->citeParentheticalButton; ?>
	<?php echo $this->citeYearButton; ?>
	<?php echo $this->noCiteButton; ?>
	<?php echo $this->closeButton; ?>
</div>
<div style="width:100%">
	<label for="title"><?php echo JText::_('JRESEARCH_SEARCH').': '; ?></label> <input id="title" name="title" type="text" />
	<table style="width: 100%">
		<tr>
			<td><label for="all"><?php echo JText::_('JRESEARCH_ALL').':' ?></label><input name="criteria" id="allRadio" value="all" type="radio" /></td>
			<td><label for="keywords"><?php echo JText::_('JRESEARCH_BY_KEYWORDS').':' ?></label><input id="keywordsRadio" name="criteria" value="keywords" type="radio" /></td>
			<td><label for="title"><?php echo JText::_('JRESEARCH_BY_TITLE').':' ?></label><input name="criteria" id="titleRadio" value="title" type="radio" /></td>
			<td><label for="year"><?php echo JText::_('JRESEARCH_BY_YEAR').':' ?></label><input name="criteria" value="year" id="yearRadio" type="radio" /></td>
			<td><label for="authors"><?php echo JText::_('JRESEARCH_BY_AUTHORS').':' ?></label><input name="criteria" id="authorsRadio" value="authors" type="radio" /></td>
			<td><label for="citekey"><?php echo JText::_('JRESEARCH_BY_CITEKEY').':' ?></label><input name="criteria" id="citekeyRadio" value="citekey" type="radio" /></td>
		</tr>
	</table>
	<div>&nbsp;</div>
	<table class="adminlist" style="text-align:center;">
	<thead>
		<tr>
			<th class="title" width="40%"><?php echo JText::_('JRESEARCH_TITLE'); ?></th>
			<th class="title" width="10%"><?php echo JText::_('JRESEARCH_CITEKEY'); ?></th>
			<th class="title" width="30%"><?php echo JText::_('JRESEARCH_AUTHORS'); ?></th>
			<th class="title" width="10%"><?php echo JText::_('JRESEARCH_PUBLICATION_TYPE'); ?></th>
			<th class="title" width="10%"><?php echo JText::_('JRESEARCH_YEAR'); ?></th>
		</tr>
	</thead>
	<tbody id="results">
	</tbody>
	</table>
</div>