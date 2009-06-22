<?php
/**
 * @package JResearch
 * @subpackage Financiers
 * Default view for adding/editing a single financier
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" id="adminForm" action="./" method="post" class="form-validate" onsubmit="return validate(this);"  >
<table class="edit" cellpadding="5" cellspacing="5">
<thead>
	<tr>
		<th colspan="4"><?php echo JText::_('JRESEARCH_FINANCIER')?></th>
	</tr>
</thead>
<tbody>
	<tr>
		<th><?php echo JText::_('Name').': '?></th>
		<td>
			<input name="name" id="name" size="60" maxlength="60" value="<?php echo $this->financier?$this->financier->name:'' ?>" class="required" />
			<br />
			<label for="name" class="labelform" ><?php echo JText::_('JRESEARCH_FINANCIER_PROVIDE_VALID_NAME'); ?></label>
		</td>
		<th><?php echo JText::_('Published').': '; ?></th>
		<td><?php echo $this->publishedRadio; ?></td>
	</tr>
	<tr>
		<th><?php echo JText::_('URL').': '?></th>
		<td colspan="3">
			<input name="url" id="url" size="50" maxlength="255" value="<?php echo $this->financier?$this->financier->url:'' ?>" class="validate-url" />
			<br />
			<label for="url" class="labelform" ><?php echo JText::_('JRESEARCH_FINANCIER_PROVIDE_VALID_URL'); ?></label>
		</td>
	</tr>
</tbody>
</table>
<input type="hidden" name="id" value="<?php echo $this->financier?$this->financier->id:'' ?>" />
<?php echo JHTML::_('jresearchhtml.hiddenfields', 'financiers'); ?>
<?php echo JHTML::_('behavior.keepalive'); ?>
<?php echo JHTML::_('form.token'); ?>	
</form>