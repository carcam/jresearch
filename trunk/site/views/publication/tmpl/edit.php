<div class="componentheading" style="float: left;">
	<?=JText::_('JRESEARCH_EDIT_PUBLICATION');?>
</div>
<?php 
if($this->id > 0)
{
?>
	<div style="float: right;">
		<button type="button" onclick="javascript:msubmitform('apply');">Apply</button>
		<button type="button" onclick="javascript:msubmitform('cancel')">Cancel</button>
	</div>
	<div style="clear: both;">&nbsp;</div>
<?php
	include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'publication'.DS.'tmpl'.DS.'default.php');
}
else
{
?>
	<div style="clear: both;">&nbsp;</div>
	<div style="text-align:center;"><?=JText::_('JRESEARCH_PUBLICATION_EDIT_NO_VALID_ID')?></div>
<?php
}
?>