<?php
/**
 * @package JResearch
 * @subpackage Projects
 * Default view for adding/editing a single project
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" action="./" method="post" class="form-validate" onsubmit="return validate(this);">
<table class="edit" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th><?php echo JText::_('Position').': '?></th>
		<td colspan="3">
			<input name="position" id="position" size="80" maxlength="255" value="<?php echo $this->item?$this->item->position:'' ?>" class="required" />
			<br />
			<label for="position" class="labelform"><?php echo JText::_('JRESEARCH_MEMBER_PROVIDE_VALID_POSITION'); ?></label>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td colspan="3"><?php echo $this->publishedRadio; ?></td>
	</tr>
</tbody>
</table>

<input type="hidden" name="id" value="<?php echo $this->item?$this->item->id:'' ?>" />		
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'member_positions'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
</form>
