<?php
/**
 * @package JResearch
 * @subpackage Staff
 * Default view for editing a member in the frontend
 */
?>
<div style="float: right;">
	<button type="button" onclick="javascript:msubmitform('apply');"><?php echo JText::_('Apply'); ?></button>
	<button type="button" onclick="javascript:msubmitform('save')"><?php echo JText::_('Save') ?></button>
	<button type="button" onclick="javascript:msubmitform('cancel')"><?php echo JText::_('Cancel'); ?></button>
</div>
<div style="clear: right">&nbsp;</div>
<?php 
/**
 * Require the administrator template.
 */
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'member'.DS.'tmpl'.DS.'default.php'); 

?>