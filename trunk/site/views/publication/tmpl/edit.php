<div class="componentheading" style="float: left;">
	<?=JText::_('JRESEARCH_EDIT_PUBLICATION');?>
</div>
<?php 
if(JHTML::_('jresearch.authorize','edit', 'publications', $this->id))
{
	if($this->id > 0)
	{
?>
	<div style="float: right;">
		<button type="button" onclick="javascript:msubmitform('apply');"><?php echo JText::_('Apply'); ?></button>
		<button type="button" onclick="javascript:msubmitform('save')"><?php echo JText::_('Save') ?></button>
		<button type="button" onclick="javascript:msubmitform('cancel')"><?php echo JText::_('Cancel'); ?></button>
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
}
else
{
?>
	<div style="clear: both;">&nbsp;</div>
	<div style="text-align:center;"><?=JText::_('JRESEARCH_ACCESS_NOT_ALLOWED')?></div>
<?php
}
?>