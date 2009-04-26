<div class="componentheading" style="float: left;">
	<?=JText::_('JRESEARCH_'.JString::strtoupper(JRequest::getVar('task')).'_PUBLICATION');?>
</div>
<?php 
if((JHTML::_('Jresearch.authorize','edit', 'publications', $this->id) && ($this->id > 0)) || (JHTML::_('Jresearch.authorize','add', 'publications') && ($this->id <= 0)))
{
?>
	<div style="float: right;">
		<button type="button" onclick="javascript:msubmitform('apply');"><?php echo JText::_('Apply'); ?></button>
		<button type="button" onclick="javascript:msubmitform('save')"><?php echo JText::_('Save') ?></button>
		<button type="button" onclick="javascript:msubmitform('cancel')"><?php echo JText::_('Cancel'); ?></button>
	</div>
	<div style="clear: both;">&nbsp;</div>
	<?php
	include_once(JPATH_PLUGINS.DS.'jresearch'.DS.'plg_jresearch_databases_modified_publication_form'.DS.'tmpl'.DS.'default.php');
}
else
{
?>
	<div style="clear: both;">&nbsp;</div>
	<div style="text-align:center;"><?=JText::_('JRESEARCH_ACCESS_NOT_ALLOWED')?></div>
<?php
}
?>