<div class="componentheading" style="float: left;">
	<?php echo JText::_('JRESEARCH_'.JString::strtoupper(JRequest::getVar('task')).'_PUBLICATION');?>
</div>
<?php 
if((JHTML::_('Jresearch.authorize','edit', 'publications', $this->id) && ($this->id > 0)) || (JHTML::_('Jresearch.authorize','add', 'publications') && ($this->id <= 0))):
?>
	<div style="float: right;">
		<button type="button" onclick="javascript:msubmitform('apply');"><?php echo JText::_('JRESEARCH_APPLY'); ?></button>
		<button type="button" onclick="javascript:msubmitform('save')"><?php echo JText::_('JRESEARCH_SAVE') ?></button>
		<button type="button" onclick="javascript:msubmitform('cancel')"><?php echo JText::_('JRESEARCH_CANCEL'); ?></button>
	</div>
	<div style="clear: both;"></div>
	<?php
	include_once(JPATH_COMPONENT_SITE.DS.'views'.DS.'publication'.DS.'tmpl'.DS.'form.php');
else:
?>
	<div style="clear: both;"></div>
	<div style="text-align:center;"><?php echo JText::_('JRESEARCH_ACCESS_NOT_ALLOWED')?></div>
<?php
endif;
?>