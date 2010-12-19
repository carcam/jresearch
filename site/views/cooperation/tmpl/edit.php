<h1 class="componentheading" style="float: left;">
	<?php echo JText::_('JRESEARCH_'.JString::strtoupper(JRequest::getVar('task')).'_COOPERATION');?>
</h1>
<?php
if((JHTML::_('Jresearch.authorize','edit', 'cooperations', $this->coop->id) && ($this->coop->id > 0)) || (JHTML::_('Jresearch.authorize','add', 'cooperations') && ($this->coop->id <= 0))):
?>
<div style="float: right;">
	<button type="button" onclick="javascript:msubmitform('save');">Save</button>
	<button type="button" onclick="javascript:msubmitform('cancel')">Cancel</button>
</div>
<div style="clear: right">&nbsp;</div>
<?php
include_once(JRESEARCH_COMPONENT_ADMIN.DS.'views'.DS.'cooperation'.DS.'tmpl'.DS.'default.php');
else:
?>
	<div style="text-align:center; clear: both;"><?php echo JText::_('JRESEARCH_ACCESS_NOT_ALLOWED')?></div>
<?php
endif;
?>