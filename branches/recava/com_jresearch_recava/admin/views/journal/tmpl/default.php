<?php
/**
 * @package JResearch
 * @subpackage Financiers
 * Default view for adding/editing a single financier
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" method="post" class="form-validate" onSubmit="return validate(this);"  >
<table class="editpublication" cellpadding="5" cellspacing="5">
<tbody>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_JOURNAL')?></th>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_TITLE').': '?></td>
		<td>
			<input name="title" id="name" size="60" maxlength="255" value="<?php echo isset($this->journal)?$this->journal->title:'' ?>" class="required" />
			<br />
			<label for="title" class="labelform" ><?php echo JText::_('JRESEARCH_JOURNAL_PROVIDE_VALID_TITLE'); ?></label>
		</td>
		<td><?php echo JText::_('Published').': '; ?></td>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('JRESEARCH_IMPACT_FACTOR').': '?></td>
		<td colspan="3">
			<input name="impact_factor" id="impact_factor" size="3" maxlength="10" value="<?php echo isset($this->journal)?$this->journal->impact_factor:'' ?>" class="validate-number" />
			<br />
			<label for="impact_factor" class="labelform" ><?php echo JText::_('JRESEARCH_JOURNAL_PROVIDE_VALID_IMPACT_FACTOR'); ?></label>
		</td>
	</tr>
</tbody>
</table>
<input type="hidden" name="option" value="com_jresearch" />
<input type="hidden" name="task" value="" />		
<input type="hidden" name="controller" value="journals" />
<input type="hidden" name="id" value="<?php echo isset($this->journal)?$this->journal->id:'' ?>" />	
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>
</form>